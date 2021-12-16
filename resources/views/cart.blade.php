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
    <form action="" name="cart" method="POST" onchange="summaryTotalCost()">
        <div style="padding: 0px 15px 15px 15px; width: 79%; display: inline-block;">
            @if(isset($orderInCart))
                @foreach($orderInCart[0]['products'] as $key => $product)
                    <div class="cart_product_block" id="div{{$product['id']}}">
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
                                    <button type="button" class="button_delete" onclick="deleteFromCart({{$product['id']}}@isset($orderInCart[0]['id']),{{$orderInCart[0]['id']}}@endisset)">Удалить
                                        из корзины
                                    </button>
                                </div>
                            </div>
                            <div class="cart_product_counts"
                                 onchange="calculateCost({{$key}}, {{$product['price']}}, {{$product['product_type']['weight_initial']}})">
                                <div>
                                    Количество
                                    <div class="changes_position">
                                        <button type="button"
                                                onclick="one_count('count{{$key}}', -1, {{$loop->index}})">–
                                        </button>
                                        <input onkeypress="numbersOnly()" name="count{{$key}}" id="count{{$key}}"
                                               value="1">
                                        <button type="button" onclick="one_count('count{{$key}}', 1, {{$loop->index}})">
                                            +
                                        </button>
                                    </div>
                                </div>
                                @if($product['product_type']['weight_min'] != $product['product_type']['weight_max'])
                                    <div>
                                        Вес
                                        <div class="changes_position">
                                            <button type="button"
                                                    onclick="one_weight('weight{{$key}}', -0.5, {{$loop->index}})">–
                                            </button>
                                            <input onkeyup="range(this.value, 'weight{{$key}}')"
                                                   onkeypress="doubleOnly(this.value)" name="weight{{$key}}"
                                                   id="weight{{$key}}"
                                                   value="{{$product['product_type']['weight_initial']}}">
                                            <button type="button"
                                                    onclick="one_weight('weight{{$key}}', 0.5, {{$loop->index}})">+
                                            </button>
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
        </div>
        <div style="margin-top: 20px; padding: 0px 15px 15px 15px; width: 21%; display: inline-block; float: right;">
            <div class="menuTotal">
                <div style="display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <ul>
                        <li id="itog">Итого: <span id="summaryTotal"></span>₽</li>
                        <li id="pickTime">Выбрать дату и время<input onchange="dropIntervals(this.value)" type="date" name="" id="minDate"></li>
                    </ul>
                </div>
            </div>
            <div class="menuTotal" style="margin-top: 20px;">
                <button id="buttonOrder">
                    Оформить заказ!
                </button>
            </div>
            @if(!Auth::user())
                <div id="regInForm">
                @csrf
                <!-- Name -->
                    <div class="mt-4 hiddenOnClick">
                        <x-label for="name" :value="__('Имя')"/>

                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                                 required/>
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-label for="email" :value="__('Email')"/>

                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                                 required/>
                    </div>

                    <!-- Phone -->
                    <div class="mt-4 hiddenOnClick">
                        <x-label for="phone" value="{{ __('Номер телефона') }}"/>
                        <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')"
                                 placeholder="88005553535"/>
                    </div>

                    <!-- Password -->
                    <div class="mt-4 hiddenOnClick">
                        <x-label for="password" :value="__('Пароль')"/>

                        <x-input id="password" class="block mt-1 w-full"
                                 type="password"
                                 name="password"
                                 required autocomplete="new-password"/>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4 hiddenOnClick">
                        <x-label for="password_confirmation" :value="__('Подверждение пароля')"/>

                        <x-input id="password_confirmation" class="block mt-1 w-full"
                                 type="password"
                                 name="password_confirmation" required/>
                    </div>

                    <div class="flex items-center justify-center mt-4 hiddenOnClick">
                        <button type="button" onclick="LogInCart()"
                                class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Уже зарегистрированы?') }}
                        </button>
                    </div>
                    <div class="flex items-center justify-center mt-4 hidden" id="hiddenNon">
                        <button type="button" onclick="RegInCart()"
                                class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Нет учётной записи?') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function deleteFromCart(id_prod, id_ord = -1) {
            $.ajax({
                url: '{{route('deleteProductInCartAjax')}}',
                type: "POST",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {order: id_ord, product: id_prod},
                success: function (data) {
                    if (data == "ok") {
                        if( document.getElementsByClassName('cart_product_block').length > 1) {
                            $('#div' + id_prod).remove();
                            summaryTotalCost();
                        }
                        else {
                            document.getElementsByTagName('form')[0].parentNode.innerHTML = '<div style="padding: 20px 15px 15px 15px;">Корзина пуста! <a href="{{route('main')}}" style="text-decoration: underline;">Вернуться на главную за покупками</a></div>';
                        }
                    }
                },
                error: function () {
                    alert('Невозможно удалить продукт, перезагрузите страницу');
                }
            });
        }
        function dropIntervals(dateForIntervals){
            $.ajax({
                url: '{{route('cartIntervals')}}',
                type: "POST",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {dateForIntervals: dateForIntervals},
                success: function (data) {
                    console.clear();
                    console.log(data[0]);
                    console.log(data);
                },
                error: function () {
                    alert('Невозможно вывести интервалы, перезагрузите страницу');
                }
            });
        }
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
            document.getElementById('total' + index).innerHTML = (document.getElementById('weight' + index).value / weight_init) * price * document.getElementById('count' + index).value;
            document.getElementById('forOne' + index).innerHTML = (document.getElementById('weight' + index).value / weight_init) * price;
        }

        function summaryTotalCost() {
            let summary = 0;
            for (let i = 0; i < document.getElementsByClassName('totalForProduct').length; i++)
                summary -= -(document.getElementsByClassName('totalForProduct')[i].innerHTML);
            document.getElementById('summaryTotal').innerHTML = summary;
        }

        function minDate() {
            let timeToOrder = new Date();
            timeToOrder.setDate(timeToOrder.getDate() + 1);
            let min = timeToOrder.getFullYear() + '-' + (timeToOrder.getMonth() + 1) + '-' + timeToOrder.getDate();
            document.getElementById('minDate').setAttribute('min', min);
        }

        window.onload = function load() {
            summaryTotalCost();
            minDate()
        };

        function LogInCart() {
            document.getElementById('hiddenNon').setAttribute('style', 'display:block');
            for(let i = 0; i < document.getElementsByClassName('hiddenOnClick').length; i++)
                document.getElementsByClassName('hiddenOnClick')[i].setAttribute('style', 'display:none');
        }

        function RegInCart() {
            document.getElementById('hiddenNon').setAttribute('style', 'display:none');
            for(let i = 0; i < document.getElementsByClassName('hiddenOnClick').length; i++)
                document.getElementsByClassName('hiddenOnClick')[i].setAttribute('style', 'display:block');
        }
    </script>
    @else
    <div style="padding-top: 20px;">
        Корзина пуста! <a href="{{route('main')}}" style="text-decoration: underline;">Вернуться на главную за покупками</a>
    </div>
    @endif
</x-app-layout>

