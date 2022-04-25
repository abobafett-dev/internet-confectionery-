<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Component_Type;
use App\Models\Ingredient;
use App\Models\Ingredient_Component;
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

        $data = $request->toArray();
        $copyOfData = $request->toArray();

        var_dump($data);
        var_dump($copyOfData);
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

        $isProductTypeInDB = !empty(Ingredient::where('name', $data['title_type_prod'])->get()->toArray());

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

        $componentType = Component_Type::find($data['comp_type_comp'])->get()->toArray();

        if (empty($componentType))
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип компонента не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);

        $productType = Product_Type::find($data['comp_type_prod'])->get()->toArray();

        if (empty($productType))
            return redirect('admin/products/add')->with(['errorInDB' => 'Тип продукта не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);

        unset($data['comp_name']);
        unset($data['comp_description']);
        unset($data['comp_type_comp']);
        unset($data['comp_type_prod']);
        unset($data['comp_price']);
        unset($data['comp_coef']);
        unset($data['comp']);

        $ingredients = [];
        $weightOfAllIngredients = 0;

        foreach ($data as $datum) {
            if (strpos($datum, 'comp_ingred_')) {
                $indexOfData = explode('_', $datum)[2];
                if (!isset($data["comp_ingred_weight_" + $indexOfData])) {
                    return redirect('admin/products/add')->with(['errorWithData' => 'Доля для ингредиента не найдена', 'data' => $copyOfData]);
                }

                $ingredient = Ingredient::find($datum)->get()->toArray();
                if (empty($ingredient)) {
                    return redirect('admin/products/add')->with(['errorInDB' => 'Ингредиент не обнаружен, перезагрузите страницу - ctrl+F5', 'data' => $copyOfData]);
                }
                if(in_array($ingredient, $ingredients)){
                    return redirect('admin/products/add')->with(['errorWithData' => 'Нельзя дублировать ингредиенты', 'data' => $copyOfData]);
                }

                array_push($ingredients, [$indexOfData => $ingredient]);
                $weightOfAllIngredients += $data["comp_ingred_weight_" + $indexOfData];
                if ($weightOfAllIngredients > 1) {
                    return redirect('admin/products/add')->with(['errorWithWeight' => 'Общая сумма долей ингредиентов должна быть меньше или равна 1', 'data' => $copyOfData]);
                }
            }
        }

        $componentCoefficient = round(round($copyOfData['comp_coef'], 2) * $productType['weight_initial'], 2);

        $currentDate = date("Y-m-d H:i:s");
        $currentComponentId = Component::insertGetId(
            [
                'name' => $copyOfData['comp_name'], 'description' => $copyOfData['comp_description'],
                'coefficient' => $componentCoefficient, 'id_component_type' => $componentType['id'],
                'price' => $copyOfData['comp_price'], 'isActive' => true, 'photo' => 'Заглушка',
                'created_at' => $currentDate, 'updated_at' => $currentDate
            ]);

        $path = Storage::putFileAs('public/component', $copyOfData['comp'], $currentComponentId . ".png");

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
                'weight' => $data['comp_ingred_weight_' + $index],
                'created_at' => $currentDate, 'updated_at' => $currentDate
            ]);
        }

        return redirect('admin/products/add')->with(['was_created' => 'Компонент с именем ' . $data['comp_name'] . ' был создан']);
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

        $isComponentTypeInDB = !empty(Ingredient::where('name', $data['title_comp_prod'])->get()->toArray());
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

        $isIngredientInDB = !empty(Ingredient::where('name', $data['title_ingredient'])->get()->toArray());
        if ($isIngredientInDB) {
            return redirect('admin/products/add')->with(['errorInDB' => 'Ингредиент уже создан', 'data' => $copyOfData]);
        }

        $currentDate = date("Y-m-d H:i:s");
        Ingredient::insert(['name' => $data['title_ingredient'], 'description' => $data['ingredient_description'],
            'created_at' => $currentDate, 'updated_at' => $currentDate]);
        return redirect('admin/products/add')->with(['was_created' => 'Ингредиент с именем ' . $data['title_ingredient'] . ' был создан']);
    }
}
