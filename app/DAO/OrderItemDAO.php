<?php

namespace App\DAO;

use App\Models\OrderItem;

class OrderItemDAO
{
    private $model;
    public function __construct(OrderItem $orderItem){
        $this->model = $orderItem;
    }

    public function create(array $products, $order_id){
        foreach ($products as $key => $product) {
            $data = [
                'order_id' => $order_id,
                'product_id' => $product,
                'quantity' => 1, // we are not taking any quantity input from user, so using 1 here
            ];
            $this->model::create($data);
        }
    }
    
}
