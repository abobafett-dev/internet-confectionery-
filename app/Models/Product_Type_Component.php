<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Type_Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_product_type',
        'id_component',
    ];
}
