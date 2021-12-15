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
    {{--    <form action="" name="cart" method="POST">--}}
    <div style="padding: 0px 15px 15px 15px; width: 79%; display: inline-block;">
        @isset($orderInCart)
            @foreach($orderInCart[0]['products'] as $key => $product)
                <div class="cart_product_block">
                    <div class="width0"><input type="checkbox" name="" id=""></div>
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
                                <form action="{{route('deleteProductInCart',['product'=>$product['id']])}}"
                                      method="POST" class="delete">
                                    @isset($orderInCart[0]['id'])
                                        <input name="order" value="{{$orderInCart[0]['id']}}" hidden>
                                    @endisset
                                    <button class="button_delete">Удалить
                                        из корзины
                                    </button>
                                    {{csrf_field()}}
                                </form>
                            </div>
                        </div>
                        <div class="cart_product_counts">
                            Количество
                            <div class="changes_position">
                                <button type="button" onclick="one_count('count{{$key}}', -1)">-</button>
                                <input onkeypress="numbersOnly()" name="count{{$key}}" id="count{{$key}}" value="1">
                                <button type="button" onclick="one_count('count{{$key}}', 1)">+</button>
                            </div>
                            @if($product['product_type']['weight_min'] != $product['product_type']['weight_max'])
                                Вес
                                <div class="changes_position">
                                    <button type="button" onclick="one_weight('weight{{$key}}', -0.5)">-</button>
                                    <input onkeyup="range(this.value, 'weight{{$key}}')"
                                           onkeypress="doubleOnly(this.value)" name="weight{{$key}}"
                                           id="weight{{$key}}"
                                           value="{{$product['product_type']['weight_initial']}}">
                                    <button type="button" onclick="one_weight('weight{{$key}}', 0.5)">+</button>
                                </div>
                            @endif
                        </div>
                        <div class="cart_product_price">
                            <div>
                                    <span
                                        class="total">{{$product['product_type']['weight_initial']*$product['price']}}</span>₽
                            </div>
                            <div>
                                    <span
                                        class="forOne">{{$product['product_type']['weight_initial']*$product['price']}}</span>₽/шт
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {{var_dump($orderInCart[0]['products'])}}

            {{var_dump($orderInCart, $schedule_interval, $schedule_standard, $schedule_update_all, $orders_all)}}

    </div>
    <div style="padding: 15px; width: 20%; display: inline-block; float: right;">
        <div>
            itogo
        </div>
    </div>
    {{--    </form>--}}
        <script>
            function one_weight(index, difference) {
                document.getElementById(index).setAttribute('value', document.getElementById(index).value);
                if (document.getElementById(index).getAttribute('value') - (-difference) < {{$product['product_type']['weight_min']}}) {
                    alert('Минимальный вес продутка {{$product['product_type']['weight_min']}}')
                } else if (document.getElementById(index).getAttribute('value') - (-difference) > {{$product['product_type']['weight_max']}}) {
                    alert('Максимальный вес продутка {{$product['product_type']['weight_max']}}')
                } else {
                    document.getElementById(index).value -= (-difference);
                    document.getElementById(index).setAttribute('value', document.getElementById(index).value - (-difference));
                }
            }

            function one_count(index, difference) {
                document.getElementById(index).setAttribute('value', document.getElementById(index).value);
                if (document.getElementById(index).getAttribute('value') - (-difference) < 1) {
                    alert('Минимальное количество 1')
                } else {
                    document.getElementById(index).value -= (-difference);
                    document.getElementById(index).setAttribute('value', document.getElementById(index).value - (-difference));
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
        </script>
    @endisset
</x-app-layout>

