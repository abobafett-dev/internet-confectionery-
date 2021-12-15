<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
            {{ __('Корзина') }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}
            </div>
        @endif
    </x-slot>
    {{--     id_product   --}}
    <div style="padding: 0px 15px 15px 15px; width: 79%; display: inline-block;" >
        <form action="" name="cart" method="POST" onchange="summaryTotalCost()" >
            @if(isset($orderInCart))
            @foreach($orderInCart[0]['products'] as $key => $product)
                <div class="cart_product_block">
{{--                        <div class="width0"><input type="checkbox" name="" id=""></div>--}}
                    <div class="cart_product_info">
                        <div class="cart_product_pic">
                            <img src="{{$product['photo']}}" alt="" style="width: 10em; max-width: none;">
                        </div>
                        <div class="cart_product_description">
                            <div class="cart_product_text">
                                <h1 style="font-size: x-large;">{{$product['name']}}</h1>
                                {{$product['description']}}
                            </div>
                            <div>
{{--                                    <form action="{{route('deleteProductInCart',['product'=>$product['id']])}}"--}}
{{--                                          method="POST" class="delete">--}}
                                    @isset($orderInCart[0]['id'])
                                    <input name="order" value="{{$orderInCart[0]['id']}}" hidden>
                                    @endisset
                                    <button class="button_delete">Удалить
                                        из корзины
                                    </button>
{{--                                        {{csrf_field()}}--}}
{{--                                    </form>--}}
                            </div>
                        </div>
                        <div class="cart_product_counts" onchange="calculateCost({{$key}}, {{$product['price']}}, {{$product['product_type']['weight_initial']}})">
                            <div>
                                Количество
                                <div class="changes_position">
                                    <button type="button" onclick="one_count('count{{$key}}', -1, {{$loop->index}})">–</button>
                                    <input onkeypress="numbersOnly()" name="count{{$key}}" id="count{{$key}}" value="1">
                                    <button type="button" onclick="one_count('count{{$key}}', 1, {{$loop->index}})">+</button>
                                </div>
                            </div>
                            @if($product['product_type']['weight_min'] != $product['product_type']['weight_max'])
                            <div>
                                Вес
                                <div class="changes_position">
                                    <button type="button" onclick="one_weight('weight{{$key}}', -0.5, {{$loop->index}})">–</button>
                                    <input onkeyup="range(this.value, 'weight{{$key}}')"
                                           onkeypress="doubleOnly(this.value)" name="weight{{$key}}"
                                           id="weight{{$key}}"
                                           value="{{$product['product_type']['weight_initial']}}">
                                    <button type="button" onclick="one_weight('weight{{$key}}', 0.5, {{$loop->index}})">+</button>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="cart_product_price">
                            <div style="font-weight: bold; font-size: 18px;">
                                <span class="totalForProduct"
                                   id="total{{$key}}">{{$product['product_type']['weight_initial']*$product['price']}}</span>₽
                            </div>
                            <div style="font-size: 14px;">
                                <span
                                    id="forOne{{$key}}">{{$product['product_type']['weight_initial']*$product['price']}}</span>₽/шт
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    </div>
    <div style="margin-top: 20px; padding: 0px 15px; width: 21%; display: inline-block; float: right;">
        <div class="menuTotal">
            <div style="display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <ul>
                    <li id="itog">Итого: <span id="summaryTotal"></span>₽</li>
                    <li id="pickTime">Выбрать дату и интервал<input type="date" name="" id="minDate"></li>
                </ul>
            </div>
        </div>
        <div class="menuTotal" style="margin-top: 20px;">
            <button id="buttonOrder">
                Оформить заказ!
            </button>
        </div>
    </div>
    <script>
        function one_weight(index, difference, number) {
            document.getElementById(index).setAttribute('value', document.getElementById(index).value);
            if (document.getElementById(index).getAttribute('value') - (-difference) < {{$product['product_type']['weight_min']}}) {
                alert('Минимальный вес продутка {{$product['product_type']['weight_min']}}')
            } else if (document.getElementById(index).getAttribute('value') - (-difference) > {{$product['product_type']['weight_max']}}) {
                alert('Максимальный вес продутка {{$product['product_type']['weight_max']}}')
            } else {
                document.getElementById(index).value -= (-difference);
                document.getElementById(index).setAttribute('value', document.getElementById(index).value);
            }
            document.getElementsByClassName('cart_product_counts')[number].dispatchEvent(new Event('change'));
            document.getElementsByName('cart')[0].dispatchEvent(new Event('change'));
        }

        function one_count(index, difference, number) {
            document.getElementById(index).setAttribute('value', document.getElementById(index).value);
            if (document.getElementById(index).getAttribute('value') - (-difference) < 1) {
                alert('Минимальное количество 1')
            } else {
                document.getElementById(index).value -= (-difference);
                document.getElementById(index).setAttribute('value', document.getElementById(index).value);
                document.getElementsByClassName('cart_product_counts')[number].dispatchEvent(new Event('change'));
                document.getElementsByName('cart')[0].dispatchEvent(new Event('change'));
            }
        }

        function numbersOnly() {
            var prov1 = /[0-9]/.test(event.key);
            if (prov1 == false)
                event.returnValue = false;
        }

        function range(x, index) {
            if ((x != '') && (x < {{$product['product_type']['weight_min']}})) {
                alert('Минимальный вес продутка {{$product['product_type']['weight_min']}}');
                document.getElementById(index).value = {{$product['product_type']['weight_min']}};
                document.getElementById(index).setAttribute('value', {{$product['product_type']['weight_min']}});
            } else if ((x != '') && (x > {{$product['product_type']['weight_max']}})) {
                alert('Максимальный вес продукта {{$product['product_type']['weight_max']}}');
                document.getElementById(index).value = {{$product['product_type']['weight_max']}};
                document.getElementById(index).setAttribute('value', {{$product['product_type']['weight_max']}});
            }
        }

        function doubleOnly(x) {
            var prov1 = /[0-9]/.test(event.key);
            var prov2 = /[.]/.test(event.key);

            if (prov1 == false && prov2 == false)
                event.returnValue = false;

            if (x.length == 0 && prov2 == true)
                event.returnValue = false;

            if (x.split(".").length - 1 > 0 && prov2 == true)
                event.returnValue = false;
        }
        function calculateCost(index, price, weight_init) {
            {{--var obj = {{$orderInCart[0]}};--}}
            // console.log(obj);
            // alert(obj[index]['price']);
            // alert(document.getElementById('total'+index).innerHTML);
            // alert(document.getElementById('forOne'+index).innerHTML);
            // price is BD
            // weight_init na ed
            // document.getElementById('count'+index).value // kol-vo
            // document.getElementById('weight'+index).value // ves
            document.getElementById('total'+index).innerHTML = (document.getElementById('weight'+index).value/weight_init)*price*document.getElementById('count'+index).value;
            document.getElementById('forOne'+index).innerHTML = (document.getElementById('weight'+index).value/weight_init)*price;
        }
        function summaryTotalCost(){
            let summary = 0;
            for(let i = 0; i < document.getElementsByClassName('totalForProduct').length; i++)
                summary -= -(document.getElementsByClassName('totalForProduct')[i].innerHTML);
            document.getElementById('summaryTotal').innerHTML = summary;
        }
        function minDate() {
            let timeToOrder = new Date();
            timeToOrder.setDate(timeToOrder.getDate() + 1);
            let min = timeToOrder.getFullYear()+'-'+(timeToOrder.getMonth()+1)+'-'+timeToOrder.getDate();
            document.getElementById('minDate').setAttribute('min', min);
        }
        window.onload = function load(){
            summaryTotalCost();
            minDate()
        };
    </script>
    @else
    <div style="padding-top: 20px;">
        Корзина пуста! <a href="{{route('main')}}" style="text-decoration: underline;">Вернуться на главную за покупками</a>
    </div>
    @endif
</x-app-layout>

