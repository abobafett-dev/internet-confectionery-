<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_Product;
use App\Models\Product;
use App\Models\Product_Type;
use App\Models\Schedule_Interval;
use App\Models\Schedule_Standard;
use App\Models\Schedule_Update;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

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

        $weekDays = array(7 => 'воскресенье', 1 => 'понедельник', 2 => 'вторник',
            3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота');

        $orderDayCode = (int)date('N', strtotime($orderDate));


        $schedule_standard = Schedule_Standard::where('isActive', true)->where('weekday', $weekDays[$orderDayCode])->get()->toArray();
        $count = (int)$schedule_standard[0]['orders_count'];

        if ($count < 1)
            return array();

        $schedule_intervals = array();
        foreach (Schedule_Interval::where('isActive', true)->get()->toArray() as $interval) {
            $schedule_intervals[$interval['id']] = $interval;
        }


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

        $schedule_intervals[count($schedule_intervals)] = $count;

        return $schedule_intervals;
    }

    public function addOrderToUser(Request $request)
    {
        $currentDate = date("Y-m-d H:i:s");

        if (Auth::user() != null) {

            $orderInCart = Order::where('id_user', Auth::id())->where('id_status', 2)->get()->toArray();

            if (count($orderInCart) < 1) {
                return redirect(route('cart'));
            }

            $request->validate([
                'dateForIntervals' => ['required', 'string', 'size:10'],
                'schedule_interval' => ['required', 'string'],
            ]);

            $propertiesFromRequest = $request->toArray();

            $UserCartController = new UserCartController();
            $scheduleIntervals = $UserCartController->createIntervalsAjax($request);
            $countForDay = $scheduleIntervals[count($scheduleIntervals) - 1];
            $countForDay_copy = $countForDay;

            foreach($scheduleIntervals as $scheduleInterval){
                if(isset($scheduleInterval['id']) && $scheduleInterval['id'] == (int)($request['schedule_interval'])){
                    $isTrueInterval = true;
                    $timeOfInterval = explode(":", $scheduleInterval['end']);
                    $currentDate_plus_day  = mktime(date($timeOfInterval[0]), date($timeOfInterval[1]), date($timeOfInterval[2]), date("m"),   date("d")+1,   date("Y"));
                    $dateForIntervals = strtotime($request['dateForIntervals']);
                    if($dateForIntervals < $currentDate_plus_day){
                        return redirect('cart')->with(['errorInterval' => 'Дата не доступна для выбора, необходимо выбрать дату на день больше текущей', $propertiesFromRequest]);
                    }
                }
            }
            if (!isset($isTrueInterval)) {
                return redirect('cart')->with(['errorInterval' => 'Интервал не доступен для выбора, выберите еще раз', $propertiesFromRequest]);
            }

            $productsInOrder = Order_Product::where('id_order', $orderInCart[0]['id'])->get()->toArray();
            foreach ($productsInOrder as $indexProduct => $productInOrder) {
                foreach ($propertiesFromRequest as $index => $productProperty) {
                    if (preg_match("/^productCount_[0-9]+$/", $index)) {
                        $countId = explode('_', $index);
                        if ($countId[1] == $productInOrder['id_product']) {
                            $countForDay -= $productProperty;
                            if ($countForDay < 0) {
                                return redirect('cart')->with(['errorInterval' => 'Доступно для заказа продуктов на этот день: ' . $countForDay_copy, $propertiesFromRequest]);
                            }
                            break;
                        }
                    }
                }
            }

            foreach ($productsInOrder as $indexProduct => $productInOrder) {
                foreach ($propertiesFromRequest as $index => $productProperty) {
                    $weightId = explode('_', $index);
                    $countId = explode('_', $index);
                    $isWeight = false;
                    $isCount = false;
                    if (preg_match("/^productWeight_[0-9]+$/", $index) && $weightId[1] == $productInOrder['id_product']) {
                        Order_Product::where('id_order', $orderInCart[0]['id'])->where('id_product', (double)$weightId[1])
                            ->update(['weight' => $productProperty]);
                        $productsInOrder[$indexProduct]['status'] = true;
                        $isWeight = true;
                    }
                    if (preg_match("/^productCount_[0-9]+$/", $index) && $countId[1] == $productInOrder['id_product']) {
                        Order_Product::where('id_order', $orderInCart[0]['id'])->where('id_product', (int)$countId[1])
                            ->update(['count' => $productProperty]);
                        $productsInOrder[$indexProduct]['status'] = true;
                        $isCount = true;
                    }
                    if ($isCount && $isWeight)
                        break;
                }

                if (!isset($productsInOrder[$indexProduct]['status'])) {
                    $product = Product::find($productInOrder['id_product'])->toArray();
                    $product_type = Product_Type::find($product['id_product_type'])->toArray();
                    Order_Product::where('id_order', $orderInCart[0]['id'])->where('id_product', $productInOrder['id_product'])
                        ->update(['weight' => $product_type['weight_initial'], 'count' => 1]);
                }
            }

            $orderDate = $propertiesFromRequest['dateForIntervals'];

            $weekDays = array(7 => 'воскресенье', 1 => 'понедельник', 2 => 'вторник',
                3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота');

            $orderDayCode = (int)date('N', strtotime($orderDate));

            $schedule_standard = Schedule_Standard::where('isActive', true)->where('weekday', $weekDays[$orderDayCode])->first()->toArray();

            $currentOrder = Order::find($orderInCart[0]['id']);
            $currentOrder->id_status = 1;
            $currentOrder->will_cooked_at = $propertiesFromRequest['dateForIntervals'];
            $currentOrder->id_schedule_standard = $schedule_standard['id'];
            $currentOrder->id_schedule_interval = (int)$propertiesFromRequest['schedule_interval'];
            $currentOrder->save();

            return redirect(route('order', $orderInCart[0]['id']));
        } else {

            if (isset($propertiesFromRequest['name']) && isset($propertiesFromRequest['email']) &&
                isset($propertiesFromRequest['phone']) && isset($propertiesFromRequest['password'])) {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'phone' => ['required', 'string', 'regex:/^8\d{10,10}$/'],
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                    'dateForIntervals' => ['required', 'string', 'size:10'],
                    'schedule_interval' => ['required', 'string'],
                ]);
            } elseif(isset($propertiesFromRequest['name'])){
                $request->validate([
                    'email' => ['filled', 'string', 'email', 'max:255'],
                    'dateForIntervals' => ['required', 'string', 'size:10'],
                    'schedule_interval' => ['required', 'string'],
                ]);
            }

            $propertiesFromRequest = $request->toArray();


            $UserCartController = new UserCartController();
            $scheduleIntervals = $UserCartController->createIntervalsAjax($request);
            $countForDay = $scheduleIntervals[count($scheduleIntervals) - 1];
            $countForDay_copy = $countForDay;

            foreach($scheduleIntervals as $scheduleInterval){
                if(isset($scheduleInterval['id']) && $scheduleInterval['id'] == (int)($request['schedule_interval'])){
                    $isTrueInterval = true;
                    $timeOfInterval = explode(":", $scheduleInterval['end']);
                    $currentDate_plus_day  = mktime(date($timeOfInterval[0]), date($timeOfInterval[1]), date($timeOfInterval[2]), date("m"),   date("d")+1,   date("Y"));
                    $dateForIntervals = strtotime($request['dateForIntervals']);
                    if($dateForIntervals < $currentDate_plus_day){
                        return redirect('cart')->with(['errorInterval' => 'Дата не доступна для выбора, необходимо выбрать дату на день больше текущей', $propertiesFromRequest]);
                    }
                }
            }
            if (!isset($isTrueInterval)) {
                return redirect('cart')->with(['errorInterval' => 'Интервал не доступен для выбора, выберите еще раз', $propertiesFromRequest]);
            }

            $productsInOrder = Cookie::get();

            foreach ($productsInOrder as $indexProduct => $cookie) {
                if (preg_match("/^orderInCartProducts_[0-9]+$/", $indexProduct)) {
                    foreach ($propertiesFromRequest as $index => $productProperty) {
                        if (preg_match("/^productCount_[0-9]+$/", $index)) {
                            $countId = explode('_', $index);
                            if ($countId[1] == (int)$cookie) {
                                $countForDay -= $productProperty;
                                if ($countForDay < 0) {
                                    return redirect('cart')->with(['errorInterval' => 'Доступно для заказа продуктов на этот день: ' . $countForDay_copy, $propertiesFromRequest]);
                                }
                                break;
                            }
                        }
                    }
                }
            }



            $orderDate = $propertiesFromRequest['dateForIntervals'];

            $weekDays = array(7 => 'воскресенье', 1 => 'понедельник', 2 => 'вторник',
                3 => 'среда', 4 => 'четверг', 5 => 'пятница', 6 => 'суббота');

            $orderDayCode = (int)date('N', strtotime($orderDate));

            $schedule_standard = Schedule_Standard::where('isActive', true)->where('weekday', $weekDays[$orderDayCode])->first()->toArray();

            if (isset($propertiesFromRequest['name']) && isset($propertiesFromRequest['email']) &&
                isset($propertiesFromRequest['phone']) && isset($propertiesFromRequest['password'])) {
                $RegisteredUserController = new Controllers\Auth\RegisteredUserController();
                $RegisteredUserController->store($request);

                $currentOrder = Order::create([
                    'id_user' => Auth::id(),
                    'id_status' => 1,
                    'will_cooked_at' => $propertiesFromRequest['dateForIntervals'],
                    'id_schedule_interval' => (int)$propertiesFromRequest['schedule_interval'],
                    'id_schedule_standard' => $schedule_standard['id'],
                ]);

            } elseif (isset($propertiesFromRequest['email'])) {
                $user = User::where('email', $propertiesFromRequest['email'])->first()->toArray();

                if (count($user) < 1) {
                    return redirect('cart')->with(['errorInterval' => 'Пользователь с указаной почтой не существует', $propertiesFromRequest]);
                }

                $currentOrder = Order::create([
                    'id_user' => $user['id'],
                    'id_status' => 1,
                    'will_cooked_at' => $propertiesFromRequest['dateForIntervals'],
                    'id_schedule_interval' => (int)$propertiesFromRequest['schedule_interval'],
                    'id_schedule_standard' => $schedule_standard['id'],
                ]);
            }

//            var_dump($request->toArray(), $productsInOrder);
//            return;

            foreach ($productsInOrder as $indexProduct => $cookie) {
                if (preg_match("/^orderInCartProducts_[0-9]+$/", $indexProduct)) {
                    $isWeight = false;
                    $isCount = false;
                    $currentWeight = 0;
                    $currentCount = 0;
                    foreach ($propertiesFromRequest as $index => $productProperty) {
                        $weightId = explode('_', $index);
                        $countId = explode('_', $index);
                        if (preg_match("/^productWeight_[0-9]+$/", $index) && $weightId[1] == $cookie) {
                            $currentWeight = (double)$productProperty;
                            $isWeight = true;
                        }
                        if (preg_match("/^productCount_[0-9]+$/", $index) && $countId[1] == $cookie) {
                            $currentCount = (int)$productProperty;
                            $isCount = true;
                        }
                        if ($isCount && $isWeight) {
                            Order_Product::insert(['id_product' => (int)$cookie, 'id_order' => $currentOrder['id'],
                                'count' => $currentCount, 'weight' => $currentWeight,
                                'created_at'=>$currentDate,'updated_at'=>$currentDate]);
                            break;
                        }
                    }

                    if (!($isCount && $isWeight)) {
                        $product = Product::find((int)$cookie)->toArray();
                        $product_type = Product_Type::find($product['id_product_type'])->toArray();
                        Order_Product::insert(['id_product' => (int)$cookie, 'id_order' => $currentOrder['id'],
                            'count' => 1, 'weight' => $product_type['weight_initial'],
                            'created_at'=>$currentDate,'updated_at'=>$currentDate]);
                    }

                    Cookie::queue(Cookie::forget('orderInCartProducts_' . $cookie));
                }
            }

            if(Auth::user() != null)
                return redirect(route('order', $currentOrder['id']));
            else
                return redirect(route('main'));
        }
    }
}
