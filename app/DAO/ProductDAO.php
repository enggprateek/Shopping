<?php

namespace App\DAO;

use App\Models\Product;

class ProductDAO
{
    private $model;
    public function __construct(Product $product){
        $this->model = $product;
    }

    public function getProducts(){
        return $this->model::all();
    }
    
}
