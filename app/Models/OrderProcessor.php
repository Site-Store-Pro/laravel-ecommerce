<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProcessor extends Model
{
    use HasFactory;

    protected $table = 'order_processors';

    protected $fillable = [
        'processor_id',
        'processor_name',
        'production',
    ];
}
