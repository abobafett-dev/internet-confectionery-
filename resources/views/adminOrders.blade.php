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
    <div style="padding: 20px;">
        <div style="text-align: center; padding: 0px 0px 20px 0px;"><input type="date" value="{{$date}}" onchange="dateOfBoard(this.value)" ></div>
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
                    <select name="" id="" style="border: none;" onchange="changeStatus(this.value, {{$order['id']}})">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function dateOfBoard(date){
            $.ajax({
                url: "{{route('createAjax')}}",
                type: "POST",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {date: date},
                success: function (data) {
                    alert(data['date']);
                    // alert(data['data'][0]['id']);


                    // document.getElementById('cart_intervals').style = 'display:flex;';
                    // document.getElementById('max_count').style = 'display:flex;';
                    // // let summary = 0;
                    // // for (let i = 0; i < document.getElementsByClassName('countProd').length; i++)
                    // //     summary -= -(document.getElementsByClassName('countProd')[i].innerHTML);
                    // // document.getElementById('summaryTotal').innerHTML = summary;
                    // document.getElementById('max').innerHTML = data[data.length - 1];
                    // document.getElementById('cart_intervals').innerHTML = '<h4 style="all: revert; margin: 5px auto;">Свободное время</h4>';
                    // for(let i = 0; i < data.length - 1; i++) {
                    //     setTimeout(() => document.getElementById('cart_intervals').insertAdjacentHTML('beforeend','<div><label class="cart-custom-radio"><input type="radio" name="schedule_interval" value="'+data[i]["id"]+'"><span>'+data[i]["start"]+'</span></label></div>'),200);
                    // }
                },
                error: function () {
                    alert('Невозможно вывести интервалы, выберите другую дату');
                }
            });
        }
        function changeStatus(value, id) {
            $.ajax({
                url: "{{route('changeStatusAjax')}}",
                type: "POST",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {status: value, order: id},
                success: function () {
                    alert('Статус успешно изменён');
                    // document.getElementById('cart_intervals').style = 'display:flex;';
                    // document.getElementById('max_count').style = 'display:flex;';
                    // // let summary = 0;
                    // // for (let i = 0; i < document.getElementsByClassName('countProd').length; i++)
                    // //     summary -= -(document.getElementsByClassName('countProd')[i].innerHTML);
                    // // document.getElementById('summaryTotal').innerHTML = summary;
                    // document.getElementById('max').innerHTML = data[data.length - 1];
                    // document.getElementById('cart_intervals').innerHTML = '<h4 style="all: revert; margin: 5px auto;">Свободное время</h4>';
                    // for(let i = 0; i < data.length - 1; i++) {
                    //     setTimeout(() => document.getElementById('cart_intervals').insertAdjacentHTML('beforeend','<div><label class="cart-custom-radio"><input type="radio" name="schedule_interval" value="'+data[i]["id"]+'"><span>'+data[i]["start"]+'</span></label></div>'),200);
                    // }
                },
                error: function () {
                    alert('Невозможно изменить статус заказа!');
                }
            });
        }
    </script>
</x-app-layout>
