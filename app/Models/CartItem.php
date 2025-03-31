<?php
// app/Models/CartItem.php
namespace App\Models;

class CartItem
{
    public $id;
    public $product_id;
    public $name;
    public $image;
    public $price;
    public $quantity;

    public function __construct($product_id, $name, $image, $price, $quantity = 1)
    {
        $this->product_id = $product_id;
        $this->name = $name;
        $this->image = $image;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}