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
use Nette\Utils\DateTime;

class AdminOrdersPageController extends Controller
{
    function create()
    {
        if (Auth::user()->id_user_status != 2)
            abort(403);

        $currentDay = date('Y-m-d');

        $orders = Order::where('will_cooked_at', $currentDay)->get()->toArray();

        $AdminFunctionsController = new AdminFunctionsController();

        $orders = $AdminFunctionsController->createOrders($orders);

        var_dump($orders);
        return;

        return view('adminOrders')->with(['data' => $orders, 'date' => $currentDay]);
    }

    function createAjax(Request $request)
    {
        if (Auth::user()->id_user_status != 2)
            abort(403);

        $date = $request->toArray()['date'];

        if(DateTime::createFromFormat('Y-m-d', $date) == false)
            abort(404);

        $currentDay = $date;

        $orders = Order::where('will_cooked_at', $currentDay)->get()->toArray();

        $AdminFunctionsController = new AdminFunctionsController();

        $orders = $AdminFunctionsController->createOrders($orders);

        return ['data' => $orders, 'date' => $date];
    }

    //Функция для изменения статуса заказа
    //В атрибут надо отправить
    // order = id заказа
    // status = id статуса на который надо поменять текущий статус
    // $this->changeStatusAjax(new Request(['order'=>21,'status'=>7]));
    function changeStatusAjax(Request $request)
    {
        if (Auth::user()->id_user_status != 2)
            abort(403);

        $request = $request->toArray();

        $currentOrder = Order::find($request['order']);

        if ($currentOrder != null) {
            $currentOrder->update(['id_status' => $request['status']]);

            $currentIntervalStart = Schedule_Interval::find($currentOrder->id_schedule_interval)->start;

            $old_status = Order_Status::find($currentOrder['id_status'])->toArray();

            $new_status = Order_Status::find($request['status'])->toArray();

            return "Статус заказа на " . $currentIntervalStart . " был изменен с «" . $old_status['status'] . "» на «" . $new_status['status'] . "»";
        } else {
            return "Ошибка! заказ не обнаружен. Перезагрузите страницу Ctrl+F5";
        }
    }
}
