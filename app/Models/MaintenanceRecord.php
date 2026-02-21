<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRecord extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = ['vehicle_id', 'type', 'description', 'scheduled_date', 'completed_date', 'cost', 'performed_by', 'notes'];
    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
    ];

    public function vehicle() { return $this->belongsTo(Vehicle::class); }
}
