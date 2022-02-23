<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order_Product;
use App\Models\Order_Status;
use App\Models\Product;
use App\Models\Product_Type;
use App\Models\Schedule_Interval;
use App\Models\Schedule_Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFunctionsController extends Controller
{
    public function createOrders(array $orders): array
    {
        $orders_products = [];

        foreach ($orders as $index => $order) {
            if ($order['id_schedule_interval'] != null)
                $orders[$index]['interval'] = Schedule_Interval::find($order['id_schedule_interval'])->toArray();
            else
                $orders[$index]['interval'] = null;

            if ($order['id_schedule_standard'] != null)
                $orders[$index]['schedule_standard'] = Schedule_Standard::find($order['id_schedule_standard'])->toArray();
            else
                $orders[$index]['schedule_standard'] = null;

            $orders[$index]['status'] = Order_Status::find($order['id_status'])->toArray();
            $orders_products[$order['id']] = Order_Product::where('id_order', $order['id'])->get();
        }


        foreach ($orders_products as $order_products) {
            $countProducts = 0;
            foreach ($order_products as $order_product) {
                foreach ($orders as $index => $order) {
                    if ($order['id'] == $order_product['id_order']) {
                        if (!isset($orders[$index]['products'])) {
                            $orders[$index]['products'] = [];
                            $orders[$index]['products'][$order_product['id_product']] = Product::find($order_product['id_product'])->toArray();
                            $orders[$index]['products'][$order_product['id_product']]['data'] = $order_product->toArray();
                            $orders[$index]['products'][$order_product['id_product']]['product_type'] = Product_Type::find($orders[$index]['products'][$order_product['id_product']]['id_product_type'])->toArray();
                            $countProducts += $orders[$index]['products'][$order_product['id_product']]['data']['count'];
                        } else {
                            $orders[$index]['products'][$order_product['id_product']] = Product::find($order_product['id_product'])->toArray();
                            $orders[$index]['products'][$order_product['id_product']]['data'] = $order_product->toArray();
                            $orders[$index]['products'][$order_product['id_product']]['product_type'] = Product_Type::find($orders[$index]['products'][$order_product['id_product']]['id_product_type'])->toArray();
                            $countProducts += $orders[$index]['products'][$order_product['id_product']]['data']['count'];
                        }
                        $orders[$index]['products'][$order_product['id_product']]['photo'] =
                            asset(Storage::url($orders[$index]['products'][$order_product['id_product']]['photo']) . "?r=" . rand(0, 1000));
                        $orders[$index]['countProducts'] = $countProducts;
                        break;
                    }
                }
            }
        }

        return $orders;
    }
}
