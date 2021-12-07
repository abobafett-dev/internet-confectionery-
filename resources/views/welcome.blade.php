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

</head>
<body class="antialiased">
<div
    class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="text-sm text-gray-700 dark:text-gray-500 underline">Личный кабинет</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline">Войти</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Зарегестрироваться</a>
                @endif
            @endauth
        </div>
    @endif
</div>


{{--    вывод продуктов с количеством по типу--}}
@if(count($productsWithTypesAndCount) > 0)
    <div>
        @foreach($productsWithTypesAndCount as $type => $productsOfType)
            <div>
                {{$type}}
                @foreach($productsOfType as $productOfType)
                    <div>
                        {{$productOfType->name}}
                        <div>
                            {{$productOfType}}
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
        <div style="background-color: #f39b9b">
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
                                {{var_dump($component)}}
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
@endif

</body>
</html>
