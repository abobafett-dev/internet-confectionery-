<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Order_Status;
use App\Models\Product;
use App\Models\Schedule_Interval;
use App\Models\Schedule_Standard;
use App\Models\Source;
use App\Models\User;
use App\Models\User_status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function create()
    {
        $userStatus = User_status::find(Auth::user()->id_user_status);
        $sources = Source::all();
        $user = Auth::user();
        if ($user['avatar'] != null)
            $user['avatar'] = Storage::url($user['avatar']) . "?r=" . rand(0, 1000);
        else
            $user['avatar'] = "storage/logo/standardUserAvatar.png" . "?r=" . rand(0, 1000);
        if ($user['birthday'] != null)
            $user['birthday'] = date("Y-m-d", strtotime($user['birthday']));
        if ($user['id_source'] != null)
            $user['id_source'] = Source::find($user['id_source']);

        $classUserProfileController = new UserProfileController();

        $orders = $classUserProfileController->createOrders(Order::where('id_user', $user['id'])->get()->toArray());

        foreach($orders as $index => $order){
            if($order['id_status'] == 2)
                unset($orders[$index]);
        }

        $ordersToAdmin = $classUserProfileController->createOrders(Order::all()->toArray());

        foreach($ordersToAdmin as $index => $order){
            if($order['id_status'] == 2)
                unset($ordersToAdmin[$index]);
        }

        return view('dashboard')
            ->with(['user' => $user,
                'sources' => $sources,
                'userStatus' => $userStatus,
                'orders' => $orders,
                'ordersToAdmin' => $ordersToAdmin]);
    }

    public function createOrders(array $orders): array
    {
        $orders_products = [];

        foreach ($orders as $index => $order) {
            if($order['id_schedule_interval'] != null)
                $orders[$index]['interval'] = Schedule_Interval::find($order['id_schedule_interval'])->toArray();
            else
                $orders[$index]['interval'] = null;

            if($order['id_schedule_standard'] != null)
                $orders[$index]['schedule_standard'] = Schedule_Standard::find($order['id_schedule_standard'])->toArray();
            else
                $orders[$index]['schedule_standard'] = null;

            $orders[$index]['status'] = Order_Status::find($order['id_status'])->toArray();
            $orders_products[$order['id']] = Order_Product::where('id_order', $order['id'])->get();
        }

        foreach ($orders_products as $order_products) {
            foreach ($order_products as $order_product) {
                foreach ($orders as $index => $order) {
                    if ($order['id'] == $order_product['id_order']) {
                        if (!isset($orders[$index]['products'])) {
                            $orders[$index]['products'] = [];
                            $orders[$index]['products'][$order_product['id_product']] = Product::find($order_product['id_product'])->toArray();
                        } else
                            $orders[$index]['products'][$order_product['id_product']] = Product::find($order_product['id_product'])->toArray();
                        $orders[$index]['products'][$order_product['id_product']]['photo'] =
                            asset(Storage::url($orders[$index]['products'][$order_product['id_product']]['photo']) . "?r=" . rand(0, 1000));
                        break;
                    }
                }
            }
        }

        return $orders;
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['filled', 'string', 'max:255'],
            'phone' => ['filled', 'string', 'regex:/^8\d{10,10}$/'],
            'gender' => ['filled', 'string'],
            'birthday' => ['filled', 'date'],
            'id_source' => ['filled', 'integer'],
            'avatarFile' => ['filled', 'image', 'mimes:jpeg,jpg,png', 'max:5500'],
        ]);

        if (isset($validated['avatarFile'])) {
            $path = Storage::putFileAs('public/userAvatar', $validated['avatarFile'], Auth::user()->id . ".png");
            unset($validated['avatarFile']);
            $validated['avatar'] = $path;
        }

        User::find(Auth::user()->id)->update($validated);

        $updatedUser = User::find(Auth::user()->id);

        if ($updatedUser->bonus == -1
            && $updatedUser->name != null && $updatedUser->gender != null
            && $updatedUser->birthday != null && $updatedUser->phone != null
            && $updatedUser->avatar != null && $updatedUser->id_source != null) {
            User::find(Auth::user()->id)->update(['bonus' => 0]);
        }

        return redirect('dashboard')->with(['was_updated' => 'Изменения прошли успешно!']);
    }

    public function delete()
    {
        $orders = Order::where('id_user',Auth::user()->id)->get()->toArray();
        if(count($orders) > 0){
            foreach($orders as $order){
                if($order['id_status'] == 2){
                    Order_Product::where('id_order',$order['id'])->delete();
                    Order::find($order['id'])->delete();
                }
                else{
                    Order::find($order['id'])->update(['id_user' => 1]);
                }
            }
        }

        User::find(Auth::user()->id)->delete();

        return redirect()->route('main');
    }
}
