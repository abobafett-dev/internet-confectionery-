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
    <div id="order_container">
        <h2 style="all:revert; margin: 10px 0px;">Данные о заказе</h2>
        <div id="order_left">
            <div id="order_products">
                <h3 style="all:revert; margin: 5px;">Купленные товары</h3>
                @php $sum = 0; @endphp
                @foreach($order[0]['products'] as $product)
                    @php $sum += $product['price']*$product['data']['count']*($product['data']['weight']/$product['product_type']['weight_initial']); @endphp
                    <div class="order_products">
                        <div class="order-img-prod">
                            <img src="{{asset($product['photo'])}}" alt="">
                        </div>
                        <div style="padding: 10px; width: 100%">
                            <div>
                                <h4 style="all:revert; margin: 0px;">Название</h4>
                                <span style="">{{$product['name']}}</span>
                            </div>
                            <div>
                                <h4 style="all:revert; margin: 0px;">Описание</h4>
                                <span style="">{{$product['description']}}</span>
                            </div>
                            <div>
                                <h4 style="all:revert; margin: 0px;">Стоимость</h4>
                                <span style="">{{$product['price']}}</span>₽ за <span
                                    style="">{{$product['product_type']['weight_initial']}}</span>кг
                            </div>
                            <div>
                                <h4 style="all:revert; margin: 0px;">Выбранно</h4>
                                <span style="">{{$product['data']['weight']}}</span>кг, <span
                                    style="">{{$product['data']['count']}}</span>шт.
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div id="order_right">
            <h3 style="all:revert; margin: 5px;">Время и статус</h3>
            <div id="order_dates">
                <div id="date_order">
                    <h4 style="all:revert; margin: 0px;">Дата оформления заказа</h4>
                    {{$order[0]['created_at']}}
                </div>

                <div id="status">
                    <h4 style="all:revert; margin: 0px;">Текущий статус</h4>
                    {{$order[0]['status']['status']}}
                </div>

                <div id="date_cook">
                    <h4 style="all:revert; margin: 0px;">Дата приготовления</h4>
                    <span id="will_cooked_at" style="margin-right: 10px;">{{$order[0]['will_cooked_at']}}</span> <span
                        id="interval">{{$order[0]['interval']['start']}}</span>
                </div>
            </div>

            @if($order[0]['status']['id'] != 7)
                <h3 style="all:revert; margin: 5px;">Стоимость</h3>
                <div id="money">
                    <div id="price">
                        <h4 style="all:revert; margin: 0px;">Сумма</h4>
                        {{$sum}}₽
                    </div>

                    <div id="bonus">
                        <h4 style="all:revert; margin: 0px;">Бонусами</h4>
                        @if($order[0]['paidByPoints']){{$order[0]['paidByPoints']}} @else 0 @endif
                    </div>

                    <div id="cost">
                        <h4 style="all:revert; margin: 0px;">Итого</h4>
                        {{$sum - $order[0]['paidByPoints']}}₽
                    </div>

                    <div id="paid">
                        <h4 style="all:revert; margin: 0px;">Оплачено</h4>
                        @if($order[0]['status']['id'] == 5 || $order[0]['status']['id'] == 6)
                            ){{$sum - $order[0]['paidByPoints']}}₽ @else {{($sum - $order[0]['paidByPoints'])/2}}
                        ₽ @endif
                    </div>
                </div>
            @endif
            @if($order[0]['status']['id'] != 7 || $order[0]['status']['id'] != 6)
                <h3 style="all:revert; margin: 5px;">Комментарий к заказу</h3>
                <div id="comment">
                    {{$order[0]['review_text']}}
                </div>
            @endif
            <h3 style="all:revert; margin: 5px;">Сообщение, которое вам нужно отправить кондитеру</h3>
            <div id="message">
                <textarea cols="55" style="resize: none; overflow: hidden;" readonly>Здравствуйте, я хочу сделать у вас покупку. &#10;Номер моего заказа на вашем сайте: {{$order[0]['id']}}
                </textarea>
            </div>
            <h3 style="all:revert; margin: 5px;">Ссылка на кондитера</h3>
            <div id="message">
                <a style="color: #c08d87" href="https://www.instagram.com/cakemechtai.tmn/">@<span style="text-decoration: underline; ">cakemechtai.tmn</span></a>
            </div>
        </div>
    </div>
    <div style="width: 210px; margin: auto; padding:20px;">
        <a href="{{route('main')}}" style="text-decoration: underline;">Вернуться на главную</a>
    </div>
</x-app-layout>
