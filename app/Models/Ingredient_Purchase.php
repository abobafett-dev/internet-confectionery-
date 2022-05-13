<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient_Purchase extends Model
{
    use HasFactory;

    protected $table = 'ingredient-purchase';

    protected $fillable = [
        'id_ingredient',
        'id_purchase',
        'weight',
        'price',
        'created_at',
        'updated_at'
    ];
}
