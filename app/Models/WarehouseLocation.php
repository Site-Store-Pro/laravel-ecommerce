<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseLocation extends Model
{
    use HasFactory;

    protected $table = 'warehouse_locations';

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state_code',
        'country_code',
        'zipcode',
        'shipstation_carrier_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
