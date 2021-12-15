<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule_Update extends Model
{
    use HasFactory;

    protected $table = 'schedule_update';

    protected $fillable = [
        'schedule_will_updated_at',
        'access',
        'orders_count_update',
        'id_schedule_interval',
        'start',
        'end'
    ];
}
