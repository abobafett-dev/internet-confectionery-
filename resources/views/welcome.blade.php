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


    @if(count($productsWithTypesAndCount) > 0)
        <div class="abcd">
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

</div>
</body>
</html>
