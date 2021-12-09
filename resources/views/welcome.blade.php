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
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        {{--    вывод продуктов с количеством по типу--}}
        @if(count($productsWithTypesAndCount) > 0)
            <div style=" max-width: 50%">
                @foreach($productsWithTypesAndCount as $type => $productsOfType)
                    <div>
                        {{$type}}
                        @foreach($productsOfType as $productOfType)
                            <div>
                                {{$productOfType->name}}
                                <div>
                                    <img src="{{$productOfType->logo}}" alt="">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif

        {{--    вывод конструктора - по типу продукта --}}
        @if(count($componentsWithProductTypesForConstructor) > 0)
            @foreach($componentsWithProductTypesForConstructor as $product_type => $componentsWithType)
                <div style="background-color: #f39b9b;">
                    {{$product_type}}
                    <div>
                    {{$componentsWithType[0]['weight_min']}}
                    {{$componentsWithType[0]['weight_initial']}}
                    {{$componentsWithType[0]['weight_max']}}
                    </div>
                    @foreach($componentsWithType as $component_type => $components)
                        <div style="background-color: #ee5656; margin-left: 10px">
                            @if($component_type != "0")
                                {{$component_type}}
                                @foreach($components as $component)
                                    <div style="background-color: #fa2121; margin-left: 20px">
                                        {{$component['name']}}
                                        <br>{{var_dump($component)}}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>
    @include('layouts.footer')
</body>
</html>
