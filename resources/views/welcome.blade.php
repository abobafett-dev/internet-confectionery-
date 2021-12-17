<!DOCTYPE html>

<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>«Мечтай»</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css')}}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css')}}">

    <!-- Logo -->
    <link rel="shortcut icon" href="/storage/logo/Logo.png" type="image/png">

    <script src="{{ asset('js/app.js') }}" defer></script>

</head>
<body class="antialiased">

    <div class="" style="">
        {{--    вывод продуктов с количеством по типу--}}
        @include('layouts.navigation')
        <div class="white">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
{{--                {{var_dump($productsWithTypesAndCount['торт_тест'])}}--}}
            @if(count($productsWithTypesAndCount) > 0)
                <div >
                    @foreach($productsWithTypesAndCount as $type => $productsOfType)
                        <div style="">
                            <h3 style="all:revert; margin: 0px 0px 20px 0px; text-align: center;"> Топ-5 из категории «{{$type}}»</h3>
                            @foreach($productsOfType as $productOfType)
                                <form action="{{route('addProductInCart', ['product'=>$productOfType['id']])}}" method="POST" style=" max-width: 13em; padding: 1em; text-align: center; border: 1px solid rgba(151,149,149,0.29); border-radius: 5px;">
                                    <h4 style="all:revert; margin: 0px; text-align: center; margin-bottom: 10px;">{{$productOfType->name}}</h4>
                                    <div style="margin: auto;">
                                        <img style="border-radius: 5px;" src="{{$productOfType['photo']}}" alt="" style="width: 10em">
                                    </div>
                                    <button>Добавить в корзину</button>
                                    {{ csrf_field() }}
                                </form>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
            </div>
        </div>
        <div class="pink">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(count($componentsWithProductTypesForConstructor) > 0)
                    <div style="display: flex; justify-content: space-around; flex-wrap: wrap;">
                    @foreach($componentsWithProductTypesForConstructor as $product_type => $componentsWithType)
                    <div style="border: 1px solid black; padding: 5px; margin: 10px 0px" id="{{$product_type}}" class="main_cursor_hover"><h4 style="all:revert; margin: 0px; text-align: center;">{{$product_type}}</h4></div>
                    @endforeach
                    </div>
                    @foreach($componentsWithProductTypesForConstructor as $product_type => $componentsWithType)
                        <form action="{{route('addProductFromConstructor')}}" method="POST">
                            <input type="text" value="{{$product_type}}" name="product_type" hidden>
                            <div style="margin: auto; width: 35%;">
                                @foreach($componentsWithType as $component_type => $components)
                                    <div style="margin-left: 10px; display: flex; justify-content: space-between; margin: 5px 0px; align-items: center;" >
                                        @if($component_type != "0")
                                            <div>{{$component_type}}</div>
                                            <div>
                                                <select style="min-width: 180px;" name="constructor_{{$components[array_key_first($components)]['id_component_type']}}" id="">
                                                    <option value="" selected hidden></option>
                                                @foreach($components as $key => $component)
                                                    <option value="{{$component['id']}}">{{$component['name']}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                    <div  style="margin-left: 10px; display: flex; justify-content: center; margin: 5px 0px; align-items: center;">
                                        <button>
                                            <h4 style="all:revert; margin: 0px; text-align: center; margin-top: 10px;">Заказать!</h4>
                                        </button>
                                    </div>
                                {{csrf_field()}}
                            </div>
                        </form>
                    @endforeach
                @endif
            </div>
        </div>
        {{--    вывод конструктора - по типу продукта --}}
{{--        {{var_dump($componentsWithProductTypesForConstructor)}}--}}
{{--        @if(count($componentsWithProductTypesForConstructor) > 0)--}}
{{--            @foreach($componentsWithProductTypesForConstructor as $product_type => $componentsWithType)--}}
{{--                <form action="{{route('addProductFromConstructor')}}" method="POST">--}}
{{--                    <input type="text" value="{{$product_type}}" name="product_type" hidden>--}}
{{--                    <div style="background-color: #f39b9b;">--}}
{{--                        {{$product_type}}--}}
{{--                        <div>--}}
{{--                        {{$componentsWithType[0]['weight_min']}}--}}
{{--                        {{$componentsWithType[0]['weight_initial']}}--}}
{{--                        {{$componentsWithType[0]['weight_max']}}--}}
{{--                        </div>--}}
{{--                        @foreach($componentsWithType as $component_type => $components)--}}

{{--                            <div style="background-color: #ee5656; margin-left: 10px">--}}
{{--                                @if($component_type != "0")--}}
{{--                                    {{$component_type}}--}}
{{--                                    <select name="constructor_{{$components[array_key_first($components)]['id_component_type']}}" id="">--}}
{{--                                        <option value="" selected hidden></option>--}}
{{--                                    @foreach($components as $key => $component)--}}
{{--                                            <option value="{{$component['id']}}">{{$component['name']}}</option>--}}
{{--                                    @endforeach--}}
{{--                                    </select>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                        <button>Заказать!</button>--}}
{{--                        {{csrf_field()}}--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            @endforeach--}}
{{--        @endif--}}
    </div>
    @include('layouts.footer')


{{--    <div class="white">--}}
{{--        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="pink">--}}
{{--        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"></div>--}}
{{--    </div>--}}
</body>
</html>
