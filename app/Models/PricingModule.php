<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingModule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'key',
        'label',
        'description',
        'dev_cost_usdt',
        'monthly_cost_usdt',
        'is_active',
    ];

    protected $casts = [
        'dev_cost_usdt' => 'decimal:2',
        'monthly_cost_usdt' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}

