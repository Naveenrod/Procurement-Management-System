<?php
namespace App\Models;

use App\Enums\DriverStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model {
    use HasFactory;

    protected $fillable = ['employee_id', 'user_id', 'name', 'phone', 'license_number', 'license_expiry', 'status'];
    protected $casts = [
        'status' => DriverStatus::class,
        'license_expiry' => 'date',
    ];

    protected static function booted(): void {
        static::creating(function ($model) {
            if (!$model->employee_id) {
                $count = static::count() + 1;
                $model->employee_id = 'DRV-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function trips() { return $this->hasMany(Trip::class); }
}
