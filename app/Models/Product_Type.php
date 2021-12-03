<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Type extends Model
{
    use HasFactory;

    protected $table = 'product_type';

    protected $fillable = [
        'name',
        'weight_min',
        'weight_initial',
        'weight_max',
        ''
    ];
}
