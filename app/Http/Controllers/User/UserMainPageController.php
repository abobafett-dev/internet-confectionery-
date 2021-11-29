<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use App\Models\Product_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserMainPageController extends Controller
{
    public function createProducts(){
        $products = Product::all();
        $product_types = Product_Type::all();
        $products_orders = Order_Product::all();

        $countProductsInOrders = [];
        foreach($products_orders as $product_order){
            if(isset($countProductsInOrders[$product_order]))
                $countProductsInOrders[$product_order] += $product_order->count;
            else
                $countProductsInOrders[$product_order] = $product_order->count;
        }

        $productsWithTypesAndCount = [];
        foreach($products as $product){
            foreach($product_types as $product_type){
                if($product_type->id = $product->id_product_type)
                    $product['product_type'] = $product_type->name;
            }
            if($product->photo != null)
                $product->photo = Storage::url($product->photo) . "?r=" . rand(0,1000);
            $product['count'] = $countProductsInOrders[$product->id];
            $productsWithTypesAndCount[$product['product_type']][count($productsWithTypesAndCount[$product['product_type']])] = $product;
        }

        foreach($productsWithTypesAndCount as $productWithTypesAndCount){
            array_multisort($productWithTypesAndCount['count'], SORT_DESC, SORT_NUMERIC);
        }

        var_dump($productsWithTypesAndCount);

        return view('welcome')->with(['productsWithTypesAndCount'=>$productsWithTypesAndCount]);
    }
}
