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

    {{var_dump($productsWithTypesAndCount)}}
    {{$productsWithTypesAndCount[0]['id']}}
    @foreach($productsWithTypesAndCount as $iter)
    <form action="/" method="POST">
        <img src="{{$iter['photo']}}" alt="" style="width:10em;">
        <input type="number"  value="{{$iter['id']}}" name="id_product" disabled hidden>
        <button>Добавить в корзину</button>
        {{ csrf_field() }}
    </form>
    @endforeach

</x-app-layout>

