<?php

namespace App\Models;

use App\Enums\RfqStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rfq extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'rfq_number',
        'title',
        'description',
        'purchase_requisition_id',
        'issued_by',
        'issue_date',
        'closing_date',
        'status',
        'terms',
    ];

    protected function casts(): array
    {
        return [
            'status' => RfqStatus::class,
            'issue_date' => 'date',
            'closing_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (!$model->rfq_number) {
                $latest = static::max('id') ?? 0;
                $model->rfq_number = 'RFQ-' . str_pad($latest + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequisition::class, 'purchase_requisition_id');
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(RfqVendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RfqItem::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(RfqResponse::class);
    }
}
