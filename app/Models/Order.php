<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'id_user',
        'id_status',
        'will_cooked_at',
        'address',
        'review_text',
        'review_photo',
        'id_schedule_standard',
        'id_schedule_interval'
    ];
}
