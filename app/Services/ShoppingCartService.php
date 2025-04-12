<?php
// app/Services/ShoppingCartService.php
namespace App\Services;

use App\Helpers\SessionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ShoppingCartService
{
    private const CART_SESSION_KEY = 'cart';

    /**
     * Lấy giỏ hàng hiện tại
     */
    public function getCart(): array
    {
        Log::info('Lấy giỏ hàng từ session');
        
        // Sử dụng SessionHelper để lấy giỏ hàng
        $cart = SessionHelper::getObjectFromJson(self::CART_SESSION_KEY);
        Log::info('Giỏ hàng trong session:', ['cart' => $cart]);
        
        // Trả về giỏ hàng trống nếu không tìm thấy
        if (!$cart) {
            return [
                'items' => [],
                'total' => 0,
            ];
        }
        
        return $cart;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(int $productId, string $name, string $image, float $price, int $quantity = 1): array
    {
        $cart = $this->getCart();
        $items = collect($cart['items']);
        
        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $existingItemIndex = $items->search(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });
        
        if ($existingItemIndex !== false) {
            // Cập nhật số lượng nếu sản phẩm đã tồn tại
            $items[$existingItemIndex]['quantity'] += $quantity;
        } else {
            // Thêm sản phẩm mới vào giỏ hàng
            $items->push([
                'product_id' => $productId,
                'name' => $name,
                'image' => $image,
                'price' => $price,
                'quantity' => $quantity
            ]);
        }
        
        $total = $this->calculateTotal($items);
        
        $cart = [
            'items' => $items->toArray(),
            'total' => $total,
        ];
        
        // Lưu giỏ hàng vào session sử dụng SessionHelper
        SessionHelper::setObjectAsJson(self::CART_SESSION_KEY, $cart);
        Log::info('Đã lưu giỏ hàng vào session', ['cart' => $cart]);
        
        return $cart;
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function removeFromCart(int $productId): array
    {
        $cart = $this->getCart();
        $items = collect($cart['items']);
        
        // Xóa sản phẩm khỏi giỏ hàng
        $items = $items->reject(function ($item) use ($productId) {
            return $item['product_id'] == $productId;
        });
        
        $total = $this->calculateTotal($items);
        
        $cart = [
            'items' => $items->toArray(),
            'total' => $total,
        ];
        
        // Lưu giỏ hàng vào session
        SessionHelper::setObjectAsJson(self::CART_SESSION_KEY, $cart);
        Log::info('Đã cập nhật giỏ hàng trong session sau khi xóa sản phẩm');
        
        return $cart;
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function updateQuantity(int $productId, int $quantity): array
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($productId);
        }
        
        $cart = $this->getCart();
        $items = collect($cart['items']);
        
        // Cập nhật số lượng sản phẩm
        $items = $items->map(function ($item) use ($productId, $quantity) {
            if ($item['product_id'] == $productId) {
                $item['quantity'] = $quantity;
            }
            return $item;
        });
        
        $total = $this->calculateTotal($items);
        
        $cart = [
            'items' => $items->toArray(),
            'total' => $total,
        ];
        
        // Lưu giỏ hàng vào session
        SessionHelper::setObjectAsJson(self::CART_SESSION_KEY, $cart);
        Log::info('Đã cập nhật số lượng sản phẩm trong giỏ hàng');
        
        return $cart;
    }

    /**
     * Xóa giỏ hàng
     */
    public function clearCart(): void
    {
        // Xóa giỏ hàng khỏi session
        SessionHelper::setObjectAsJson(self::CART_SESSION_KEY, null);
        Log::info('Đã xóa giỏ hàng');
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