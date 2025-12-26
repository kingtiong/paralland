<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Proposal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'requirements',
        'development_price_rbe',
        'status',
        'reviewed_by_user_id',
        'review_note',
        'reviewed_at',
    ];

    protected $casts = [
        'requirements' => 'array',
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
}
