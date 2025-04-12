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
     */
    public function getCart()
    {
        $cart = $this->cartService->getCart();

        return response()->json($cart);
    }

    /**
     * Add a product to the cart
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
     */
    public function updateQuantity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'replace' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $replace = $request->input('replace', false);

        $currentCart = $this->cartService->getCart();
        $items = collect($currentCart['items']);

        $existingItem = $items->firstWhere(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });

        if ($existingItem && !$replace) {
            $newQuantity = $existingItem['quantity'] + $quantity;

            $cart = $this->cartService->updateQuantity($productId, $newQuantity);

            return response()->json([
                'message' => 'Product quantity increased successfully!',
                'cart' => $cart
            ]);
        } else {
            $cart = $this->cartService->updateQuantity($productId, $quantity);

            return response()->json([
                'message' => 'Product quantity updated successfully!',
                'cart' => $cart
            ]);
        }
    }

    /**
     * Process the checkout
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

        if ($request->payment_method === 'online') {
            $orderId = uniqid();
            session(['temp_order_id' => $orderId, 'shipping_address' => $request->shipping_address, 'notes' => $request->notes]);

            $response = $this->momoService->createPayment([
                'order_id' => $orderId,
                'amount' => $cart['total'],
                'order_info' => 'Thanh toán đơn hàng online'
            ]);

            if (!$response['success'] || !isset($response['pay_url'])) {
                return response()->json(['message' => 'Payment request failed. Please try again.'], 400);
            }

            return response()->json(['pay_url' => $response['pay_url']]);
        }
        if ($request->payment_method === 'cash') {
            // Tạo đơn hàng ngay lập tức khi thanh toán bằng tiền mặt
            $order = new Order();
            $order->user_id = Auth::id();
            $order->order_date = now();
            $order->total_price = $cart['total'];
            $order->shipping_address = $request->input('shipping_address');
            $order->notes = $request->input('notes');
            $order->payment_method = 'cash';
            $order->status = 'paid'; // Đặt trạng thái đơn hàng là 'paid'
            $order->save();
        
            foreach ($cart['items'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        
            $this->cartService->clearCart();
        
            return response()->json([
                'message' => 'Cash payment confirmed.',
                'order_id' => $order->id
            ]);
        }
        
    }


    /**
     * Handle cash payment confirmation
     */
    public function cashConfirmation()
    {
        return response()->json(['message' => 'Cash payment successful!']);
    }

    /**
     * Handle MoMo payment callback
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
