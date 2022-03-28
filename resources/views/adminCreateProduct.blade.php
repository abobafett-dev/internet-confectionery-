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
            <div style="display: grid;">
                <form action="" name="product" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data" class="formAddAdmin">
                    <h2 style="text-align: center; font-size: 20px;">Добавление продукта</h2>
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
                        <option value="" disabled selected style="display: none;"></option>
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
            <div style="display: grid;">
                <form action="" name="product" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data">
                    <h2 style="text-align: center; font-size: 20px;">Добавление продукта</h2>
                    <label for="prod_name">
                        <h3>Название</h3>
                    </label>
                    <input type="text" name="title" id="prod_name">
                    <label for="prod_descr">
                        <h3>Описание</h3>
                    </label>
                    <input type="text" name="description" id="prod_descr">
                    <label for="prod_img">
                        <h3>Фото</h3>
                    </label>
                    <input type="file" name="img" id="prod_img">
                    <label for="prod_price">
                        <h3>Стоимость</h3>
                    </label>
                    <input type="number" name="img" id="prod_price">
                    <label for="prod_coef">
                        <h3>Коэффициент бонусов</h3>
                    </label>
                    <input type="number" name="img" id="prod_coef">
                </form>
            </div>
            <div style="display: grid;">
                <form action="" name="product" method="POST"
                      style="border: 1px solid #6e6e6e; border-radius: 10px; padding: 10px;"
                      enctype="multipart/form-data">
                    <h2 style="text-align: center; font-size: 20px;">Добавление продукта</h2>
                    <label for="prod_name">
                        <h3>Название</h3>
                    </label>
                    <input type="text" name="title" id="prod_name">
                    <label for="prod_descr">
                        <h3>Описание</h3>
                    </label>
                    <input type="text" name="description" id="prod_descr">
                    <label for="prod_img">
                        <h3>Фото</h3>
                    </label>
                    <input type="file" name="img" id="prod_img">
                    <label for="prod_price">
                        <h3>Стоимость</h3>
                    </label>
                    <input type="number" name="img" id="prod_price">
                    <label for="prod_coef">
                        <h3>Коэффициент бонусов</h3>
                    </label>
                    <input type="number" name="img" id="prod_coef">
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
