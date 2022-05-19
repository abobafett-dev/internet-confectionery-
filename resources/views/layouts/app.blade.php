@php
use Illuminate\Support\Facades\URL;
@endphp

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>«Мечтай»</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @if(\request()->getScheme() == "https")
            <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::secureAsset('css/normalize.css')}}" type="text/css">
            <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::secureAsset('css/app.css') }}" type="text/css">
            <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::secureAsset('css/style.css')}}" type="text/css">
        @endif

        @if(config('app.env') === 'local' || \request()->getScheme() === "http")
            <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::asset('css/normalize.css')}}" type="text/css">
            <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::asset('css/app.css') }}" type="text/css">
            <link rel="stylesheet" href="{{ Illuminate\Support\Facades\URL::asset('css/style.css')}}" type="text/css">
        @endif

        <!-- Logo -->
        <link rel="shortcut icon" href="{{\Illuminate\Support\Facades\Storage::url('logo/Logo.png')}}" type="image/png">

        <!-- Scripts -->
        @if(\request()->getScheme() == "https")
            <script src="{{ Illuminate\Support\Facades\URL::secureAsset('js/app.js') }}" defer></script>
        @endif

        @if(config('app.env') === 'local' || \request()->getScheme() === "http")
            <script src="{{ Illuminate\Support\Facades\URL::asset('js/app.js') }}" defer></script>
        @endif
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body class="font-sans antialiased" style="">
        <div class="min-h-screen bg-gray-100">
{{--        <div class="min-h-screen bg-gray-100" style="padding-bottom: 325px;">--}}
            <!-- Page Heading -->
            @include('layouts.navigation')

            <!-- Page Content -->
            <main style="">
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="bg-white border-gray-200">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <!-- Page Footer -->
{{--        <div style="margin-top: -325px;">--}}
        @include('layouts.footer')
{{--        </div>--}}
    </body>
</html>
