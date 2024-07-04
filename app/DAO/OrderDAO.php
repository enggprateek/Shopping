<?php

namespace App\DAO;

use App\Models\Order;

class OrderDAO
{
    private $model;
    public function __construct(Order $order){
        $this->model = $order;
    }

    public function create(array $data){
        return $this->model::create($data);
    }
    
}
