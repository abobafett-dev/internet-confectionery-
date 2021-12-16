<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Component_Type;
use App\Models\Order_Product;
use App\Models\Product;
use App\Models\Product_Type;
use App\Models\Product_Type_Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserCatalogController extends Controller
{
    public function create(){
        $products = Product::where('isActive', true)->where('name', '<>', 'Конструктор')->get();
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

        return view('catalog')->with(['productsWithTypesAndCount'=>$productsWithTypesAndCount, 'product_types'=>$product_types]);
    }
}
