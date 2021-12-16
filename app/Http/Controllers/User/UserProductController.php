<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use App\Models\Product_Component;
use App\Models\Product_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserProductController extends Controller
{
    public function deleteProductInCart($productId, Request $request){
        //var_dump($productId, $request->toArray());
        if(Auth::user() != null){
            $request = $request->toArray();
            $order_product_order = Order_Product::where('id_order', $request['order']);
            if(count($order_product_order->get()->toArray()) == 1){
                $id_order = $order_product_order->get()->toArray()[0]['id_order'];
                $order_product_order->delete();
                Order::find($id_order)->delete();
            }
            $order_product_order->where('id_product',$productId)->delete();
            return redirect('cart');
        } else{
            Cookie::queue(Cookie::forget('orderInCartProducts_'.$productId));
            return redirect('cart');
        }
    }

    public function deleteProductInCartAjax($productId, Request $request){
        //var_dump($productId, $request->toArray());
        if(Auth::user() != null){
            $request = $request->toArray();
            $order_product_order = Order_Product::where('id_order', $request['order']);
            if(count($order_product_order->get()->toArray()) == 1){
                $id_order = $order_product_order->get()->toArray()[0]['id_order'];
                $order_product_order->delete();
                Order::find($id_order)->delete();
            }
            $order_product_order->where('id_product',$productId)->delete();
            return "ok";
        } else{
            Cookie::queue(Cookie::forget('orderInCartProducts_'.$productId));
            return "ok";
        }
    }

    public function addProductInCart($productId, Request $request)
    {
        if (Auth::user() == null) {

            $cookie = cookie('orderInCartProducts_' . $productId, $productId, 2680000);
            return redirect($request->server()['HTTP_REFERER'])->cookie($cookie);

        } elseif (Auth::user() != null) {
            $UserProductController = new UserProductController();
            $UserProductController->checkAndAddProductInChart($productId);
        }
        return redirect($request->server()['HTTP_REFERER']);
    }

    private function checkAndAddProductInChart($productId)
    {
        $orderInCart = Order::where('id_user', Auth::id())->where('id_status', 2)->get()->toArray();
        if ($orderInCart == null) {
            $orderId = Order::insertGetId(['id_user' => Auth::id(), 'id_status' => 2]);
            Order_Product::insert(['id_order' => $orderId, 'id_product' => $productId, 'count' => 1]);
        } else {
            $order_product = Order_Product::where('id_order', $orderInCart[0]['id'])->where('id_product', $productId);
            if (count($order_product->get()->toArray()) == 0) {
                Order_Product::insert(['id_order' => $orderInCart[0]['id'], 'id_product' => $productId, 'count' => 1]);
            }
        }
    }

    public function addProductFromConstructor(Request $request)
    {
        $request_copy = $request->toArray();
        $products_components = array();
        $price = 0;
        $components = array();
        $product_type = Product_Type::where('name', $request_copy['product_type'])->get()->toArray();

        foreach ($request_copy as $constructor => $component) {
            if (preg_match("/^constructor_[0-9]+$/", $constructor)) {
                $request_copy[$constructor] = (int)$component;
                $products_components[count($products_components)] = Product_Component::where('id_component', $request_copy[$constructor])->get()->toArray();
                $components[count($components)] = Component::find((int)$component)->toArray();
                $componentForPrice = Component::find((int)$component)->toArray();
                $price += (double)$componentForPrice['price'] * (double)$componentForPrice['coefficient'] * (double)$product_type[0]['weight_initial'];
            }
        }

        foreach ($request_copy as $constructor => $component) {
            if (preg_match("/^constructor_[0-9]+$/", $constructor)) {
                $componentDecor = Component::find((int)$component)->toArray();
                if ($componentDecor['id_component_type'] == 2) {
                    break;
                }
            }
        }

        $products = array();
        foreach ($products_components as $product_components) {
            $products[count($products)] = array();
            foreach ($product_components as $product_component) {
                $products[count($products) - 1][count($products[count($products) - 1])] = $product_component['id_product'];
            }
        }

        $existProducts = array();
        for ($i = 0; $i < count($products) - 1; $i++) {
            $existProducts = array_intersect($products[$i], $products[$i + 1]);
        }

        $productFromConstructor = null;
        foreach ($existProducts as $index => $existProduct) {
            $existProducts[$index] = Product::find($existProduct)->toArray();
            if ($existProducts[$index]['name'] == 'Конструктор') {
                $productFromConstructor = $existProducts[$index];
                break;
            }
        }

        if (Auth::user() == null && $productFromConstructor != null) {
            $cookie = cookie('orderInCartProducts_' . $productFromConstructor['id'], $productFromConstructor['id'], 2680000);
            return redirect($request->server()['HTTP_REFERER'])->cookie($cookie);

        } elseif (Auth::user() == null && $productFromConstructor == null) {
            $productId = Product::insertGetId(['id_product_type' => $product_type[0]['id'], 'name' => 'Конструктор',
                'photo' => $componentDecor['photo'], 'isActive' => true, 'bonus_coefficient' => 1, 'price' => $price]);
            foreach($components as $component){
                Product_Component::insert(['id_product'=>$productId, 'id_component'=>$component['id']]);
            }
            $cookie = cookie('orderInCartProducts_' . $productId, $productId, 2680000);
            return redirect($request->server()['HTTP_REFERER'])->cookie($cookie);

        } elseif (Auth::user() != null && $productFromConstructor != null) {

            $UserProductController = new UserProductController();
            $UserProductController->checkAndAddProductInChart($productFromConstructor['id']);

            return redirect($request->server()['HTTP_REFERER']);

        } elseif (Auth::user() != null && $productFromConstructor == null) {
            $productId = Product::insertGetId(['id_product_type' => $product_type[0]['id'], 'name' => 'Конструктор',
                'photo' => $componentDecor['photo'], 'isActive' => true, 'bonus_coefficient' => 1, 'price' => $price]);
            foreach($components as $component){
                Product_Component::insert(['id_product'=>$productId, 'id_component'=>$component['id']]);
            }

            $UserProductController = new UserProductController();
            $UserProductController->checkAndAddProductInChart($productId);

            return redirect($request->server()['HTTP_REFERER']);
        }
    }
}
