<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>«Мечтай»</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        @if(\request()->getScheme() === "https")
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

        @if(\request()->getScheme() === "https")
            <script src="{{ Illuminate\Support\Facades\URL::secureAsset('js/app.js') }}" defer></script>
        @endif

        @if(config('app.env') === 'local' || \request()->getScheme() === "http")
            <script src="{{ Illuminate\Support\Facades\URL::asset('js/app.js') }}" defer></script>
        @endif
        <style>
            html, body {
                height: 100%;
                scroll-behavior: smooth;
                min-width: 1260px;
                display: flex;
                flex-direction:column;
                justify-content: space-between;
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body>

        <div style="position:absolute; width: 100%">
            @include('layouts.navigation')
        </div>
        <div class="font-sans text-gray-900 antialiased" style="height: 100%; min-height: 820px;">
            {{ $slot }}
        </div>
        @include('layouts.footer')
    </body>
</html>
