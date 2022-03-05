<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_Type;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProductsPageController extends Controller
{

    function create()
    {
        if (Auth::user()->id_user_status != 2)
            abort(403);


        $products = Product::where('name', '<>', 'Конструктор')->get()->toArray();
        $products_copy = new \ArrayObject($products);
        $product_types = Product_Type::all()->toArray();

        $product_types_ids = array_column($product_types, 'id');

        $productsWithTypesAndCount = [];
        $isDeleted = [];
        foreach ($products as $idProduct => $product) {
            foreach ($products_copy as $idProductCopy => $product_copy) {
                if ($product_copy['id'] == $product['id'])
                    continue;
                elseif ($product['name'] == $product_copy['name'] && $product['id_product_type'] == $product_copy['id_product_type']) {
                    if ($product_copy['isActive'] == true) {
                        unset($products[$idProduct]);
                        $prov = true;
                        break;
                    } elseif (!in_array($idProductCopy, $isDeleted) && !in_array($idProduct, $isDeleted)) {
                        array_push($isDeleted, $idProductCopy);
                        continue;
                    }
                }
            }
            if (isset($prov)) {
                unset($prov);
                continue;
            }

            if (!in_array($idProduct, $isDeleted)) {
                $product['product_type'] = $product_types[array_search($product['id_product_type'], $product_types_ids)]['name'];
                if ($product['photo'] != null)
                    $product['photo'] = asset(Storage::url($product['photo']) . "?r=" . rand(0, 1000));

                $productsWithTypesAndCount[count($productsWithTypesAndCount)] = $product;
            }
        }

        $product_types = array();
        foreach (Product_Type::all()->toArray() as $product_type) {
            $product_types[$product_type['id']] = $product_type;
        }

        return view('adminProducts')->with(['productsWithTypesAndCount' => $productsWithTypesAndCount, 'product_types' => $product_types]);
    }


    //Функция для изменения будет ли продаваться продукт или нет
    //В атрибут надо отправить
    // product = id продукта
    //$this->changeActiveAjax(new Request(['product'=>21]));
    function changeActiveAjax(Request $request)
    {
        $request = $request->toArray();

        $currentProduct = Product::find($request['product']);

        if ($currentProduct != null) {
            $currentProduct->update(['isActive' => !$currentProduct->isActive]);

            return "Статус продукта успешно изменен!";
        } else {
            return "Ошибка! продукт не обнаружен. Перезагрузите страницу Ctrl+F5";
        }
    }
}
