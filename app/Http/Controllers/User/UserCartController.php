<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use App\Models\Product_Type;
use App\Models\Schedule_Interval;
use App\Models\Schedule_Standard;
use App\Models\Schedule_Update;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class UserCartController extends Controller
{
    public function create()
    {
        if (Auth::user() == null) {
            $orderInCartProducts = Cookie::get();
            $orderInCart = array();
            foreach($orderInCartProducts as $index=>$cookie){
                if(preg_match("/^orderInCartProducts_[0-9]+$/",$index)){
                    if(!isset($orderInCart[0]))
                        $orderInCart = array(0=>array('products'=>array()));
                    $productId = explode("_", $index);
                    $orderInCart[0]['products'][(int)($productId[1])] = Product::find((int)($productId[1]))->toArray();
                    $orderInCart[0]['products'][(int)($productId[1])]['photo'] =
                        asset(Storage::url($orderInCart[0]['products'][(int)($productId[1])]['photo']) . "?r=" . rand(0, 1000));
                }
            }
        } elseif (Auth::user() != null) {
            $orderInCart = Order::where('id_user', Auth::id())->where('id_status', 2)->get()->toArray();

            if (count($orderInCart) > 0) {
                $classUserProfileController = new UserProfileController();

                $orderInCart = $classUserProfileController->createOrders($orderInCart);
            }
        } else {
            $orderInCart = array();
        }

//        $days = array(0 => 'воскресенье', 1 => 'понедельник', 2 => 'вторник',
//            3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота');
        if (count($orderInCart) > 0) {

            foreach ($orderInCart[0]['products'] as $index => $product) {
                $orderInCart[0]['products'][$index]['product_type'] = Product_Type::find($product['id_product_type'])->toArray();
            }

            date_default_timezone_set('Etc/GMT-5');

            $timeNowPlus24h = date('H:i',
                mktime(date('H') + 24, date('i'), 0,
                    date('m'), date('d'), date('Y')));

            $dateNowPlus24h = date('Y-m-d',
                mktime(date('H') + 24, date('i'), 0,
                    date('m'), date('d'), date('Y')));

            $dateNowPlus48h = date('Y-m-d',
                mktime(0, 0, 0,
                    date('m'), date('d') + 2, date('Y')));

            $schedule_interval = Schedule_Interval::where('isActive', true)->get()->toArray();
            $schedule_standard = Schedule_Standard::where('isActive', true)->get()->toArray();
            $schedule_updates_24 = Schedule_Update::where('schedule_will_updated_at', '=', $dateNowPlus24h)->where('start', '>=', $timeNowPlus24h)->get()->toArray();
            $schedule_updates_48 = Schedule_Update::where('schedule_will_updated_at', '>=', $dateNowPlus48h)->get()->toArray();
            $orders = Order::where('will_cooked_at', '>=', $dateNowPlus24h)->get()->toArray();
            $orders_all = array();

            foreach ($orders as $order) {
                $orders_products = Order_Product::where('id_order', $order['id'])->get()->toArray();
                if (isset($orders_all[$order['will_cooked_at']])) {
                    foreach ($orders_products as $order_products) {
                        $orders_all[$order['will_cooked_at']]['count'] += $order_products['count'];
                    }
                } else {
                    $orders_all[$order['will_cooked_at']] = array('count' => 0);
                    foreach ($orders_products as $order_products) {
                        $orders_all[$order['will_cooked_at']]['count'] += $order_products['count'];
                    }
                }
            }

            $schedule_update_all = array();

            $UserCartController = new UserCartController();


            $schedule_update_all = $UserCartController->makeArrayUpdates($schedule_updates_24, $schedule_update_all);
            $schedule_update_all = $UserCartController->makeArrayUpdates($schedule_updates_48, $schedule_update_all);

            return view('cart')->with(['orderInCart' => $orderInCart, 'schedule_interval' => $schedule_interval,
                'schedule_standard' => $schedule_standard, 'schedule_update_all' => $schedule_update_all, 'orders_all' => $orders_all]);
        }
        else
            return view('cart');
    }

    private function makeArrayUpdates($schedule_updates, $schedule_update_all): array
    {
        foreach ($schedule_updates as $schedule_update) {
            if (isset($schedule_update_all[$schedule_update['schedule_will_updated_at']])) {
                $schedule_update_all[$schedule_update['schedule_will_updated_at']][count($schedule_update_all[$schedule_update['schedule_will_updated_at']])] = $schedule_update;

                if ($schedule_update['start'] !=
                    $schedule_update_all[$schedule_update['schedule_will_updated_at']][count($schedule_update_all[$schedule_update['schedule_will_updated_at']]) - 1]['start']
                    || $schedule_update['end'] !=
                    $schedule_update_all[$schedule_update['schedule_will_updated_at']][count($schedule_update_all[$schedule_update['schedule_will_updated_at']]) - 1]['end']) {
                    if ($schedule_update['access'] == false)
                        $schedule_update_all[$schedule_update['schedule_will_updated_at']]['count'] += -1 * $schedule_update['orders_count_update'];
                    else
                        $schedule_update_all[$schedule_update['schedule_will_updated_at']]['count'] += $schedule_update['orders_count_update'];
                }
            } elseif ($schedule_update['access'] == false)
                $schedule_update_all[$schedule_update['schedule_will_updated_at']] = array('count' => -1 * $schedule_update['orders_count_update'], 1 => $schedule_update);
            elseif ($schedule_update['access'] == true)
                $schedule_update_all[$schedule_update['schedule_will_updated_at']] = array('count' => $schedule_update['orders_count_update'], 1 => $schedule_update);
        }
        return $schedule_update_all;
    }
}
