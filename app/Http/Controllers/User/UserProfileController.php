<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Order_Status;
use App\Models\Product;
use App\Models\Schedule_Interval;
use App\Models\Schedule_Standard;
use App\Models\User;
use App\Models\User_status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function create(){
        $userStatus = User_status::find(Auth::user()->id_user_status);
        $user = Auth::user();
        if($user['avatar'] != null)
            $user['avatar'] = Storage::url($user['avatar']) . "?r=" . rand(0,1000);
        if($user['birthday'] != null)
            $user['birthday'] = date("Y-m-d", strtotime($user['birthday']));

        $intervals = [];
        $schedule_standards = [];
        $order_statuses = [];
        $order_productes = [];
        $products = [];

        $orders = Order::where(['id_user',$user['id']]);
        foreach($orders as $order){
            $intervals[$order['id']] = Schedule_Interval::find($order['id_schedule_interval']);
            $schedule_standards[$order['id']] = Schedule_Standard::find($order['id_schedule_standard']);
            $order_statuses[$order['id']] = Order_Status::find($order['id_order_status']);
            $order_productes[$order['id']] = Order_Product::where('id_order', $order['id_order']);
        }
        $intervals = array_unique($intervals);
        $schedule_standards = array_unique($schedule_standards);
        $order_statuses = array_unique($order_statuses);
        $order_productes = array_unique($order_productes);

        foreach($order_productes as $order_product){
            $products[$order_product['id_order'] . $order_product['id_product']] = Product::where($order_product['id_product'])->get();
        }
        $products = array_unique($products);

        return view('dashboard')
            ->with(['user'=> $user, 'userStatus'=>$userStatus,
                'orders'=>$orders, 'intervals'=>$intervals,
                'schedule_standards'=>$schedule_standards,
                'order_statuses'=>$order_statuses,
                'order_productes'=>$order_productes,
                'products'=>$products]);
    }

    public function update(Request $request){
        $validated = $request->validate([
            'name' => ['filled', 'string', 'max:255'],
            'phone' => ['filled', 'string', 'regex:/^8\d{10,10}$/'],
            'gender' => ['filled','string'],
            'birthday' => ['filled', 'date'],
            'from' => ['filled', 'string'],
            'avatarFile' => ['filled', 'image','mimes:jpeg,jpg,png','max:5500'],
        ]);

        if(isset($validated['avatarFile'])){
            $path = Storage::putFileAs('public/userAvatar', $validated['avatarFile'], Auth::user()->id . ".png");
            unset($validated['avatarFile']);
            $validated['avatar'] = $path;
        }

        User::find(Auth::user()->id)->update($validated);

        return redirect('dashboard')->with(['was_updated'=>'Изменения прошли успешно!']);
    }

    public function delete(){
        User::find(Auth::user()->id)->delete();

        return redirect()->route('main');
    }
}
