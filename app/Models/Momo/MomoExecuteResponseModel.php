<?php
// app/Models/Momo/MomoExecuteResponseModel.php
namespace App\Models\Momo;

class MomoExecuteResponseModel
{
    public $order_id;
    public $amount;
    public $order_info;
    public $transaction_id;
    public $result_code;
    public $message;

    public function __construct(array $responseData)
    {
        $this->order_id = $responseData['orderId'] ?? '';
        $this->amount = $responseData['amount'] ?? '';
        $this->order_info = $responseData['orderInfo'] ?? '';
        $this->transaction_id = $responseData['transId'] ?? '';
        $this->result_code = $responseData['resultCode'] ?? '';
        $this->message = $responseData['message'] ?? '';
    }
}