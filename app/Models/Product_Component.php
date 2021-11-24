<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Component extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_product',
        'id_component',
        'weight'
    ];
}
