<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Component_Type;
use App\Models\Ingredient;
use App\Models\Ingredient_Component;
use App\Models\Product;
use App\Models\Product_Component;
use App\Models\Product_Type;
use App\Models\Product_Type_Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\isEmpty;

class AdminCreateProductPageController extends Controller
{
    function create()
    {

        if (Auth::user()->id_user_status != 2)
            abort(403);

        $data = [];

        $data['product_types'] = [];

        $data['ingredients'] = Ingredient::all()->toArray();

        $product_types = Product_Type::all()->toArray();

        foreach ($product_types as $product_type) {
            $data['product_types'][$product_type['id']] = $product_type;
        }

        $product_types_components = Product_Type_Component::all()->toArray();

        $component_types = Component_Type::all()->toArray();
        $data['component_types'] = $component_types;

        foreach ($data['product_types'] as $product_type_index => $product_type) {
            $data['product_types'][$product_type_index]['components'] = [];
            foreach ($product_types_components as $index => $product_type_component) {
                if ($product_type_component['id_product_type'] == $product_type['id']) {

                    $component = Component::find($product_type_component['id_component'])->toArray();

                    $component['photo'] =
                        asset(Storage::url($component['photo']) . "?r=" . rand(0, 1000));

                    foreach ($component_types as $component_type) {
                        if ($component_type['id'] == $component['id_component_type']) {
                            if (!isset($data['product_types'][$product_type_index]['components'][$component_type['name']]))
                                $data['product_types'][$product_type_index]['components'][$component_type['name']] = [];


                            array_push($data['product_types'][$product_type_index]['components'][$component_type['name']], $component);
                            unset($product_types_components[$index]);
                        }
                    }
                }
            }
        }

        return view('adminCreateProduct')->with(['data' => $data]);
    }

    function addProduct(Request $request)
    {
        if (Auth::user()->id_user_status != 2)
            abort(403);

        $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:App\Models\Product,name'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'bonus' => ['nullable', 'numeric', 'min:0'],
            'type_prod' => ['required', 'integer'],
            'img' => ['filled', 'image', 'mimes:jpeg,jpg,png', 'max:5500'],
        ]);

        $data = $request->toArray();
        $copyOfData = $request->toArray();

        unset($copyOfData['_token']);
        foreach ($copyOfData as $index => $copyOfDatum) {
            if ($copyOfDatum == null) {
                $copyOfData[$index] = "";
            }
        }

        unset($data['title']);
        unset($data['description']);
        unset($data['price']);
        unset($data['bonus']);
        unset($data['type_prod']);
        unset($copyOfData['img']);

        $components = [];

        $productType = Product_Type::find($copyOfData['type_prod'])->toArray();

        if (empty($productType))
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип продукта не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);

        foreach ($data as $index => $datum) {
            if (strpos($index, 'component_') !== false) {
                $component = Component::find($datum)->toArray();
                if (empty($component)) {
                    return redirect('admin/products/add')->with(['errorInDB' => 'Компонент не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);
                }
                $components[$datum] = $component;
            }
        }


        $productTypeComponents = Product_Type_Component::where('id_product_type', $copyOfData['type_prod'])->get()->toArray();
        $componentsOfProductType = [];

        foreach($productTypeComponents as $productTypeComponent){
            $component = Component::find($productTypeComponent['id_component'])->toArray();
            $componentsOfProductType[$productTypeComponent['id_component']] = $component;
        }

        $mustComponents = [];
        foreach ($componentsOfProductType as $componentOfProductType){
            if(!in_array($componentOfProductType['id_component_type'], $mustComponents)){
                $mustComponents[$componentOfProductType['id_component_type']] = $componentOfProductType['id_component_type'];
            }
        }


        if(count($mustComponents) != count($components)){
            return redirect('admin/products/add')->with(['errorWithData' => 'Компоненты не для всех типов компонентов были выбраны были выбраны', 'data' => $copyOfData]);
        }

        $validatedComponents = [];

        foreach ($components as $component) {
            foreach ($productTypeComponents as $productTypeComponent) {
                if ($productTypeComponent['id_component'] == $component['id']) {
                    $validatedComponents[$component['id']] = $component;
                    break;
                }
            }
        }

        if (count($validatedComponents) != count($components)) {
            return redirect('admin/products/add')->with(['errorWithData' => 'Не все компоненты могут быть использованы для указанного типа продукта', 'data' => $copyOfData]);
        }

        foreach ($components as $firstComponent) {
            foreach ($components as $secondComponent) {
                if ($firstComponent['id'] == $secondComponent['id']) {
                    continue;
                }
                if ($firstComponent['id_component_type'] == $secondComponent['id_component_type']) {
                    return redirect('admin/products/add')->with(['errorWithData' => 'Обнаружены дубликаты компонентов', 'data' => $copyOfData]);
                }
            }
        }

        $dublicatedComponentsByProducts = [];
        $firstComponent = array_shift($components);
        $isDublicatedProducts = Product_Component::where('id_component',$firstComponent['id']);
        array_unshift($components, $firstComponent);

        foreach ($components as $component){
            $isDublicatedProducts = $isDublicatedProducts->orWhere('id_component',$component['id']);
        }

        $isDublicatedProducts = $isDublicatedProducts->get()->toArray();

        foreach($isDublicatedProducts as $isDublicatedProduct){
            if(!isset($dublicatedComponentsByProducts[$isDublicatedProduct['id_product']]))
                $dublicatedComponentsByProducts[$isDublicatedProduct['id_product']] = [];
            $dublicatedComponentsByProducts[$isDublicatedProduct['id_product']][$isDublicatedProduct['id_component']] = $isDublicatedProduct['id_product'];
        }

        foreach($dublicatedComponentsByProducts as $index=>$dublicatedComponentsByProduct){
            if(count($dublicatedComponentsByProduct) == count($components)){
                $dublicateProduct = Product::find($index)->toArray();
                return redirect('admin/products/add')->with(['errorWithData' => 'Обнаружены дубликаты продукта по компонентам с именем '. $dublicateProduct['name'], 'data' => $copyOfData]);
            }
        }
        $bonus_coefficient = null;
        if($copyOfData['bonus'] == "")
            $bonus_coefficient = 1;
        else
            $bonus_coefficient = $copyOfData['bonus'];

        $currentDate = date("Y-m-d H:i:s");
        $productId = Product::insertGetId([
            'id_product_type' => $productType['id'],
            'name' => $copyOfData['title'],
            'description' => $copyOfData['description'],
            'photo' => "Заглушка",
            'price' => $copyOfData['price'],
            'bonus_coefficient' => $bonus_coefficient,
            'isActive' => false,
            'created_at' => $currentDate,
            'updated_at' => $currentDate
        ]);
        if(isset($data['img']))
            $path = Storage::putFileAs('public/product', $data['img'], $productId . ".png");
        else
            $path = "";

        Product::find($productId)->update(['photo' => $path]);


        foreach ($components as $component) {
            Product_Component::insert([
                'id_product' => $productId,
                'id_component' => $component['id'],
                'created_at' => $currentDate, 'updated_at' => $currentDate
            ]);
        }

        return redirect('admin/products/add')->with(['was_created' => 'Продукт с именем ' . $copyOfData['title'] . ' был создан']);
    }

    function addProductType(Request $request)
    {

        if (Auth::user()->id_user_status != 2)
            abort(403);

        $request->validate([
            'title_type_prod' => ['required', 'string', 'max:255'],
            'type_prod_min_weight' => ['nullable', 'numeric'],
            'type_prod_max_weight' => ['nullable', 'numeric'],
            'type_prod_standard_weight' => ['nullable', 'numeric'],
            'using_constr' => ['nullable', 'string', 'size:2'],
        ]);

        $data = $request->toArray();
        $copyOfData = $request->toArray();
        unset($copyOfData['_token']);
        foreach ($copyOfData as $index => $copyOfDatum) {
            if ($copyOfDatum == null) {
                $copyOfData[$index] = "";
            }
        }

        $data['title_type_prod'] = mb_strtolower($data['title_type_prod']);

        if (isset($data['using_constr']))
            $data['using_constr'] = true;
        else
            $data['using_constr'] = false;

        $data['type_prod_standard_weight'] = floatval($data['type_prod_standard_weight']);

        if ($data['type_prod_min_weight'] == "" && $data['type_prod_max_weight'] != "") {
            return redirect('admin/products/add')->with(['errorWithWeight' => 'Необходимо указать минимальный вес для типа продукта', 'data' => $copyOfData]);
        } elseif ($data['type_prod_min_weight'] != "" && $data['type_prod_max_weight'] == "") {
            return redirect('admin/products/add')->with(['errorWithWeight' => 'Необходимо указать максимальный вес для типа продукта', 'data' => $copyOfData]);
        } elseif ($data['type_prod_min_weight'] != "" && $data['type_prod_max_weight'] != "") {
            $data['type_prod_min_weight'] = floatval($data['type_prod_min_weight']);
            $data['type_prod_max_weight'] = floatval($data['type_prod_max_weight']);
            if (!($data['type_prod_min_weight'] < $data['type_prod_standard_weight'] && $data['type_prod_standard_weight'] < $data['type_prod_max_weight'])) {
                return redirect('admin/products/add')->with(['errorWithWeight' => 'Стандартный вес должен быть больше минимального и меньше максимального', 'data' => $copyOfData]);
            }
        }

        $isProductTypeInDB = !empty(Ingredient::where('name', $data['title_type_prod'])->toArray());

        if ($isProductTypeInDB) {
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип Продукта с указанным именем уже создан', 'data' => $copyOfData]);
        }

        $currentDate = date("Y-m-d H:i:s");
        Product_Type::insert(['name' => $data['title_type_prod'], 'weight_min' => $data['type_prod_min_weight'],
            'weight_max' => $data['type_prod_max_weight'], 'weight_initial' => $data['type_prod_standard_weight'],
            'isConstructor' => $data['using_constr'],
            'created_at' => $currentDate, 'updated_at' => $currentDate]);
        return redirect('admin/products/add')->with(['was_created' => 'Тип Продукта с именем ' . $data['title_type_prod'] . ' был создан']);
    }

    function addComponent(Request $request)
    {

        if (Auth::user()->id_user_status != 2)
            abort(403);

        $request->validate([
            'comp_name' => ['required', 'string', 'max:255'],
            'comp_description' => ['nullable', 'string'],
            'comp_type_comp' => ['required', 'integer'],
            'comp_type_prod' => ['required', 'integer'],
            'comp_price' => ['required', 'numeric', 'min:0'],
            'comp_coef' => ['required', 'numeric', 'between:0,1'],
            'comp' => ['filled', 'image', 'mimes:jpeg,jpg,png', 'max:5500'],
        ]);

        $data = $request->toArray();
        $copyOfData = $request->toArray();
        unset($copyOfData['_token']);
        foreach ($copyOfData as $index => $copyOfDatum) {
            if ($copyOfDatum == null) {
                $copyOfData[$index] = "";
            }
        }

        $componentType = Component_Type::find($data['comp_type_comp'])->toArray();

        if (empty($componentType))
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип компонента не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);

        $productType = Product_Type::find($data['comp_type_prod'])->toArray();

        if (empty($productType))
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип продукта не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);

        $productTypeComponents = Product_Type_Component::where('id_product_type', $productType['id'])->where('id_component', $componentType['id'])->get()->toArray();
        $componentTypesComponents = Component::where('id_component_type', $componentType['id'])->get()->toArray();

        $componentTypes = [];
        $indexToDelete = null;

        foreach ($productTypeComponents as $productTypeComponent) {
            foreach ($componentTypesComponents as $index => $component) {
                if ($component['id'] == $productTypeComponent['id_component']) {
                    $indexToDelete = $index;
                    if (!in_array($component['id_component_type'], $componentTypes)) {
                        $componentTypes[count($componentTypes) - 1] = $component['id_component_type'];
                    }
                    break;
                }
            }
            if (!is_null($indexToDelete)) {
                unset($componentTypes[$indexToDelete]);
                $indexToDelete = null;
            }
        }

        if (!in_array($componentType['id'], $componentTypes)) {
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип компонента нельзя выбрать для этого типа продукта, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);
        }

        unset($data['comp_name']);
        unset($data['comp_description']);
        unset($data['comp_type_comp']);
        unset($data['comp_type_prod']);
        unset($data['comp_price']);
        unset($data['comp_coef']);
        unset($copyOfData['comp']);

        $ingredients = [];
        $weightOfAllIngredients = 0;

        foreach ($data as $index => $datum) {
            if (strpos($index, 'comp_ingred_') !== false && strpos($index, 'comp_ingred_weight_') === false) {
                $indexOfData = $datum;
                if (!isset($data["comp_ingred_weight_" . explode('_', $index)[2]])) {
//                    var_dump($index);
//                    var_dump($data["comp_ingred_weight_" . explode('_', $index)[2]]);
//                    var_dump($data);
//                    return;
                    return redirect('admin/products/add')->with(['errorWithData' => 'Доля для ингредиента не найдена', 'data' => $copyOfData]);
                }

                $ingredient = Ingredient::find($datum)->toArray();
                if (empty($ingredient)) {
                    return redirect('admin/products/add')->with(['errorInDB' => 'Ингредиент не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);
                }
                if (in_array($ingredient, $ingredients)) {
                    return redirect('admin/products/add')->with(['errorWithData' => 'Нельзя дублировать ингредиенты', 'data' => $copyOfData]);
                }

                $ingredients[$indexOfData] = $ingredient;
                $weightOfAllIngredients += $data["comp_ingred_weight_" . explode('_', $index)[2]];
                if ($weightOfAllIngredients > 1) {
                    return redirect('admin/products/add')->with(['errorWithWeight' => 'Общая сумма долей ингредиентов должна быть меньше или равна 1', 'data' => $copyOfData]);
                }
            }
        }

        $ingredientsValidate = [];
        foreach ($ingredients as $index => $ingredient) {
            $ingredientsValidate['comp_ingred_' . $index] = ['integer'];
            $ingredientsValidate['comp_ingred_weight_' . $index] = ['numeric', 'between:0,1'];
        }

        $request->validate($ingredientsValidate);

        $componentCoefficient = round(round($copyOfData['comp_coef'], 2) * $productType['weight_initial'], 2);

        $currentDate = date("Y-m-d H:i:s");
        $currentComponentId = Component::insertGetId(
            [
                'name' => $copyOfData['comp_name'], 'description' => $copyOfData['comp_description'],
                'coefficient' => $componentCoefficient, 'id_component_type' => $componentType['id'],
                'price' => $copyOfData['comp_price'], 'isActive' => true, 'photo' => 'Заглушка',
                'created_at' => $currentDate, 'updated_at' => $currentDate
            ]);

        if(isset($data['comp']))
            $path = Storage::putFileAs('public/component', $data['comp'], $currentComponentId . ".png");
        else
            $path = "";

        Component::find($currentComponentId)->update(['photo' => $path]);

        Product_Type_Component::insert(
            [
                'id_product_type' => $productType['id'],
                'id_component' => $currentComponentId,
                'created_at' => $currentDate, 'updated_at' => $currentDate
            ]);
        foreach ($ingredients as $index => $ingredient) {
            Ingredient_Component::insert([
                'id_ingredient' => $ingredient['id'],
                'id_component' => $currentComponentId,
                'weight' => $data['comp_ingred_weight_' . $index],
                'created_at' => $currentDate, 'updated_at' => $currentDate
            ]);
        }

        return redirect('admin/products/add')->with(['was_created' => 'Компонент с именем ' . $copyOfData['comp_name'] . ' был создан']);
    }

    function addComponentType(Request $request)
    {

        if (Auth::user()->id_user_status != 2)
            abort(403);

        $request->validate([
            'title_comp_prod' => ['required', 'string', 'max:255']
        ]);

        $data = $request->toArray();
        $copyOfData = $request->toArray();
        unset($copyOfData['_token']);
        foreach ($copyOfData as $index => $copyOfDatum) {
            if ($copyOfDatum == null) {
                $copyOfData[$index] = "";
            }
        }

        $data['title_comp_prod'] = mb_strtolower($data['title_comp_prod']);

        $isComponentTypeInDB = !empty(Ingredient::where('name', $data['title_comp_prod'])->toArray());
        if ($isComponentTypeInDB) {
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип Компонента уже создан', 'data' => $copyOfData]);
        }


        $currentDate = date("Y-m-d H:i:s");
        Component_Type::insert(['name' => $data['title_comp_prod'],
            'created_at' => $currentDate, 'updated_at' => $currentDate]);
        return redirect('admin/products/add')->with(['was_created' => 'Тип компонента с именем ' . $data['title_comp_prod'] . ' был создан']);
    }

    function addIngredient(Request $request)
    {

        if (Auth::user()->id_user_status != 2)
            abort(403);

        $request->validate([
            'title_ingredient' => ['required', 'string', 'max:255'],
            'ingredient_description' => ['nullable', 'string'],
        ]);

        $data = $request->toArray();
        $copyOfData = $request->toArray();
        unset($copyOfData['_token']);
        foreach ($copyOfData as $index => $copyOfDatum) {
            if ($copyOfDatum == null) {
                $copyOfData[$index] = "";
            }
        }

        $data['title_ingredient'] = mb_strtolower($data['title_ingredient']);
        $data['ingredient_description'] = mb_strtolower($data['ingredient_description']);

        $isIngredientInDB = !empty(Ingredient::where('name', $data['title_ingredient'])->toArray());
        if ($isIngredientInDB) {
            return redirect('admin/products/add')->with(['errorInDB' => 'Ингредиент уже создан', 'data' => $copyOfData]);
        }

        $currentDate = date("Y-m-d H:i:s");
        Ingredient::insert(['name' => $data['title_ingredient'], 'description' => $data['ingredient_description'],
            'created_at' => $currentDate, 'updated_at' => $currentDate]);
        return redirect('admin/products/add')->with(['was_created' => 'Ингредиент с именем ' . $data['title_ingredient'] . ' был создан']);
    }
}
