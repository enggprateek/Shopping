<?php

namespace App\Http\Controllers;

use App\DAO\OrderDAO;
use App\DAO\OrderItemDAO;
use App\DAO\ProductDAO;
use App\Events\OrderPlaced;
use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ipbase\Ipbase\IpbaseClient;

class ProductController extends Controller
{
    protected $dao;
    protected $orderDao;
    protected $orderItemDao;
    public function __construct(ProductDAO $productDao, OrderDAO $orderDAO, OrderItemDAO $orderItemDAO){
        $this->dao = $productDao;
        $this->orderDao = $orderDAO;
        $this->orderItemDao = $orderItemDAO;
    }

    public function index() {
        $products = [];
        try {
            
            $products = $this->dao->getProducts();
            
            return view("products.index",compact('products'));
        } catch (\Throwable $th) {
            // Log error using Logger Framework
            return view("products.index",compact('products'));
        }
    }

    public function order(OrderRequest $request) {

        try {
            $ipBase = new IpbaseClient(env('ipbase_key'));
            $ip = $request->ip();
            $ipInfo = $ipBase->info([
                'ip' => $ip,
                'language' => 'en',
            ]);
            try {
                if ($ipInfo['data']['location']['country']['name'] == "Somalia") {
                    return redirect('/')->with('error', 'Failed to place order. Please try again.');
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
            $data = $request->all();
            DB::beginTransaction();
            $order = $this->orderDao->create($data);
            $product = isset($data['product']) ? $data['product'] : [];
            $this->orderItemDao->create($product,$order->id);
            DB::commit();
            event(new OrderPlaced($order));
            return redirect('/')->with('success', 'Order placed successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            // Log error using Logger Framework
            return redirect('/')->with('error', 'Failed to place order. Please try again.');
        }

    }
}
