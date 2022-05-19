@php use Illuminate\Support\Facades\Storage; @endphp
<!DOCTYPE html>

<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>«Мечтай» {{config('app.env')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

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
    <link rel="shortcut icon" href="/storage/logo/Logo.png" type="image/png">

    @if(\request()->getScheme() === "https")
        <script src="{{ Illuminate\Support\Facades\URL::secureAsset('js/app.js') }}" defer></script>
    @endif

    @if(config('app.env') === 'local' || \request()->getScheme() === "http")
        <script src="{{ Illuminate\Support\Facades\URL::asset('js/app.js') }}" defer></script>
    @endif
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
<body class="antialiased">

<div class="" style="">
    @include('layouts.navigation')
    {{--    вывод продуктов с количеством по типу--}}

    <div style="padding: 20px 40px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{--                {{var_dump($productsWithTypesAndCount['торт_тест'])}}--}}
            @if(count($productsWithTypesAndCount) > 0)
                @foreach($productsWithTypesAndCount as $type => $productsOfType)
                    <div style="margin:40px 0px;">
                        <h3 style="all:revert; margin: 0px 0px 20px 0px; text-align: center;">
                            Топ-@if(count($productsOfType) < 5){{count($productsOfType)}} @else{{5}}@endif из категории
                            «{{$type}}»</h3>
                        <div
                            style="display: flex; min-width: 100%; justify-content: space-evenly; box-shadow: 0px 0px 10px #0000004a; padding:20px 10px;">
                            @for($i = 0; ($i < 5) && ($i < count($productsOfType)); $i++)
                                <form action="{{route('addProductInCart', ['product'=>$productsOfType[$i]['id']])}}"
                                      method="POST"
                                      style=" max-width: 13em; padding: 1em; text-align: center; border: 1px solid rgba(151,149,149,0.29); border-radius: 5px;">
                                    <h4 style="all:revert; margin: 0px; text-align: center; margin-bottom: 10px;">{{$productsOfType[$i]->name}}</h4>
                                    <div style="margin: auto;">
                                        <img style="border-radius: 5px; width: 11em; height: 11em;"
                                             src="{{$productsOfType[$i]['photo']}}" alt="" style="width: 10em">
                                    </div>
                                    <button style="text-decoration: underline; margin-top: 10px;">Добавить в корзину
                                    </button>
                                    {{ csrf_field() }}
                                </form>
                            @endfor
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div>

    <div  style="background: url({{Storage::url("logo/backgroundImgMainConstructor.png")}}); background-repeat: no-repeat; background-size: cover;">
        <div class="pink" style="background: rgba(0,0,0,.75); width: 100%; height: 100%; color: white;">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div>
                <h3 style="all:revert; margin: 0px 0px 20px 0px; text-align: center;">Собери свой десерт!</h3>
            </div>
            @if(count($componentsWithProductTypesForConstructor) > 0)
                <div style="display: flex; justify-content: space-evenly; flex-wrap: wrap;">
                    @foreach($componentsWithProductTypesForConstructor as $product_type => $componentsWithType)
                        <div style="border: 1px solid white; padding: 5px; margin: 10px 0px 25px 0px; color: black; background-color: white;"
                             id="{{$product_type}}" class="main_cursor_hover"
                             onclick="hiddenFormConstructor(this,{{$loop->index}})"><h4
                                style="all:revert; margin: 0px; text-align: center;">{{$product_type}}</h4></div>
                    @endforeach
                </div>
                @foreach($componentsWithProductTypesForConstructor as $product_type => $componentsWithType)
                    <form action="{{route('addProductFromConstructor')}}" id="{{$loop->index}}" class="formConstructor"
                          method="POST">
                        <input type="text" value="{{$product_type}}" name="product_type" hidden>
                        <div style="margin: auto; width: 35%;">
                            @foreach($componentsWithType as $component_type => $components)
                                @if($component_type != "0")
                                    <div
                                        style="margin-left: 10px; display: flex; justify-content: space-between; margin: 5px 0px; align-items: center; border-bottom: 1px solid white;">
                                        <div style="font-weight: bold;">{{$component_type}}</div>
                                        <div>
                                            <select style="width: auto; color: black;" class="selectWidth"
                                                    name="constructor_{{$components[array_key_first($components)]['id_component_type']}}"
                                                    id="">
                                                <option value="" selected hidden></option>
                                                @foreach($components as $key => $component)
                                                    <option value="{{$component['id']}}">{{$component['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                            @endforeach
                            <div
                                style=" display: flex; justify-content: center; margin: 40px 0px 0px 0px; align-items: center;">
                                <button style="border: 1px solid white; padding: 10px; border-radius: 10px; background: white; color: black;">
                                    <h4 style="all:revert; margin: 0px; text-align: center;">Заказать!</h4>
                                </button>
                            </div>
                            {{csrf_field()}}
                        </div>
                    </form>
                @endforeach
            @endif
        </div>
    </div>

</div>
@include('layouts.footer')

</body>
<script>
    function hiddenFormConstructor(block, index) {
        for (let i = 0; i < document.getElementsByClassName('formConstructor').length; i++)
            document.getElementsByClassName('formConstructor')[i].style.display = 'none';
        document.getElementById(index).style.display = 'block';
        for (let i = 0; i < document.getElementsByClassName('main_cursor_hover').length; i++){
            document.getElementsByClassName('main_cursor_hover')[i].style.backgroundColor = 'white';
            document.getElementsByClassName('main_cursor_hover')[i].style.color = 'black';
        }
        block.style.backgroundColor = 'rgb(222 151 163)';
        block.style.color = 'white';
    }

    document.getElementsByClassName('main_cursor_hover')[0].click();

    var maxWidth = 0;
    for (let i = 0; i < document.getElementsByClassName('selectWidth').length; i++)
        if(document.getElementsByClassName('selectWidth')[i].offsetWidth > maxWidth){
            maxWidth = document.getElementsByClassName('selectWidth')[i].offsetWidth
            console.log(document.getElementsByClassName('selectWidth')[i].offsetWidth)
        }
    for (let i = 0; i < document.getElementsByClassName('selectWidth').length; i++)
        document.getElementsByClassName('selectWidth')[i].style.width = maxWidth+"px"

    @if(session()->exists('errorConstructor'))
    alert("{{session('errorConstructor')}}");
    scrollTo(0, 900);
    @endif
</script>
</html>
