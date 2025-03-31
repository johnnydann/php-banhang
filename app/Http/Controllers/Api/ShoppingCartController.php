<?php
// app/Http/Controllers/Api/ShoppingCartController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Services\MomoService;
use App\Services\ShoppingCartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShoppingCartController extends Controller
{
    protected $cartService;
    protected $momoService;

    public function __construct(ShoppingCartService $cartService, MomoService $momoService)
    {
        $this->cartService = $cartService;
        $this->momoService = $momoService;
    }

    /**
     * Get the current cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCart()
    {
        $cart = $this->cartService->getCart();
        
        return response()->json($cart);
    }

    /**
     * Add a product to the cart
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Get the product from the database
        $product = Product::findOrFail($productId);

        // Add the product to the cart
        $cart = $this->cartService->addToCart(
            $productId,
            $product->name,
            $product->image_url,
            $product->price,
            $quantity
        );

        return response()->json([
            'message' => 'Product added to cart successfully!',
            'cart' => $cart
        ]);
    }

    /**
     * Remove a product from the cart
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productId = $request->input('product_id');

        // Remove the product from the cart
        $cart = $this->cartService->removeFromCart($productId);

        return response()->json([
            'message' => 'Product removed from cart successfully!',
            'cart' => $cart
        ]);
    }

    /**
     * Update the quantity of a product in the cart
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateQuantity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Update the quantity of the product in the cart
        $cart = $this->cartService->updateQuantity($productId, $quantity);

        return response()->json([
            'message' => 'Product quantity updated successfully!',
            'cart' => $cart
        ]);
    }

    /**
     * Process the checkout
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string|in:cash,online',
            'shipping_address' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cart = $this->cartService->getCart();
        
        if (empty($cart['items'])) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        // Create a new order
        $order = new Order();
        $order->user_id = Auth::id() ?? null; // If user is not logged in, user_id will be null
        $order->order_date = now();
        $order->total_price = $cart['total'];
        $order->shipping_address = $request->input('shipping_address');
        $order->notes = $request->input('notes');
        $order->save();

        // Create order details
        foreach ($cart['items'] as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $item['product_id'];
            $orderDetail->quantity = $item['quantity'];
            $orderDetail->price = $item['price'];
            $orderDetail->save();
        }

        // Process payment based on the selected method
        $paymentMethod = $request->input('payment_method');

        if ($paymentMethod === 'cash') {
            // Clear the cart after successful order
            $this->cartService->clearCart();

            return response()->json([
                'message' => 'Cash payment confirmed.',
                'order_id' => $order->id
            ]);
        } elseif ($paymentMethod === 'online') {
            // Create MoMo payment request
            $orderInfo = [
                'order_id' => $order->id,
                'amount' => $cart['total'],
                'order_info' => "Payment for order #{$order->id}"
            ];

            $response = $this->momoService->createPayment($orderInfo);

            if ($response['success'] && isset($response['pay_url'])) {
                return response()->json([
                    'pay_url' => $response['pay_url']
                ]);
            } else {
                return response()->json([
                    'message' => 'Payment request failed. Please try again.'
                ], 400);
            }
        }

        return response()->json(['message' => 'Invalid payment method.'], 400);
    }

    /**
     * Handle cash payment confirmation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cashConfirmation()
    {
        return response()->json(['message' => 'Cash payment successful!']);
    }

    /**
     * Handle MoMo payment callback
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentCallback(Request $request)
    {
        $response = $this->momoService->paymentExecute($request);

        if ($response['success']) {
            // Clear the cart after successful payment
            $this->cartService->clearCart();
            
            return response()->json([
                'message' => 'Payment successful!',
                'data' => $response
            ]);
        }

        return response()->json([
            'message' => 'Payment failed!',
            'data' => $response
        ], 400);
    }
}