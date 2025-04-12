<?php
// app/Services/MomoService.php
namespace App\Services;

use App\Models\Momo\MomoCreatePaymentResponseModel;
use App\Models\Momo\MomoExecuteResponseModel;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MomoService
{
    protected $apiUrl;
    protected $secretKey;
    protected $accessKey;
    protected $returnUrl;
    protected $notifyUrl;
    protected $partnerCode;
    protected $requestType;

    public function __construct()
    {
        $this->apiUrl = config('momo.api_url');
        $this->secretKey = config('momo.secret_key');
        $this->accessKey = config('momo.access_key');
        $this->returnUrl = config('momo.return_url');
        $this->notifyUrl = config('momo.notify_url');
        $this->partnerCode = config('momo.partner_code');
        $this->requestType = config('momo.request_type');
    }

    /**
     * Create a payment request to MoMo
     *
     * @param array $orderInfo
     * @return array
     */
    public function createPayment(array $orderInfo): array
    {
        $requestId = time() . "";
        $orderId = $orderInfo['order_id'];
        $amountUsd = $orderInfo['amount'];
        $exchangeRate = 24000; // hoặc config('payment.exchange_rate')
        $amountVnd = round($amountUsd * $exchangeRate);
        
        // lưu lại log dễ debug
        Log::info('Converted USD to VND', [
            'usd' => $amountUsd,
            'vnd' => $amountVnd,
        ]);
        
        $amount = (string) $amountVnd;
        

        $orderInfo = $orderInfo['order_info'] ?? "Payment for order #$orderId";
        Log::info('DEBUG MoMo ENV values', [
            'returnUrl' => $this->returnUrl,
            'notifyUrl' => $this->notifyUrl
        ]);
        Log::info('DEBUG MoMo Request Data', [
            'requestId' => $requestId,
            'orderId' => $orderId,
            'amount' => $amount,
            'orderInfo' => $orderInfo
        ]);        
        $rawHash = "accessKey=" . $this->accessKey .
            "&amount=" . $amount .
            "&extraData=" .
            "&ipnUrl=" . $this->notifyUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $this->partnerCode .
            "&redirectUrl=" . $this->returnUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $this->requestType;
    
            
        $signature = hash_hmac('sha256', $rawHash, $this->secretKey);
        
        $requestData = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $this->returnUrl,
            'ipnUrl' => $this->notifyUrl,
            'extraData' => '',
            'requestType' => $this->requestType,
            'signature' => $signature
        ];
        Log::info('MoMo Request Data', $requestData);

        try {
            $response = Http::post($this->apiUrl, $requestData);
            Log::info('MoMo Raw Response', ['body' => $response->body()]);
            
            if ($response->successful()) {
                $responseData = $response->json();
                
                return [
                    'success' => true,
                    'pay_url' => $responseData['payUrl'] ?? null,
                    'qr_code_url' => $responseData['qrCodeUrl'] ?? null,
                    'message' => $responseData['message'] ?? 'Success',
                ];
            }
            
            Log::error('MoMo API Error: ' . $response->body());
            
            return [
                'success' => false,
                'message' => 'Failed to connect to MoMo API',
            ];
        } catch (\Exception $e) {
            Log::error('MoMo API Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'An error occurred while processing the payment',
            ];
        }
    }

    /**
     * Handle the payment callback from MoMo
     *
     * @param Request $request
     * @return array
     */
    public function paymentExecute(Request $request): array
    {
        $data = $request->all();
        
        // Validate the signature from MoMo
        $partnerCode = $data['partnerCode'] ?? '';
        $accessKey = $data['accessKey'] ?? '';
        $requestId = $data['requestId'] ?? '';
        $amount = $data['amount'] ?? '';
        $orderId = $data['orderId'] ?? '';
        $orderInfo = $data['orderInfo'] ?? '';
        $orderType = $data['orderType'] ?? '';
        $transId = $data['transId'] ?? '';
        $resultCode = $data['resultCode'] ?? '';
        $message = $data['message'] ?? '';
        $payType = $data['payType'] ?? '';
        $responseTime = $data['responseTime'] ?? '';
        $extraData = $data['extraData'] ?? '';
        $signature = $data['signature'] ?? '';
        
        $rawHash = "partnerCode=" . $partnerCode .
            "&accessKey=" . $accessKey .
            "&requestId=" . $requestId .
            "&amount=" . (string) $amount .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&orderType=" . $orderType .
            "&transId=" . $transId .
            "&message=" . $message .
            "&responseTime=" . $responseTime .
            "&resultCode=" . $resultCode .
            "&payType=" . $payType .
            "&extraData=" . $extraData;
            
        $calculatedSignature = hash_hmac('sha256', $rawHash, $this->secretKey);
        
        // Log the callback data for debugging
        Log::info('MoMo Callback Data', $data);
        
        if ($signature != $calculatedSignature) {
            Log::warning('MoMo Callback: Invalid signature');
            return [
                'success' => false,
                'message' => 'Invalid signature',
            ];
        }
        
        // Check if the payment was successful
        if ($resultCode == '0') {
            // Payment successful, update the order status
            try {
                // Here you would update your order status in the database
                Order::where('id', $orderId)->update(['status' => 'paid']);
                
                return [
                    'success' => true,
                    'order_id' => $orderId,
                    'amount' => (string)$amount,
                    'transaction_id' => $transId,
                    'message' => 'Payment successful',
                ];
            } catch (\Exception $e) {
                Log::error('MoMo Callback Error: ' . $e->getMessage());
                
                return [
                    'success' => false,
                    'message' => 'Failed to update order status',
                ];
            }
        } else {
            Log::warning("MoMo Callback: Payment failed with code $resultCode");
            
            return [
                'success' => false,
                'order_id' => $orderId,
                'message' => $message,
            ];
        }
    }
}