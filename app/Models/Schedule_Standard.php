<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule_Standard extends Model
{
    use HasFactory;

    protected $table = 'schedule_standard';

    protected $fillable = [
        'weekday',
        'start',
        'end',
        'orders_count',
        'isActive'
    ];
}
