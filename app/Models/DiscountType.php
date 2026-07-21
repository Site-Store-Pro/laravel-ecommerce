<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscountType extends Model
{
    protected $table = 'discount_types';

    protected $fillable = ['name', 'description'];

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'discount_type_id');
    }
}
