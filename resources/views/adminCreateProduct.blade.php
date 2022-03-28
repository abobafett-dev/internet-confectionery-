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
    <div style="padding: 15px;">
        <div style="display: grid; gap: 20px; grid-template-columns: auto auto auto;">
            {{--      Форма добавления товара      --}}
            <div style="display: grid;">
                <form action="" name="product" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data" class="formAddAdmin">
                    <h2 style="text-align: center; font-size: 20px;">Добавление <span style="text-decoration: underline;">продукта</span></h2>
                    <label for="prod_name">
                        <h3>Название</h3>
                    </label>
                    <input type="text" name="title" id="prod_name">
                    <label for="prod_descr">
                        <h3>Описание</h3>
                    </label>
                    <textarea type="text" name="description" id="prod_descr">

                    </textarea>
                    <label for="prod_img">
                        <h3>Фото</h3>
                    </label>
                    <input type="file" name="img" id="prod_img">
                    <label for="prod_price">
                        <h3>Стоимость</h3>
                    </label>
                    <input type="number" name="price" id="prod_price">
                    <label for="prod_coef">
                        <h3>Коэффициент бонусов</h3>
                    </label>
                    <input type="number" name="bonus" id="prod_coef">
                    <label for="type_prod">
                        <h3>Тип продукта</h3>
                    </label>
                    <select type="number" name="type_prod" id="type_prod" onchange="makeFormForAddProduct(this.value)">
                        <option value="" disabled selected style="display: none;">Выберете значение</option>
                        @foreach($data['product_types'] as $type)
                            <option value="{{$type['id']}}">{{$type['name']}}</option>
                        @endforeach
                    </select>
                    <div id="containerAddFormProduct">

                    </div>
                    <div style="text-align: center; margin: 20px auto 0px auto;">
                        <button type="submit" style="text-decoration: underline;">Добавить</button>
                    </div>
                </form>
            </div>
            {{--      Форма добавления типа продукта       --}}
            <div style="display: grid;">
                <form action="" name="type_product" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data" class="formAddAdmin">
                    <h2 style="text-align: center; font-size: 20px;">Добавление <span style="text-decoration: underline;">типа продукта</span></h2>
                    <label for="type_prod_name">
                        <h3>Название типа продукта</h3>
                    </label>
                    <input type="text" name="title_type_prod" id="type_prod_name">
                    <label for="type_prod_min_weight">
                        <h3>Минимальный вес(кг)</h3>
                    </label>
                    <input type="number" name="type_prod_min_weight" id="type_prod_min_weight">
                    <label for="type_prod_max_weight">
                        <h3>Максимальный вес(кг)</h3>
                    </label>
                    <input type="number" name="type_prod_max_weight" id="type_prod_max_weight">
                    <label for="type_prod_standard_weight">
                        <h3>Стандартный вес(кг)</h3>
                    </label>
                    <input type="number" name="type_prod_standard_weight" id="type_prod_standard_weight">
                    <label for="type_prod_type_comp">
                        <h3>Какие входят типы компанента?</h3>
                    </label>
                    <select type="number" name="type_prod_type_comp" id="type_prod_type_comp">
                        <option value="" disabled selected style="display: none;">Выберете значение</option>
{{--                        Сделать сюда список всех компанентов - добавить в компаненты поле "описание для кондитера" --}}
                    </select>
                    <label for="using_constr">
                        <h3>Используется в конструкторе?</h3>
                    </label>
                    <input type="checkbox" name="using_constr" id="using_constr" style="width: 16px;">
                    <div style="text-align: center; margin: 20px auto 0px auto;">
                        <button type="submit" style="text-decoration: underline;">Добавить</button>
                    </div>
                </form>
            </div>
            {{--      Форма добавления компонента      --}}
            <div style="display: grid;">
                <form action="" name="component" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data" class="formAddAdmin">
                    <h2 style="text-align: center; font-size: 20px;">Добавление <span style="text-decoration: underline;">компонента</span></h2>
                    <label for="comp_name">
                        <h3>Название</h3>
                    </label>
                    <input type="text" name="comp_name" id="comp_name">
                    <label for="comp_descr">
                        <h3>Описание</h3>
                    </label>
                    <textarea type="text" name="comp_description" id="comp_descr"></textarea>
                    <label for="comp_type_comp">
                        <h3>К какому компоненту относится</h3>
                    </label>
                    <select type="number" name="comp_type_comp" id="comp_prod_type_comp">
                        <option value="" disabled selected style="display: none;">Выберете значение</option>
                    </select>
                    <label for="comp_img">
                        <h3>Фото</h3>
                    </label>
                    <input type="file" name="comp" id="comp_img">
                    <label for="comp_price">
                        <h3>Стоимость</h3>
                    </label>
                    <input type="number" name="comp_price" id="comp_price">
                    <label for="comp_coef">
                        <h3>Коэффициент</h3>
                    </label>
                    <input type="number" name="comp_coef" id="comp_coef">
                    <div style="text-align: center; margin: 20px auto 0px auto;">
                        <button type="submit" style="text-decoration: underline;">Добавить</button>
                    </div>
                </form>
            </div>
            {{--      Форма добавления типа компонента      --}}
            <div style="display: grid;">
                <form action="" name="type_component" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data" class="formAddAdmin">
                    <h2 style="text-align: center; font-size: 20px;">Добавление <span style="text-decoration: underline;">типа компонента</span></h2>
                    <label for="type_comp_name">
                        <h3>Название типа компонента</h3>
                    </label>
                    <input type="text" name="title_comp_prod" id="type_comp_name">
                    <label for="type_comp_description_for_confectioner">
                        <h3>Описание для кондитера</h3>
                    </label>
                    <input type="text" name="type_comp_description_for_confectioner" id="type_comp_description_for_confectioner">
                    <div style="text-align: center; margin: 20px auto 0px auto;">
                        <button type="submit" style="text-decoration: underline;">Добавить</button>
                    </div>
                </form>
            </div>
            {{--      Форма добавления ингредиента      --}}
            <div style="display: grid;">
                <form action="" name="ingredient" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data" class="formAddAdmin">
                    <h2 style="text-align: center; font-size: 20px;">Добавление <span style="text-decoration: underline;">ингредиента</span></h2>
                    <label for="ingredient_name">
                        <h3>Название типа компонента</h3>
                    </label>
                    <input type="text" name="title_ingredient" id="ingredient_name">
                    <label for="ingredient_description">
                        <h3>Описание для кондитера</h3>
                    </label>
                    <input type="text" name="ingredient_description" id="ingredient_description">
                    <div style="text-align: center; margin: 20px auto 0px auto;">
                        <button type="submit" style="text-decoration: underline;">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    let dataJS = @json($data);
    console.log(dataJS);
    function makeFormForAddProduct(id) {
        let htmlIn = '';
            for(var key in dataJS['product_types'][id]['components']){
                htmlIn += '<label for="prod_component_'+key+'"><h3>'+key+'</h3></label><select type="number" name="component_'+dataJS['product_types'][id]['components'][key][0]['id_component_type']+'" id="prod_component_'+key+'">'
                for(var compsKey in dataJS['product_types'][id]['components'][key]){
                    htmlIn += '<option value="'+dataJS['product_types'][id]['components'][key][compsKey]['id']+'">'+dataJS['product_types'][id]['components'][key][compsKey]['name']+'</option>'
                }
                htmlIn += '</select>'
            }
        document.getElementById('containerAddFormProduct').innerHTML = htmlIn;
    }
</script>
