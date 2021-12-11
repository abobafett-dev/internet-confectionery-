<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderPageController extends Controller
{
    public function create($order){

        if(!is_numeric($order)){
            abort(404);
        }

        $order = Order::findOrFail($order);

        if($order['id_user'] != Auth::id())
            if(Auth::user()->id_user_status != 2)
                abort(403);

        $order = array(0 => $order->toArray());

        $classUserProfileController = new UserProfileController();

        $order = $classUserProfileController->createOrders($order);

        return view('order')->with(['order'=>$order]);
    }
}
