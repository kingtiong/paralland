<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceInvoice extends Model
{
    protected $fillable = [
        'project_id',
        'due_date',
        'amount_usdt',
        'status',
        'paid_at',
        'last_reminded_at',
        'payment_tx_hash',
        'paid_wallet_address',
        'split_server_usdt',
        'split_team_usdt',
        'split_lp_usdt',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'last_reminded_at' => 'datetime',
        'amount_usdt' => 'decimal:2',
        'split_server_usdt' => 'decimal:2',
        'split_team_usdt' => 'decimal:2',
        'split_lp_usdt' => 'decimal:2',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
