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
    public function createProducts()
    {
        $products = Product::all();
        $product_types = Product_Type::all();
        $products_orders = Order_Product::all();

        $countProductsInOrders = [];
        foreach ($products_orders as $product_order) {
            if (isset($countProductsInOrders[$product_order->id_product]))
                $countProductsInOrders[$product_order->id_product] += $product_order->count;
            else
                $countProductsInOrders[$product_order->id_product] = $product_order->count;
        }

        $productsWithTypesAndCount = [];
        foreach ($products as $product) {
            foreach ($product_types as $product_type) {
                if ($product_type->id == $product->id_product_type)
                    $product->product_type = $product_type->name;
            }
            if ($product->photo != null)
                $product->photo = Storage::url($product->photo) . "?r=" . rand(0, 1000);
            $product['count'] = $countProductsInOrders[$product->id];
            if (isset($productsWithTypesAndCount[$product['product_type']]))
                $productsWithTypesAndCount[$product['product_type']][count($productsWithTypesAndCount[$product['product_type']])] = $product;
            else {
                $productsWithTypesAndCount[$product['product_type']] = [];
                $productsWithTypesAndCount[$product['product_type']][count($productsWithTypesAndCount[$product['product_type']])] = $product;
            }

        }

        function sortByCount($firstObj, $secondObj): int
        {
            if ($firstObj['count'] == $secondObj['count'])
                return 0;
            return ($firstObj['count'] < $secondObj['count']) ? 1 : -1;
        }

        foreach ($productsWithTypesAndCount as $productWithTypesAndCount) {
            usort($productWithTypesAndCount, "App\Http\Controllers\user\sortByCount");
        }

        return view('welcome')->with(['productsWithTypesAndCount' => $productsWithTypesAndCount]);

        return view('welcome');
    }
}
