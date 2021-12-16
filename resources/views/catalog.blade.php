<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
            {{ __('Каталог') }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}
            </div>
        @endif
    </x-slot>

    <div style="padding: 0px 15px 15px 15px; width: 100%;">
    @foreach($productsWithTypesAndCount as $iter)
    <form action="{{route('addProductInCart', ['product'=>$iter['id']])}}" method="POST">
        {{$iter['name']}}
        <img src="{{$iter['photo']}}" alt="" style="width:10em;">
        <button>Добавить в корзину</button>
        {{ csrf_field() }}
    </form>
    @endforeach
    </div>

    {{var_dump($productsWithTypesAndCount)}}
{{--    {{$productsWithTypesAndCount[0]['id']}}--}}
</x-app-layout>

