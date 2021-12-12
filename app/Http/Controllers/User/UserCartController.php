<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserCartController extends Controller
{
    public function create(){
        if(Auth::user() == null && Cookie::get('orderInCart') != null){
            $orderInCart = Cookie::get('orderInCart');
        }
        elseif(Auth::user() != null){
            $orderInCart = Order::where('id_user',Auth::id())->where('id_status',2)->get()->toArray();

            if(count($orderInCart) > 0) {
                $classUserProfileController = new UserProfileController();

                $orderInCart = $classUserProfileController->createOrders($orderInCart);
            }
        }

        return view('cart')->with(['orderInCart'=>$orderInCart]);
    }
}
