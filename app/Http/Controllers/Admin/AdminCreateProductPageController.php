<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Component_Type;
use App\Models\Product_Type;
use App\Models\Product_Type_Component;
use Illuminate\Http\Request;

class AdminCreateProductPageController extends Controller
{
    function create(){

        $data = [];

        $data['product_types'] = Product_Type::all()->toArray();

        $product_types_components = Product_Type_Component::all()->toArray();


        $component_types = Component_Type::all()->toArray();

        foreach($data['product_types'] as $product_type_index=>$product_type){
            $data['product_types'][$product_type_index]['components'] = [];
            foreach($product_types_components as $index=>$product_type_component){
                if($product_type_component['id_product_type'] == $product_type['id']){

                    $component = Component::find($product_type_component['id_component'])->toArray();

                    foreach($component_types as $component_type){
                        if($component_type['id'] == $component['id_component_type']){
                            if(!isset($data['product_types'][$product_type_index]['components'][$component_type['name']]))
                                $data['product_types'][$product_type_index]['components'][$component_type['name']] = [];


                            array_push($data['product_types'][$product_type_index]['components'][$component_type['name']], $component);
                            unset($product_types_components[$index]);
                        }
                    }
                }
            }
        }


        return view('adminCreateProduct')->with(['data'=>$data]);
    }
}
