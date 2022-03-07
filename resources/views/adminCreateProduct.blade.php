<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
            {{ __('Добавление товара') }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}
            </div>
        @endif
    </x-slot>
    <div>
        <form action="">
            <h3>Добавление типа продукта</h3>
        </form>
    </div>
</x-app-layout>
{{var_dump($data['product_types'][0]['components'])}}
{{var_dump($data['product_types'])}}
