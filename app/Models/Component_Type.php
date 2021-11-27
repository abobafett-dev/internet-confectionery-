<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component_Type extends Model
{
    use HasFactory;

    protected $table = 'component_type';

    protected $fillable = [
        'name'
    ];
}
