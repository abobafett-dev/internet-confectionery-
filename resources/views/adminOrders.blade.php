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
    <div style="padding: 20px;" id="">
        <div style="text-align: center; padding: 0px 0px 20px 0px;"><input type="date" value="{{$date['date']}}" onchange="dateOfBoard(this.value)"></div>
        <div id="container">
            <h1 style="font-size: 26px; margin-left: 20px;">День недели: {{$date['weekDay']}}</h1>
            @if(count($data['orders']) > 0)
            <table class="border" style="width: 100%; border-radius: 10px;" id="table">
                <tr class="border" style="text-align: center;" id="header">
                    <td class="border">Время</td>
                    <td class="border">Статус</td>
                    <td class="border">Вид</td>
                    <td class="border" colspan="2">Подробнее</td>
                    <td class="border">Кг</td>
                    <td class="border">Шт</td>
                    <td class="border" style="">Комменатрий</td>
                    <td class="border" style="">Оформление</td>
                </tr>
{{--                {{var_dump($data['ingregients'])}}--}}
                @foreach($data['orders'] as $order)
{{--                    {{var_dump($order['products'][1])}}--}}
                    @php
                        $firstProd = array_shift($order['products']);
                    @endphp
                    <tr class="border">
                        <td class="border" rowspan="{{$order['countProducts']}}">{{$order['interval']['start']}}</td>
                        <td class="border" rowspan="{{$order['countProducts']}}">
                            <select name="" id="" style="border: none;"
                                    onchange="changeStatus(this.value, {{$order['id']}})">
                                <option value="" hidden selected>{{$order['status']['status']}}</option>
                                <option value="1">Принят</option>
                                <option value="3" style="background-color: yellow;">Готовится</option>
                                <option value="4" style="background-color: limegreen;">Готов/Оплата</option>
                                <option value="6" style="background-color: darkgreen;">Выдан</option>
                                <option value="7" style="background-color: red;">Отменён</option>
                            </select>
                        </td>
                        <td class="border">{{$firstProd['product_type']['name']}}</td>
                        <td class="border" style="text-align: left;">
                            @foreach($firstProd['components'] as $component)
                            <div class="detail">{{$component['component_type']['name']}}</div>
                            @endforeach
                        </td>
                        <td class="border" style="text-align: left;">
                            @foreach($firstProd['components'] as $component)
                                <div class="detail">{{$component['name']}}</div>
                            @endforeach
                        </td>
                        <td class="border">{{$firstProd['data']['weight']}}</td>
                        <td class="border">{{$firstProd['data']['count']}}</td>
                        <td class="border">
{{--                            @foreach($firstProd['data']['comment_text'] as $comment)--}}
                                {{$firstProd['data']['comment_text']}}
{{--                                <br>--}}
{{--                            @endforeach--}}
                        </td>
                        <td class="border">
{{--                            @foreach($firstProd['data']['comment_photo'] as $photo)--}}
{{--                                <img src="{{$photo}}" alt="">--}}
{{--                                <br>--}}
{{--                            @endforeach--}}
                        </td>
                    </tr>
                    @foreach($order['products'] as $product)
                        <tr class="border">
                            <td class="border">{{$product['product_type']['name']}}</td>
                            <td class="border">{{$product['data']['weight']}}</td>
                            <td class="border">
{{--                                @foreach($firstProd['data']['comment_text'] as $comment)--}}
                                    {{$firstProd['data']['comment_text']}}
{{--                                    <br>--}}
{{--                                @endforeach--}}
                            </td>
                            <td class="border">
{{--                                @foreach($firstProd['data']['comment_photo'] as $photo)--}}
{{--                                    <img src="{{$photo}}" alt="">--}}
{{--                                    <br>--}}
{{--                                @endforeach--}}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </table>
            @else
                <h3>На данную дату нет заказов</h3>
            @endif
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function dateOfBoard(date) {
            document.getElementById('container').innerHTML = '<h3 style="text-align: center; width: 100%;">Загрузка</h3>';
            $.ajax({
                url: "{{route('createAjax')}}",
                type: "POST",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {date: date},
                success: function (data) {
                    if (data['data']['orders'].length > 0) {
                        document.getElementById('container').innerHTML = '<h1 style="font-size: 26px; margin-left: 20px;">День недели: '+data['date']['weekDay']+'</h1><table class="border" style="width: 100%; border-radius: 10px;" id="table"></table>'
                        document.getElementById('table').innerHTML = '<tr class="border" style="text-align: center;" id="header"> <td class="border">Время</td> <td class="border">Статус</td> <td class="border">Вид</td> <td class="border" colspan="2">Подробнее</td><td class="border">Кг</td> <td class="border">Шт</td><td class="border" style="">Комменатрий</td><td class="border" style="">Оформление</td></tr>'
                        let dataForTable = '';
                        for(order of data['data']['orders']){
                            var keys = Object.keys(order['products'])
                            let firstProd = order['products'][keys[0]];
                            console.log(firstProd);
                            delete order['products'][keys[0]];
                            dataForTable += '<tr class="border"> <td class="border" rowspan="'+order['countProducts']+'">'+order['interval']['start']+'</td><td class="border" rowspan="'+order['countProducts']+'"><select name="" id="" style="border: none;" onchange="changeStatus(this.value, '+order['id']+')">'
                            dataForTable += '<option value="" hidden selected>'+order['status']['status']+'</option>'
                            dataForTable += '<option value="1">Принят</option>'
                            dataForTable += '<option value="3" style="background-color: yellow;">Готовится</option>'
                            dataForTable += '<option value="4" style="background-color: limegreen;">Готов/Оплата</option>'
                            dataForTable += '<option value="6" style="background-color: darkgreen;">Выдан</option>'
                            dataForTable += '<option value="7" style="background-color: red;">Отменён</option>'
                            dataForTable += '</select>'
                            dataForTable += '</td>'
                            dataForTable += '<td class="border">'+firstProd['product_type']['name']+'</td>'
                            dataForTable += '<td class="border" style="text-align: left;">'
                            let componentHTML = '';
                            for(component in firstProd['components']){
                                componentHTML += '<div class="detail">'+firstProd['components'][component]['component_type']['name']+'</div>'
                            }
                            dataForTable += componentHTML+'</td>';
                            dataForTable += '<td class="border" style="text-align: left;">';
                            componentHTML = '';
                            for(component in firstProd['components']){
                                componentHTML += '<div class="detail">'+firstProd['components'][component]['name']+'</div>'
                            }
                            dataForTable += componentHTML+'</td>'
                            dataForTable += '<td class="border">'+firstProd['data']['weight']+'</td>'
                            dataForTable += '<td class="border">'+firstProd['data']['count']+'</td>'
                            let comment =  '<td class="border">';
                            // for(var i = 0; i < firstProd['data']['comment_text'].length; i++){
                            //     comment += firstProd['data']['comment_text'][i]+'<br>'
                            // }
                            // comment += '</td>'
                            comment += firstProd['data']['comment_text']+'</td>'
                            dataForTable += comment;
                            dataForTable += '<td class="border">'+firstProd['data']['comment_photo']+'</td>'
                            dataForTable += '</tr>'

                            let product = order['products'];
                            for(id in product){
                                componentHTML = '<td class="border" style="text-align: left;">';
                                for(component in firstProd['components']){
                                    componentHTML += '<div class="detail">'+firstProd['components'][component]['component_type']['name']+'</div>'
                                }
                                componentHTML += '</td>'
                                componentHTML += '<td class="border" style="text-align: left;">';
                                for(component in firstProd['components']){
                                    componentHTML += '<div class="detail">'+firstProd['components'][component]['name']+'</div>'
                                }
                                componentHTML += '</td>'
                                dataForTable += '<tr class="border"> <td class="border">'+product[id]['product_type']['name']+'</td> '+componentHTML+' <td class="border">'+product[id]["data"]["weight"]+'<td class="border">'+firstProd['data']['count']+'</td></td><td class="border">'+product[id]["data"]["comment_text"]+'</td><td class="border">'+product[id]["data"]["comment_photo"]+'</td></tr>'
                            }

                        }
                        document.getElementById('table').innerHTML += dataForTable;
                    }
                    else {
                        document.getElementById('container').innerHTML = '<h1 style="font-size: 26px; margin-left: 20px;">День недели: '+data['date']['weekDay']+'</h1><h3>На данную дату нет заказов</h3>';
                    }
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
                },
                error: function () {
                    alert('Невозможно изменить статус заказа!');
                }
            });
        }
    </script>
</x-app-layout>
