<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient_Component extends Model
{
    use HasFactory;

    protected $table = 'ingredient-component';

    protected $fillable = [
        'id_ingredient',
        'id_component',
        'weight'
    ];
}
