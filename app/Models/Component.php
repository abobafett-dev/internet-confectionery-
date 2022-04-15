<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $table = 'component';

    protected $fillable = [
        'id_component_type',
        'name',
        'description',
        'coefficient',
        'price',
        'photo',
        'created_at',
        'updated_at'
    ];
}
