<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Order_Status;
use App\Models\Product;
use App\Models\Product_Type;
use App\Models\Schedule_Interval;
use App\Models\Schedule_Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminOrdersPageController extends Controller
{
    function create(String $date)
    {
        if (Auth::user()->id_user_status != 2)
                abort(403);

        $currentDay = date('Y-m-d');

        $orders = Order::where('will_cooked_at', $currentDay)->get()->toArray();

        $AdminFunctionsController = new AdminFunctionsController();

        $orders = $AdminFunctionsController->createOrders($orders);

        return view('adminOrders')->with(['data'=>$orders]);
    }

}
