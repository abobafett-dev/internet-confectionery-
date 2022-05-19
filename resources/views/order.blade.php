<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="max-width: 510px;">
            {{ __('Заказ №'.$order[0]['id']) }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}
            </div>
        @endif
    </x-slot>
    <div style="padding: 20px;">
    @php $sum = 0; @endphp
{{--    <div id="order_container">--}}
{{--        <h2 style="all:revert; margin: 10px 0px;">Данные о заказе</h2>--}}
{{--        <div id="order_left">--}}
{{--            <div id="order_products">--}}
{{--                <h3 style="all:revert; margin: 5px;">Купленные товары</h3>--}}

                @foreach($order[0]['products'] as $product)
                    @php if($product['data']['weight']) {$sum += ($product['data']['weight']/$product['product_type']['weight_initial'])*$product['price']*$product['data']['count'];} else {$sum += $product['price']*$product['data']['count'];} @endphp
{{--                    <div class="order_products">--}}
{{--                        <div class="order-img-prod">--}}
{{--                            <img src="{{asset($product['photo'])}}" alt="">--}}
{{--                        </div>--}}
{{--                        <div style="padding: 10px; width: 100%">--}}
{{--                            <div>--}}
{{--                                <h4 style="all:revert; margin: 0px;">Название</h4>--}}
{{--                                <span style="">{{$product['name']}}</span>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <h4 style="all:revert; margin: 0px;">Описание</h4>--}}
{{--                                <span style="">{{$product['description']}}</span>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <h4 style="all:revert; margin: 0px;">Стоимость</h4>--}}
{{--                                <span style="">{{$product['price']}}</span>₽ за <span--}}
{{--                                    style="">{{$product['product_type']['weight_initial']}}</span>кг--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <h4 style="all:revert; margin: 0px;">Выбранно</h4>--}}
{{--                                <span style="">{{$product['data']['weight']}}</span>кг, <span--}}
{{--                                    style="">{{$product['data']['count']}}</span>шт.--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                @endforeach
{{--            </div>--}}
{{--        </div>--}}
{{--        <div id="order_right">--}}
{{--            <h3 style="all:revert; margin: 5px;">Время и статус</h3>--}}
{{--            <div id="order_dates">--}}
{{--                <div id="date_order">--}}
{{--                    <h4 style="all:revert; margin: 0px;">Дата оформления заказа</h4>--}}
{{--                    {{date('Y-m-d  H:i',strtotime($order[0]['created_at']))}}--}}
{{--                </div>--}}

{{--                <div id="status">--}}
{{--                    <h4 style="all:revert; margin: 0px;">Текущий статус</h4>--}}
{{--                    {{$order[0]['status']['status']}}--}}
{{--                </div>--}}

{{--                <div id="date_cook">--}}
{{--                    <h4 style="all:revert; margin: 0px;">Дата приготовления</h4>--}}
{{--                    <span id="will_cooked_at" style="margin-right: 5px;">{{$order[0]['will_cooked_at']}}</span> <span--}}
{{--                        id="interval">{{date('H:i',strtotime($order[0]['interval']['start']))}}</span>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            @if($order[0]['status']['id'] != 7)--}}
{{--                <h3 style="all:revert; margin: 5px;">Стоимость</h3>--}}
{{--                <div id="money">--}}
{{--                    <div id="price">--}}
{{--                        <h4 style="all:revert; margin: 0px;">Сумма</h4>--}}
{{--                        {{$sum}}₽--}}
{{--                    </div>--}}

{{--                    <div id="bonus">--}}
{{--                        <h4 style="all:revert; margin: 0px;">Бонусами</h4>--}}
{{--                        @if($order[0]['paidByPoints']){{$order[0]['paidByPoints']}} @else 0 @endif--}}
{{--                    </div>--}}

{{--                    <div id="cost">--}}
{{--                        <h4 style="all:revert; margin: 0px;">Итого</h4>--}}
{{--                        {{$sum - $order[0]['paidByPoints']}}₽--}}
{{--                    </div>--}}

{{--                    <div id="paid">--}}
{{--                        <h4 style="all:revert; margin: 0px;">Оплачено</h4>--}}
{{--                        @if($order[0]['status']['id'] == 5 || $order[0]['status']['id'] == 6)--}}
{{--                            {{$sum - $order[0]['paidByPoints']}}₽ @else {{($sum - $order[0]['paidByPoints'])/2}}--}}
{{--                        ₽ @endif--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}

{{--        </div>--}}
{{--    </div>--}}
    @if(count($order) > 0)
        <div style="">
            @foreach($order as $order)
                <div class="order">
                    <div class="info">
                        <div style="background-color:
                        @switch($order['status']['status'])
                        @case('В корзине')
                            #979595
                        @break
                        @case('Принят')
                            #edab23
                        @break
                        @case('Готовится')
                            #88d792
                        @break
                        @case('Готов/Оплата')
                            #52b7ff
                        @break
                        @case('Оплачен')
                            #ffbdb5
                        @break
                        @case('Выдан')
                            #24d53a
                        @break
                        @case('Отменён')
                            #df3535
                        @break
                        @default
                            red
                        @endswitch
                            ;font-family: sans-serif; display: flex; align-items: center; justify-content: space-between;">
                            <div style="padding: 5px 15px; width: 160px;">
{{--                                {{date('d.m.Y',strtotime($order['will_cooked_at']))}}--}}
{{--                                {{date('h:i',strtotime($order['interval']['start']))}}--}}
                            </div>
                            <div style="padding: 5px 15px; width: 160px; font-weight: bold;">
                                {{$order['status']['status']}}
                            </div>
                            <div style="padding: 5px 15px; width: 160px;">
                                {{--                                    <form action="" method="POST">--}}
                                {{--                                        <input type="text" hidden value="{{$order['id']}}">--}}
                                {{--                                        <button style="color: #730101FF; text-decoration: underline #730101;">Удалить--}}
                                {{--                                            заказ--}}
                                {{--                                        </button>--}}
                                {{--                                    </form>--}}
                            </div>
                        </div>
                        <div
                            style="display: flex; justify-content: space-between; padding: 15px; border-bottom: 1px solid rgba(206,206,206,0.75);">
                            <div style="text-align: left;">
                                <div style="all:revert; margin: 0px; font-weight: bold;">Дата оформления заказа</div>
                                {{date('Y-m-d  H:i',strtotime($order['created_at']))}}
                                <div style="all:revert; margin: 0px; font-weight: bold;">Дата приготовления</div>
                                <span id="will_cooked_at" style="text-align: center;">{{$order['will_cooked_at']}}</span> <span
                                    id="interval" style="text-align: center;">{{date('H:i',strtotime($order['interval']['start']))}}</span>
                                @if($order['status']['id'] != 7)
                                <div id="price" style="margin-top: 10px;">
                                    <div style="all:revert; margin: 0px;">Сумма: <span style="font-weight: bold;">{{$sum}} ₽</span></div>
                                </div>
                                <div id="paid">
                                    <div style="all:revert; margin: 0px;">Оплачено:
                                        <span style="font-weight: bold;">@if($order['status']['id'] == 5 || $order['status']['id'] == 6)
                                                {{$sum - $order['paidByPoints']}}₽ @elseif($order['status']['id'] == 7) Отменён @elseif($order['status']['id'] == 2) В корзине @else {{($sum - $order['paidByPoints'])/2}}
                                                ₽ @endif</span></div>
                                </div>
                                @endif
                            </div>
                            <div
                                style="display: flex; justify-content: space-between; flex-direction: column; width: 470px; text-align: left;">
                                <div>
                                    <div style="all:revert; margin: 5px 0; font-weight: bold;">Сообщение, которое вам нужно отправить кондитеру</div>
                                    <div>
                                        <textarea style="resize: none; overflow: hidden; border-radius: 5px; width: 350px;" readonly>Здравствуйте, я оформил заказ на сайте. &#10;Номер моего заказа: {{$order['id']}}</textarea>
                                    </div>
                                    <div style="all:revert; margin: 5px 0; font-weight: bold;">Ссылка на кондитера</div>
                                    <div style="font-weight: bold;">
                                        VK: <a target="_blank" style="color: black; font-weight: bold;" href="https://vk.me/cakemechtai"><span style="text-decoration: underline; color: #5d84ae">cakemechtai.tmn</span></a>
                                    </div>
                                    {{--            @if($order[0]['review_text'] != null)--}}
                                    {{--                <h3 style="all:revert; margin: 5px;">Комментарий к заказу</h3>--}}
                                    {{--                <div id="comment">--}}
                                    {{--                   {{$order[0]['review_text']}}--}}
                                    {{--                </div>--}}
                                    {{--            @endif--}}
                                </div>
                                {{--                                        @if($product['data']['weight'])--}}
                                {{--                                            <div style="font-weight: bold;">{{$product['price']}}₽--}}
                                {{--                                                за {{$product['product_type']['weight_initial']}}кг--}}
                                {{--                                            </div>@endif--}}
                            </div>
                            <div
                                style="text-align: right; margin: 5px 10px; width: 180px;display: flex; justify-content: center; flex-direction: column;">
                                    <div style="font-weight: bold; margin-bottom: 10px;">

                                    </div>
                            </div>
                        </div>
                        @foreach($order['products'] as $product)
{{--                            @php if($product['data']['weight']) {$sum += ($product['data']['weight']/$product['product_type']['weight_initial'])*$product['price']*$product['data']['count'];} else {$sum += $product['price']*$product['data']['count'];} @endphp--}}
                            <div
                                style="display: flex; justify-content: space-between; padding: 15px; border-bottom: 1px solid rgba(206,206,206,0.75);">
                                <div>
                                    <img src="{{$product['photo']}}" style="width:10em;">
                                </div>
                                <div
                                    style="display: flex; justify-content: space-between; flex-direction: column; width: 470px;">
                                    <div>
                                        <h2 style="font-weight: bold; font-size: 1.5em;">{{$product['name']}}</h2>
                                        <div style="text-align: justify;">{{$product['description']}}</div>
                                    </div>
                                </div>
                                <div
                                    style="text-align: right; margin: 5px 10px; width: 150px;display: flex; justify-content: center; flex-direction: column;">
                                    @if($product['data']['weight'])
                                        <div style="margin-bottom: 10px;">
                                            Цена: <span style="font-weight: bold;">{{$product['price']*$product['data']['count']*$product['data']['weight']/$product['product_type']['weight_initial']}}
                                            ₽</span>
                                        </div>
                                        <div style="">
                                            {{$product['data']['count']}} шт. x {{$product['data']['weight']}} кг<br>x {{$product['price']/$product['product_type']['weight_initial']}} ₽/кг
                                        </div>
                                    @else
                                        <div style=" margin-bottom: 10px;">
                                            Цена: <span style="font-weight: bold;">{{$product['price']*$product['data']['count']}}
                                            ₽</span>
                                        </div>
                                        <div style="">
                                            {{$product['data']['count']}} шт. <br>х {{$product['price']}} ₽
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div style="display: flex; justify-content: space-between;">
{{--                            <div style="width: 25%; text-align: left; padding: 5px 15px;">--}}
{{--                                <a href="{{route('order',$order['id'])}}"--}}
{{--                                   style="padding: 5px 15px; color: #3636e3; text-decoration: underline;">Подробнее--}}
{{--                                </a>--}}
{{--                            </div>--}}
                            <div style="width: 35%; text-align: right;">
                                {{--                                    <form action="" method="">--}}
                                {{--                                        <button--}}
                                {{--                                            style="padding: 5px 15px; color: #3636e3; text-decoration: underline;">--}}
                                {{--                                            Оставить--}}
                                {{--                                            отзыв--}}
                                {{--                                        </button>--}}
                                {{--                                    </form>--}}
                            </div>
                            <div style="text-align: right; width: 42%; padding: 5px 25px;">
                                Итого: <span style=" font-weight: bold;">{{$sum}}₽</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="width: 210px; margin: auto; padding:20px;">
            <a href="{{route('main')}}" style="text-decoration: underline;">Вернуться на главную</a>
        </div>
    @else
        <div>К сожалению, данный заказ недоступен в настоящее время.</div>
        <div style="width: 210px; margin: auto; padding:20px;">
            <a href="{{route('main')}}" style="text-decoration: underline;">Вернуться на главную</a>
        </div>
    @endif
    </div>
</x-app-layout>
