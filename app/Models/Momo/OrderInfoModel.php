<?php
// app/Models/Momo/OrderInfoModel.php
namespace App\Models\Momo;

class OrderInfoModel
{
    public $fullName;
    public $order_id;
    public $amount;
    public $order_info;

    public function __construct(string $fullName, string $orderId, float $amount, string $orderInfo)
    {
        $this->fullName = $fullName;
        $this->order_id = $orderId;
        $this->amount = $amount;
        $this->order_info = $orderInfo;
    }
}