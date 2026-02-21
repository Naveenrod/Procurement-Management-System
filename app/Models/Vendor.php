<?php

namespace App\Models;

use App\Enums\PaymentTerms;
use App\Enums\VendorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'tax_id',
        'payment_terms',
        'status',
        'rating',
        'website',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => VendorStatus::class,
            'payment_terms' => PaymentTerms::class,
            'rating' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (!$model->code) {
                $latest = static::max('id') ?? 0;
                $model->code = 'VND-' . str_pad($latest + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(VendorContact::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function performanceScores(): HasMany
    {
        return $this->hasMany(VendorPerformanceScore::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
