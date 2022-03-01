<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
            {{ __('Календарь заказов') }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}
            </div>
        @endif
    </x-slot>
{{--    <div style="padding: 15px;">--}}
{{--        <div class="adminOrdersGridContainer">--}}
{{--            <div class="adminOrders-grid-container">--}}
{{--                <div>1.1</div>--}}
{{--                <div>1.2</div>--}}
{{--            </div>--}}
{{--            <div class="adminOrders-grid-container">--}}
{{--                <div>2.1</div>--}}
{{--                <div>2.2</div>--}}
{{--            </div>--}}
{{--            <div class="adminOrders-grid-container">3</div>--}}
{{--            <div class="adminOrders-grid-container">4</div>--}}
{{--            <div class="adminOrders-grid-container">5</div>--}}
{{--            <div class="adminOrders-grid-container">6</div>--}}
{{--            <div class="adminOrders-grid-container">7</div>--}}
{{--        </div>--}}



{{--    </div>--}}
    <div style="padding: 20px;">
        <div style="text-align: center; padding: 0px 0px 20px 0px;"><input type="date" value="{{$date}}"></div>
    <table class="border" style="width: 100%; border-radius: 10px;">
    <tr class="border" style="text-align: center;">
        <td class="border">Время</td>
        <td class="border">Статус</td>
        <td class="border">Вид</td>
        <td class="border">Кг/Шт</td>
{{--        <td class="border">Начинка</td>--}}
        <td class="border" style="width: 30%;">Оформление</td>
        <td class="border" style="width: 30%;">Комменатрий</td>
    </tr>
    @foreach($data as $order)
            @php
                $rowCount = count($order['products']);
                $firstProd = array_shift($order['products']);
            @endphp
            <tr class="border">
                <td class="border" rowspan="{{$rowCount}}">{{$order['interval']['start']}}</td>
                <td class="border" rowspan="{{$rowCount}}">
                    <select name="" id="" style="border: none;">
                        <option value="" hidden selected>{{$order['status']['status']}}</option>
                        <option value="1">Принят</option>
                        <option value="3" style="background-color: yellow;">Готовится</option>
                        <option value="4" style="background-color: limegreen;">Готов/Оплата</option>
                        <option value="6" style="background-color: darkgreen;">Выдан</option>
                        <option value="7" style="background-color: red;">Отменён</option>
                    </select>
                </td>
                <td class="border">{{$firstProd['name']}}</td>
                <td class="border">{{$firstProd['data']['weight']}}</td>
{{--                <td class="border">{{$firstProd['data']['weight']}}</td>--}}
                <td class="border">{{$firstProd['data']['comment_photo']}}</td>
                <td class="border">{{$firstProd['data']['comment_text']}}</td>
            </tr>
            @foreach($order['products'] as $product)
                <tr class="border">
                    <td class="border">{{$product['name']}}</td>
                    <td class="border">{{$product['data']['weight']}}</td>
                    <td class="border">{{$product['data']['comment_photo']}}</td>
                    <td class="border">{{$product['data']['comment_text']}}</td>
                </tr>
            @endforeach
    @endforeach

    </table>
    </div>
</x-app-layout>
