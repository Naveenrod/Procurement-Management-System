<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RfqResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'rfq_id',
        'vendor_id',
        'total_amount',
        'delivery_days',
        'payment_terms',
        'notes',
        'is_selected',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'is_selected' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(Rfq::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RfqResponseItem::class);
    }
}
