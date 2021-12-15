<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Schedule_Interval;
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
        else {
            $orderInCart = array();
        }

        $days = array(0 => 'воскресенье', 1 => 'понедельник', 2 => 'вторник',
            3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота');



        return view('cart')->with(['orderInCart'=>$orderInCart]);
    }
}
