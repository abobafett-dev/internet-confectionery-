<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminProductsPageController extends Controller
{

    function create()
    {
        if (Auth::user()->id_user_status != 2)
            abort(403);


        $products = Product::where('name', '<>', 'Конструктор')->get();
        $product_types = Product_Type::all()->toArray();

        $product_types_ids = array_column($product_types, 'id');

        $productsWithTypesAndCount = [];
        foreach ($products as $product) {
            $product->product_type = $product_types[array_search($product->id_product_type, $product_types_ids)]['name'];
            if ($product->photo != null)
                $product->photo = asset(Storage::url($product->photo) . "?r=" . rand(0, 1000));

            $productsWithTypesAndCount[count($productsWithTypesAndCount)] = $product->toArray();
        }

        $product_types = array();
        foreach(Product_Type::all()->toArray() as $product_type){
            $product_types[$product_type['id']] = $product_type;
        }

        return view('adminProducts')->with(['productsWithTypesAndCount'=>$productsWithTypesAndCount, 'product_types'=>$product_types]);
    }
}
