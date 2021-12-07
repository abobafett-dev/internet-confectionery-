<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Component_Type;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use App\Models\Product_Type;
use App\Models\Product_Type_Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserMainPageController extends Controller
{
    public function createProducts()
    {
        $products = Product::where('isActive', true)->where('name', '<>', 'Конструктор')->get();
        $product_types = Product_Type::all()->toArray();
        $products_orders = Order_Product::all();

        $components = Component::all()->toArray();
        $component_types = Component_Type::all()->toArray();
        $product_types_components = Product_Type_Component::all();

        $product_types_ids = array_column($product_types, 'id');
        $components_ids = array_column($components, 'id');
        $component_types_ids = array_column($component_types, 'id');

        $countProductsInOrders = [];
        foreach ($products_orders as $product_order) {
            if (isset($countProductsInOrders[$product_order->id_product]))
                $countProductsInOrders[$product_order->id_product] += $product_order->count;
            else
                $countProductsInOrders[$product_order->id_product] = $product_order->count;
        }

        $productsWithTypesAndCount = [];
        foreach ($products as $product) {
            $product->product_type = $product_types[array_search($product->id_product_type, $product_types_ids)]['name'];
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

        function sortByCountDesc($firstObj, $secondObj): int
        {
            if ($firstObj['count'] == $secondObj['count'])
                return 0;
            return ($firstObj['count'] < $secondObj['count']) ? 1 : -1;
        }

        foreach ($productsWithTypesAndCount as $productWithTypesAndCount) {
            usort($productWithTypesAndCount, "App\Http\Controllers\User\sortByCountDesc");
        }

        $componentsWithProductTypesForConstructor = [];
        foreach ($product_types_components as $product_type_component) {
            $component = $components[array_search($product_type_component->id_component, $components_ids)];
            if(!$component['isActive'])
                continue;

            $component_type = $component_types[array_search($component['id_component_type'], $component_types_ids)];
            $product_type = $product_types[array_search($product_type_component->id_product_type, $product_types_ids)];

            if ($component['photo'] != null)
                $component['photo'] = asset(Storage::url($component['photo']) . "?r=" . rand(0, 1000));

            if (!$product_type['isConstructor']) {
                continue;
            }

            if (isset($componentsWithProductTypesForConstructor[$product_type['name']]) &&
                !isset($componentsWithProductTypesForConstructor[$product_type['name']][$component_type['name']])) {
                $componentsWithProductTypesForConstructor[$product_type['name']][$component_type['name']] = [];
            } elseif(!isset($componentsWithProductTypesForConstructor[$product_type['name']]) &&
                !isset($componentsWithProductTypesForConstructor[$product_type['name']][$component_type['name']])) {
                $componentsWithProductTypesForConstructor[$product_type['name']] = [];
                $componentsWithProductTypesForConstructor[$product_type['name']][$component_type['name']] = [];

                $componentsWithProductTypesForConstructor[$product_type['name']][0] =
                    array('weight_min' => $product_type['weight_min'],
                        'weight_initial' => $product_type['weight_initial'],
                        'weight_max' => $product_type['weight_max']);
            }

            $componentsWithProductTypesForConstructor[$product_type['name']][$component_type['name']]
            [count($componentsWithProductTypesForConstructor[$product_type['name']][$component_type['name']])] = $component;

        }

        return view('welcome')->with(['productsWithTypesAndCount' => $productsWithTypesAndCount,
            'componentsWithProductTypesForConstructor' => $componentsWithProductTypesForConstructor]);
    }
}
