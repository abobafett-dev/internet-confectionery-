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
    <div class="min-h-screen bg-gray-100" style="padding-bottom: 70px;">
        @include('layouts.navigation')
        {{--    вывод продуктов с количеством по типу--}}
        @if(count($productsWithTypesAndCount) > 0)
            <div style=" max-width: 50%">
                @foreach($productsWithTypesAndCount as $type => $productsOfType)
                    <div>
                        {{$type}}
                        @foreach($productsOfType as $productOfType)
                            <form action="{{route('addProductInCart', ['product'=>$productOfType['id']])}}" method="POST">
                                <div>
                                    {{$productOfType->name}}
                                    <div>
                                        <img src="{{$productOfType['photo']}}" alt="" style="width: 10em">
                                    </div>
                                    <button>Добавить в корзину</button>
                                    {{ csrf_field() }}
                                </div>
                            </form>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        {{--    вывод конструктора - по типу продукта --}}
        {{var_dump($componentsWithProductTypesForConstructor)}}
        @if(count($componentsWithProductTypesForConstructor) > 0)
            @foreach($componentsWithProductTypesForConstructor as $product_type => $componentsWithType)
                <form action="{{route('addProductFromConstructor')}}" method="POST">
                    <input type="text" value="{{$product_type}}" name="product_type" hidden>
                    <div style="background-color: #f39b9b;">
                        {{$product_type}}
                        <div>
{{--                        {{$componentsWithType[0]['weight_min']}}--}}
{{--                        {{$componentsWithType[0]['weight_initial']}}--}}
{{--                        {{$componentsWithType[0]['weight_max']}}--}}
                        </div>
                        @foreach($componentsWithType as $component_type => $components)

                            <div style="background-color: #ee5656; margin-left: 10px">
                                @if($component_type != "0")
                                    {{$component_type}}
                                    <select name="constructor_{{$components[array_key_first($components)]['id_component_type']}}" id="">
                                        <option value="" selected hidden></option>
                                    @foreach($components as $key => $component)
                                            <option value="{{$component['id']}}">{{$component['name']}}</option>
                                    @endforeach
                                    </select>
                                @endif
                            </div>
                        @endforeach
                        <button>Заказать!</button>
                        {{csrf_field()}}
                    </div>
                </form>
            @endforeach
        @endif
    </div>
    @include('layouts.footer')
</body>
</html>
