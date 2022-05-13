<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Component extends Model
{
    use HasFactory;

    protected $table = 'product-component';

    protected $fillable = [
        'id_product',
        'id_component',
        'created_at',
        'updated_at'
    ];
}
