<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserProductController extends Controller
{
    public function addProductInCart($productId, Request $request)
    {
        if (Auth::user() == null) {

            $cookie = cookie('orderInCartProducts_' . $productId, $productId, 2680000);
            return redirect($request->server()['HTTP_REFERER'])->cookie($cookie);

        } elseif (Auth::user() != null) {

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
        return redirect($request->server()['HTTP_REFERER']);
    }
}
