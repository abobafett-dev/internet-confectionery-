<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-right: -160px;">
            {{ __('Каталог товаров') }}
        </h2>
        @if(session()->exists('was_updated'))
            <div
                style="padding: 0px 10px; margin-left:auto; text-align: center; background-color: #9df99d; border-radius: 10px;">{{session('was_updated')}}
            </div>
        @endif
    </x-slot>
    <div id="catalog_right">
        <a href="{{route('adminProductsAdd')}}" style="text-decoration: underline;">
            <div
                style="text-align: center; margin: auto auto 10px auto; box-shadow: 0px 0px 5px rgb(0 0 0 / 30%); padding: 10px 0px;">
                Добавить новый товар
            </div>
        </a>
        <div id="catalog_filter">
            <div style="width: 100%;">
                <h3 style="all: revert; margin: 5px;">Поиск по каталогу</h3><input placeholder="Введите ключевые слова"
                                                                                   type="text" id="catalog_search">
            </div>
            <div>
                <h3 style="all: revert; margin: 5px;">Тип продукта</h3>
                @foreach($product_types as $type)
                    <label>
                        <div id="div_checkbox{{$type['id']}}">
                            <input onclick="catalog_textColor({{$type['id']}})" class="hidCheckBox" type="checkbox"
                                   name="type" value="{{$type['id']}}" id="checkbox{{$type['id']}}">
                            <label for="checkbox{{$type['id']}}" id="label_checkbox{{$type['id']}}">
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
                    <label class="catalog-custom-radio"><input type="radio" name="weight" id=""
                                                               style="margin-right: 15px;"><span>До</span></label>
                    <label class="catalog-custom-radio"><input type="radio" name="weight" id=""
                                                               style="margin-right: 15px;"><span>От</span></label>
                </div>
            </div>
        </div>
    </div>
    <div id="catalog_left">
        @foreach($productsWithTypesAndCount as $iter)
            <div class="catalog_products" style="padding-bottom: 0px; overflow: hidden;">
                <div style="display: flex; justify-content: center; margin-bottom: 10px;"><h4
                        style="all: revert; margin: 5px;">{{$iter['name']}}</h4></div>
                <div style="display: flex; justify-content: center;"><img src="{{$iter['photo']}}" alt=""
                                                                          style="width:15em; height: 15em;"></div>
                <div style="display: none; max-width: 100%;">{{$iter['description']}}
                    <div style="display: none;">{{$iter['price']}}
                        <input type="text" value="{{$product_types[$iter['id_product_type']]['weight_min']}}" hidden>
                        <input type="text" value="{{$product_types[$iter['id_product_type']]['weight_max']}}" hidden>
                    </div>
                </div>
                @if(!$iter['isActive'])
                    <div id="text_{{$iter['id']}}"
                        style="padding: 10px 0px; border-top: 1px solid rgba(0,0,0,0.2);text-align: center; background-color:#f34d4d99; color: #028300;">

                            <button onclick="changeStatus({{$iter['id']}})">Восстановить к продаже</button>
                            {{ csrf_field() }}

                    </div>
                @else
                    <div id="text_{{$iter['id']}}"
                        style="padding: 10px 0px; border-top: 1px solid rgba(0,0,0,0.2); text-align: center; background-color: #7ed57e; color: red;">
                            <button onclick="changeStatus({{$iter['id']}})">Убрать из продажи</button>
                            {{ csrf_field() }}
                    </div>

                @endif
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
                        document.getElementById('text_'+id_prod).style.color = 'red';
                        document.getElementById('text_'+id_prod).innerHTML = '<button onclick="changeStatus('+id_prod+')">Убрать из продажи</button>{{ csrf_field() }}';
                        console.log('first')
                    }
                    else{
                        document.getElementById('text_'+id_prod).style.backgroundColor = '#f34d4d99';
                        document.getElementById('text_'+id_prod).style.color = '#028300';
                        document.getElementById('text_'+id_prod).innerHTML = '<button onclick="changeStatus('+id_prod+')">Восстановить к продаже</button>{{ csrf_field() }}';
                        console.log('second')
                    }
                },
                error: function () {
                    alert('Невозможно изменить статус товара.');
                }
            });
        }
    </script>
</x-app-layout>
