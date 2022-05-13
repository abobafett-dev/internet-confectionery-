<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredient';

    protected $fillable = [
        'name',
        'description',
        'created_at',
        'updated_at'
    ];
}
