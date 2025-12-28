<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingModule extends Model
{
    protected $fillable = [
        'key',
        'label',
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

