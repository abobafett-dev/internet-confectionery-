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
    <div id="catalog_right">
        <div id="catalog_filter">
            <div style="width: 100%;">
                <h3 style="all: revert; margin: 5px;">Поиск по каталогу</h3><input placeholder="Введите ключевые слова" type="text" id="catalog_search">
            </div>
            <div>
                <h3 style="all: revert; margin: 5px;">Тип продукта</h3>
                @foreach($product_types as $type)
                    <label>
                    <div id="div_checkbox{{$type['id']}}">
                        <input onclick="catalog_textColor({{$type['id']}})" class="hidCheckBox" type="checkbox" name="type" value="{{$type['id']}}" id="checkbox{{$type['id']}}">
                        <label  for="checkbox{{$type['id']}}" id="label_checkbox{{$type['id']}}">
                            {{$type['name']}}
                        </label>
                    </div>
                    </label>
                @endforeach
            </div>
            <div style="width: 100%;">
                <h3 style="all: revert; margin: 5px;">Вес</h3>
                <input placeholder="Введите вес в кг" type="text" id="catalog_search">
                <div style="display: flex; justify-content: space-around;">
                    <label class="catalog-custom-radio"><input type="radio" name="weight" id="" style="margin-right: 15px;"><span>До</span></label>
                    <label class="catalog-custom-radio"><input type="radio" name="weight" id="" style="margin-right: 15px;"><span>От</span></label>
                </div>
            </div>
        </div>
    </div>
{{--    {{var_dump($product_types)}}--}}
    <div id="catalog_left">
        @foreach($productsWithTypesAndCount as $iter)
{{--            {{var_dump($iter)}}--}}
            <div class="catalog_products">
                <form action="{{route('addProductInCart', ['product'=>$iter['id']])}}" method="POST" style="width:15em;">
                    <div style="display: flex; justify-content: center; margin-bottom: 10px;"><h4 style="all: revert; margin: 5px;">{{$iter['name']}}</h4></div>
                    <div style="display: flex; justify-content: center;"><img src="{{$iter['photo']}}" alt="" style="width:15em;"></div>
                    <div style="display: flex; justify-content: center; padding: 10px; max-width: 100%;">{{$iter['description']}}
                        <div style="display: none;">{{$iter['price']}}
                            <input type="text" value="{{$product_types[$iter['id_product_type']]['weight_min']}}" hidden>
                            <input type="text" value="{{$product_types[$iter['id_product_type']]['weight_max']}}" hidden>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: center; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.2)"><button style="text-decoration: underline;">Добавить в корзину</button></div>
                    {{ csrf_field() }}
                </form>
            </div>
        @endforeach
    </div>
    <script>
        function catalog_textColor(index) {
            if(document.getElementById('checkbox'+index).checked) {
                document.getElementById('label_checkbox' + index).style = 'color: rgb(233 73 73 / 85%)';
            }
            else{
                document.getElementById('label_checkbox'+index).style = '';
            }
        }
    </script>
</x-app-layout>

