<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'requirements',
        'requested_modules',
        'development_price_rbe',
        'status',
        'wizard_step',
        'wizard_step1',
        'wizard_step2',
        'wizard_step4_estimate',
        'estimated_total_usdt',
        'payment_chain',
        'payment_status',
        'payment_to_address',
        'payment_from_address',
        'payment_tx_hash',
        'submitted_at',
        'reviewed_by_user_id',
        'review_note',
        'reviewed_at',
    ];

    protected $casts = [
        'requirements' => 'array',
        'requested_modules' => 'array',
        'wizard_step1' => 'array',
        'wizard_step2' => 'array',
        'wizard_step4_estimate' => 'array',
        'estimated_total_usdt' => 'decimal:2',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    public function orderFiles(): HasMany
    {
        return $this->hasMany(OrderFile::class, 'proposal_id');
    }
}
