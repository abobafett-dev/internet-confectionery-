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
            foreach ($orderInCartProducts as $index => $cookie) {
                if (preg_match("/^orderInCartProducts_[0-9]+$/", $index)) {
                    if (!isset($orderInCart[0]))
                        $orderInCart = array(0 => array('products' => array()));
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

        if (count($orderInCart) > 0) {
            foreach ($orderInCart[0]['products'] as $index => $product) {
                $orderInCart[0]['products'][$index]['product_type'] = Product_Type::find($product['id_product_type'])->toArray();
            }

            return view('cart')->with(['orderInCart' => $orderInCart]);
        } else
            return view('cart');
    }

    public function createIntervalsAjax(Request $request): array
    {
        $orderDate = $request->toArray()['dateForIntervals'];

        $weekDays = array(0 => 'воскресенье', 1 => 'понедельник', 2 => 'вторник',
            3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота');

        $orderDayCode = (int)date('N', strtotime($orderDate));


        $schedule_standard = Schedule_Standard::where('isActive', true)->where('weekday', $weekDays[$orderDayCode])->get()->toArray();
        $count = (int)$schedule_standard[0]['orders_count'];

        if ($count < 1)
            return array();

        $schedule_intervals = Schedule_Interval::where('isActive', true)->get()->toArray();

        foreach ($schedule_intervals as $index => $schedule_interval) {
            if (($schedule_standard[0]['start'] > $schedule_interval['start'] || $schedule_standard[0]['start'] > $schedule_interval['end']) ||
            ($schedule_standard[0]['end'] < $schedule_interval['start'] || $schedule_standard[0]['end'] < $schedule_interval['end'])) {
                unset($schedule_intervals[$index]);
            }
        }

        $schedule_updates = Schedule_Update::where('schedule_will_updated_at', $orderDate)->get()->toArray();

        foreach ($schedule_updates as $schedule_update) {
            if (!$schedule_update['access'])
                $count -= (int)$schedule_update['orders_count_update'];
            else
                $count += (int)$schedule_update['orders_count_update'];

            foreach ($schedule_intervals as $index => $schedule_interval) {
                if ($schedule_interval['id'] == $schedule_update['id_schedule_interval']) {
                    unset($schedule_intervals[$index]);
                }
            }

            if ($count < 1)
                return array();
        }


        $orders = Order::where('will_cooked_at', $orderDate)->get()->toArray();
        foreach ($orders as $order) {
            $order_products = Order_Product::where('id_order', $order['id'])->get()->toArray();
            foreach ($order_products as $order_product) {
                $count -= (int)$order_product['count'];
            }

            foreach ($schedule_intervals as $index => $schedule_interval) {
                if ($schedule_interval['id'] == $order['id_schedule_interval']) {
                    unset($schedule_intervals[$index]);
                }
            }

            if ($count < 1)
                return array();
        }

        function sortIntervalsByDate($firstObj, $secondObj): int
        {
            if ($firstObj['start'] == $secondObj['start'])
                return 0;
            return ($firstObj['start'] < $secondObj['start']) ? -1 : 1;
        }

        usort($schedule_intervals, "App\Http\Controllers\User\sortIntervalsByDate");

        return $schedule_intervals;
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
