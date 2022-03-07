<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Component_Type;
use App\Models\Ingredient;
use App\Models\Ingredient_Component;
use App\Models\Order_Product;
use App\Models\Order_Status;
use App\Models\Product;
use App\Models\Product_Component;
use App\Models\Product_Type;
use App\Models\Schedule_Interval;
use App\Models\Schedule_Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFunctionsController extends Controller
{
    public function createOrders(array $orders): array
    {
        $ingredientsAndOrdersForDay = ['ingregients'=>[], 'orders'=>$orders];
        $orders_products = [];

        foreach ($ingredientsAndOrdersForDay['orders'] as $index => $order) {
            if ($order['id_schedule_interval'] != null)
                $ingredientsAndOrdersForDay['orders'][$index]['interval'] = Schedule_Interval::find($order['id_schedule_interval'])->toArray();
            else
                $ingredientsAndOrdersForDay['orders'][$index]['interval'] = null;

            if ($order['id_schedule_standard'] != null)
                $ingredientsAndOrdersForDay['orders'][$index]['schedule_standard'] = Schedule_Standard::find($order['id_schedule_standard'])->toArray();
            else
                $ingredientsAndOrdersForDay['orders'][$index]['schedule_standard'] = null;

            $ingredientsAndOrdersForDay['orders'][$index]['status'] = Order_Status::find($order['id_status'])->toArray();
            $orders_products[$order['id']] = Order_Product::where('id_order', $order['id'])->get();
        }


        foreach ($orders_products as $order_products) {
            $countProducts = 0;
            foreach ($order_products as $order_product) {
                foreach ($ingredientsAndOrdersForDay['orders'] as $index => $order) {
                    if ($order['id'] == $order_product['id_order']) {
                        if (!isset($ingredientsAndOrdersForDay['orders'][$index]['products']))
                            $ingredientsAndOrdersForDay['orders'][$index]['products'] = [];

                        $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']] = Product::find($order_product['id_product'])->toArray();
                        $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['data'] = $order_product->toArray();
                        $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['product_type'] = Product_Type::find($ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['id_product_type'])->toArray();
                        $countProducts += $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['data']['count'];

                        $product_components = Product_Component::where('id_product', $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['id'])->get()->toArray();

                        $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['components'] = [];
                        foreach ($product_components as $product_component) {

                            $component = Component::find($product_component['id_component'])->toArray();
                            $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['components'][$component['id']] = $component;
                            $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['components'][$component['id']]['component_type'] = Component_Type::find($component['id_component_type'])->toArray();

                            $component_ingredients = Ingredient_Component::where('id_component', $component['id'])->get()->toArray();

                            foreach($component_ingredients as $component_ingredient){
                                $ingredient = Ingredient::find($component_ingredient['id_ingredient'])->toArray();
                                if($ingredient == null)
                                    continue;
                                if(!isset($ingredientsAndOrdersForDay['ingregients'][$ingredient['name']]))
                                    $ingredientsAndOrdersForDay['ingregients'][$ingredient['name']] = 0;

                                $ingredientsAndOrdersForDay['ingregients'][$ingredient['name']] += $order_product['count'] * $order_product['weight'] * $component['coefficient'] * $component_ingredient['weight'];

                            }
                        }
                        $ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['photo'] =
                            asset(Storage::url($ingredientsAndOrdersForDay['orders'][$index]['products'][$order_product['id_product']]['photo']) . "?r=" . rand(0, 1000));
                        $ingredientsAndOrdersForDay['orders'][$index]['countProducts'] = $countProducts;
                        break;
                    }
                }
            }
        }



        function sortByIntervalReg($firstObj, $secondObj): int
        {
            if ($firstObj['interval']['start'] == $secondObj['interval']['start'] && $firstObj['id_status'] == $secondObj['id_status']) {
                return 0;
            } elseif ($firstObj['id_status'] == $secondObj['id_status'] && $firstObj['interval']['start'] < $secondObj['interval']['start']) {
                return -1;
            } elseif ($firstObj['id_status'] == $secondObj['id_status'] && $firstObj['interval']['start'] > $secondObj['interval']['start']) {
                return 1;
            } elseif ($firstObj['id_status'] > 5 && $firstObj['interval']['start'] < $secondObj['interval']['start']) {
                return -1;
            } elseif ($firstObj['id_status'] < 5 && $firstObj['interval']['start'] < $secondObj['interval']['start']) {
                return 1;
            } elseif ($firstObj['id_status'] < 5 && $firstObj['interval']['start'] == $secondObj['interval']['start']) {
                return -1;
            } elseif ($firstObj['id_status'] > 5 && $firstObj['interval']['start'] == $secondObj['interval']['start']) {
                return 1;
            }
            return 0;
        }

        usort($ingredientsAndOrdersForDay['orders'], "App\Http\Controllers\Admin\sortByIntervalReg");

        return $ingredientsAndOrdersForDay;
    }
}
