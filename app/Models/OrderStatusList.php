<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusList extends Model
{
    use HasFactory;

    protected $table = 'order_status_list';

    protected $fillable = [
        'orderstatuscode',
        'orderstatus',
        'sortorder',
        'customerdisplay',
        'Active',
        'AdminDisplay'
    ];
}
