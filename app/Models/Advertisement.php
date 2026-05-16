<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'advertiser_name',
        'image_path',
        'link_url',
        'position',
        'commission_amount',
        'commission_type',
        'start_date',
        'end_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'commission_amount' => 'decimal:2',
    ];

    /**
     * Cek apakah iklan masih dalam periode kontrak aktif.
     */
    public function isInContractPeriod(): bool
    {
        $now = now()->startOfDay();

        if ($this->start_date && $this->end_date) {
            return $now->between($this->start_date, $this->end_date);
        }

        if ($this->start_date) {
            return $now->gte($this->start_date);
        }

        return true; // no date restriction
    }

    /**
     * Format commission amount for display.
     */
    public function getFormattedCommissionAttribute(): string
    {
        if ($this->commission_amount <= 0) {
            return 'Tidak ada komisi';
        }

        if ($this->commission_type === 'percentage') {
            return number_format($this->commission_amount, 0) . '%';
        }

        return 'Rp ' . number_format($this->commission_amount, 0, ',', '.');
    }
}