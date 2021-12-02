<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule_Interval extends Model
{
    use HasFactory;

    protected $table = 'schedule_interval';

    protected $fillable = [
        'start',
        'end',
        'isActive'
    ];
}
