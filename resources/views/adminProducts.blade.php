<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
            {{ __('Ассортимент') }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}
            </div>
        @endif
    </x-slot>
{{--    <div id="catalog_right">--}}
{{--        <a href="{{route('adminProductsAdd')}}" style="text-decoration: underline;">--}}
{{--            <div--}}
{{--                style="text-align: center; margin: auto auto 10px auto; box-shadow: 0px 0px 5px rgb(0 0 0 / 30%); padding: 10px 0px;">--}}
{{--                Добавить новый товар--}}
{{--            </div>--}}
{{--        </a>--}}
{{--        <div id="catalog_filter">--}}
{{--            <div style="width: 100%;">--}}
{{--                <h3 style="all: revert; margin: 5px;">Поиск по каталогу</h3><input placeholder="Введите ключевые слова"--}}
{{--                                                                                   type="text" id="catalog_search">--}}
{{--            </div>--}}
{{--            <div>--}}
{{--                <h3 style="all: revert; margin: 5px;">Тип продукта</h3>--}}
{{--                @foreach($product_types as $type)--}}
{{--                    <label>--}}
{{--                        <div id="div_checkbox{{$type['id']}}">--}}
{{--                            <input onclick="catalog_textColor({{$type['id']}})" class="hidCheckBox" type="checkbox"--}}
{{--                                   name="type" value="{{$type['id']}}" id="checkbox{{$type['id']}}">--}}
{{--                            <label for="checkbox{{$type['id']}}" id="label_checkbox{{$type['id']}}">--}}
{{--                                {{$type['name']}}--}}
{{--                            </label>--}}
{{--                        </div>--}}
{{--                    </label>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            <div style="width: 100%;">--}}
{{--                <h3 style="all: revert; margin: 5px;">Вес</h3>--}}
{{--                <input placeholder="Введите вес в кг" type="text" id="catalog_search">--}}
{{--                <div style="display: flex; justify-content: space-around;">--}}
{{--                    <label class="catalog-custom-radio"><input type="radio" name="weight" id=""--}}
{{--                                                               style="margin-right: 15px;"><span>До</span></label>--}}
{{--                    <label class="catalog-custom-radio"><input type="radio" name="weight" id=""--}}
{{--                                                               style="margin-right: 15px;"><span>От</span></label>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div id="catalog_left">
        <a href="{{route('adminProductsAdd')}}" style="text-decoration: underline;">
            <div class="catalog_products" style="padding-bottom: 0px; overflow: hidden;" id="assortAddNew">
                <div style="display: flex; justify-content: center; padding: 10px 0;"><h4
                        style="all: revert; margin: 5px;">Новый товар</h4>
                </div>
                <div style="display: flex; justify-content: center;width:15em; height: 15em; align-items: center;">
                    <img src="{{\Illuminate\Support\Facades\Storage::url("logo/iconAdd.png")}}" alt="" style="width:10em; height: 10em;">
                </div>
                <div style="display: flex; justify-content: center; margin-bottom: 10px;"><h4
                        style="all: revert; margin: 5px;">Добавить</h4>
                </div>
            </div>
        </a>
        @foreach($productsWithTypesAndCount as $iter)
            <div class="catalog_products" style="padding-bottom: 0px; overflow: hidden;">

                <div style="display: flex; justify-content: center; padding: 10px 0; @if(!$iter['isActive'])background-color:#f34d4d99; @else background-color: #7ed57e; @endif"  id="header_{{$iter['id']}}"><h4
                        style="all: revert; margin: 5px;">{{$iter['name']}}</h4></div>
                <div style="display: flex; justify-content: center;"><img src="{{$iter['photo']}}" alt=""
                                                                          style="width:15em; height: 15em;"></div>

                <div style="display: none; max-width: 100%;">{{$iter['description']}}
                    <div style="display: none;">{{$iter['price']}}
                        <input type="text" value="{{$product_types[$iter['id_product_type']]['weight_min']}}" hidden>
                        <input type="text" value="{{$product_types[$iter['id_product_type']]['weight_max']}}" hidden>
                    </div>
                </div>

                    <div id="text_{{$iter['id']}}"
                        style="padding: 10px 0px; text-align: center; @if(!$iter['isActive']) background-color:#f34d4d99; @else  background-color: #7ed57e; @endif">

                            <button onclick="changeStatus({{$iter['id']}})">Восстановить к продаже</button>
                            {{ csrf_field() }}

                    </div>
            </div>
        @endforeach
    </div>
    <script>
        function catalog_textColor(index) {
            if (document.getElementById('checkbox' + index).checked) {
                document.getElementById('label_checkbox' + index).style = 'color: rgb(233 73 73 / 85%)';
            } else {
                document.getElementById('label_checkbox' + index).style = '';
            }
        }

        function changeStatus(id_prod) {
            $.ajax({
                url: '{{route('changeActiveAjax')}}',
                type: "POST",
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                data: {product: id_prod},
                success: function () {
                    console.log(document.getElementById('text_'+id_prod).style.backgroundColor)
                    if(document.getElementById('text_'+id_prod).style.backgroundColor == 'rgba(243, 77, 77, 0.6)'){
                        document.getElementById('text_'+id_prod).style.backgroundColor = '#7ed57e';
                        document.getElementById('header_'+id_prod).style.backgroundColor = '#7ed57e';
                        document.getElementById('text_'+id_prod).innerHTML = '<button onclick="changeStatus('+id_prod+')">Убрать из продажи</button>{{ csrf_field() }}';
                    }
                    else{
                        document.getElementById('text_'+id_prod).style.backgroundColor = '#f34d4d99';
                        document.getElementById('header_'+id_prod).style.backgroundColor = '#f34d4d99';
                        document.getElementById('text_'+id_prod).innerHTML = '<button onclick="changeStatus('+id_prod+')">Восстановить к продаже</button>{{ csrf_field() }}';
                    }
                },
                error: function () {
                    alert('Невозможно изменить статус товара.');
                }
            });
        }
    </script>
</x-app-layout>
