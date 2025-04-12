<?php
// app/Services/ShoppingCartService.php
namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class ShoppingCartService
{

    /**
     * Lấy giỏ hàng hiện tại
     */
    public function getCart(): array
    {
        $userId = Auth::id();
        Log::info("Lấy giỏ hàng từ DB cho user_id: {$userId}");

        $items = CartItem::where('user_id', $userId)->with('product')->get();

        $cartItems = $items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product->name,
                'image' => $item->product->image_url,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
            ];
        });

        $total = $cartItems->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return [
            'items' => $cartItems,
            'total' => $total,
        ];
    }
    

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(int $productId, string $name, string $image, float $price, int $quantity = 1): array
    {
        $userId = Auth::id();

        $item = CartItem::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return $this->getCart();
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart(int $productId): array
    {
        $userId = Auth::id();

        CartItem::where('user_id', $userId)->where('product_id', $productId)->delete();

        return $this->getCart();
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateQuantity(int $productId, int $quantity): array
    {
        $userId = Auth::id();

        if ($quantity <= 0) {
            return $this->removeFromCart($productId);
        }

        $item = CartItem::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($item) {
            $item->quantity = $quantity;
            $item->save();
        }

        return $this->getCart();
    }

    /**
     * Xóa giỏ hàng
     */
    public function clearCart(): void
    {
        $userId = Auth::id();
        CartItem::where('user_id', $userId)->delete();
    }

    /**
     * Tính tổng giá trị giỏ hàng
     */
    private function calculateTotal(Collection $items): float
    {
        return $items->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }
}