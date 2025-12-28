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
        'paid_total_usdt',
        'paid_modules',
        'payment_verified_at',
        'payment_chain',
        'payment_status',
        'payment_to_address',
        'payment_from_address',
        'payment_tx_hash',
        'submitted_at',
        'work_status',
        'expected_completion_date',
        'completed_at',
        'estimated_monthly_usdt',
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
        'paid_total_usdt' => 'decimal:2',
        'paid_modules' => 'array',
        'payment_verified_at' => 'datetime',
        'submitted_at' => 'datetime',
        'expected_completion_date' => 'date',
        'completed_at' => 'datetime',
        'estimated_monthly_usdt' => 'decimal:2',
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

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class, 'proposal_id');
    }

    public function orderFiles(): HasMany
    {
        return $this->hasMany(OrderFile::class, 'proposal_id');
    }
}
