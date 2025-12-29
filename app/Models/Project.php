<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'proposal_id',
        'client_user_id',
        'status',
        'delivered_at',
        'client_accepted_at',
        'client_rejected_at',
        'deployment_success_date',
        'maintenance_fee_usdt',
        'maintenance_offset_days',
        'maintenance_server_pct',
        'maintenance_team_pct',
        'maintenance_lp_pct',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'client_accepted_at' => 'datetime',
        'client_rejected_at' => 'datetime',
        'deployment_success_date' => 'date',
        'maintenance_fee_usdt' => 'decimal:2',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ProjectMessage::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function maintenanceInvoices(): HasMany
    {
        return $this->hasMany(MaintenanceInvoice::class);
    }
}
