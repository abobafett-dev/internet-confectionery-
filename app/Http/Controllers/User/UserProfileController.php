<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Order_Status;
use App\Models\Product;
use App\Models\Product_Type;
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
use function PHPUnit\Framework\isEmpty;

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

        foreach ($orders as $index => $order) {
            if ($order['id_status'] == 2)
                unset($orders[$index]);
        }

        return view('dashboard')
            ->with(['user' => $user,
                'sources' => $sources,
                'userStatus' => $userStatus,
                'orders' => $orders]);
    }

    public function createOrders(array $orders): array
    {
        $orders_products = [];

        $intervals = ["sql"=>[],"array"=>[]];

        $schedule_standards = ["sql"=>[],"array"=>[]];

        $order_statuses = ["sql"=>Order_Status::all()->toArray(),"array"=>[]];
        foreach ($order_statuses['sql'] as $order_status){
            $order_statuses["array"][$order_status["id"]] = $order_status;
        }

        $product_types = ["sql"=>Product_Type::all()->toArray(),"array"=>[]];
        foreach ($product_types['sql'] as $product_type){
            $product_types["array"][$product_type['id']] = $product_type;
        }

        foreach ($orders as $index => $order) {
            if ($order['id_schedule_interval'] != null) {
                if (empty($intervals['sql'])){
                    $intervals['sql'] = Schedule_Interval::all()->toArray();
                    foreach ($intervals['sql'] as $interval){
                        $intervals['array'][$interval['id']] = $interval;
                    }
                }
                    $orders[$index]['interval'] = $intervals['array'][$order['id_schedule_interval']];
            }
            else
                $orders[$index]['interval'] = null;

            if ($order['id_schedule_standard'] != null){
                if (empty($schedule_standards['sql'])){
                    $schedule_standards['sql'] = Schedule_Standard::all()->toArray();
                    foreach ($schedule_standards['sql'] as $schedule_standard){
                        $schedule_standards['array'][$schedule_standard['id']] = $schedule_standard;
                    }
                }
                $orders[$index]['schedule_standard'] = $schedule_standards['array'][$order['id_schedule_standard']];
            }
            else
                $orders[$index]['schedule_standard'] = null;

            $orders[$index]['status'] = $order_statuses["array"][$order['id_status']];
            $orders_products[$order['id']] = Order_Product::where('id_order', $order['id'])->get();
        }

        foreach ($orders_products as $order_products) {
            foreach ($order_products as $order_product) {
                foreach ($orders as $index => $order) {
                    if ($order['id'] == $order_product['id_order']) {
                        if (!isset($orders[$index]['products']))
                            $orders[$index]['products'] = [];
                        $orders[$index]['products'][$order_product['id_product']] = Product::find($order_product['id_product'])->toArray();
                        $orders[$index]['products'][$order_product['id_product']]['data'] = $order_product->toArray();
                        $orders[$index]['products'][$order_product['id_product']]['product_type'] = $product_types['array'][$orders[$index]['products'][$order_product['id_product']]['id_product_type']];

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
        $orders = Order::where('id_user', Auth::user()->id)->get()->toArray();
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                if ($order['id_status'] == 2) {
                    Order_Product::where('id_order', $order['id'])->delete();
                    Order::find($order['id'])->delete();
                } else {
                    Order::find($order['id'])->update(['id_user' => 1]);
                }
            }
        }

        User::find(Auth::user()->id)->delete();

        return redirect()->route('main');
    }
}
