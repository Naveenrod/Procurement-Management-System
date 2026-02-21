<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('trip_id')->nullable()->constrained();
            $table->string('fuel_type')->default('diesel');
            $table->decimal('liters', 8, 2);
            $table->decimal('cost_per_liter', 8, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('odometer_reading', 10, 2)->default(0);
            $table->timestamp('filled_at');
            $table->string('station_name')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('fuel_logs'); }
};
