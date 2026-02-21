<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('type')->default('truck');
            $table->string('status')->default('available');
            $table->decimal('mileage', 10, 2)->default(0);
            $table->string('fuel_type')->default('diesel');
            $table->date('insurance_expiry')->nullable();
            $table->date('registration_expiry')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vehicles'); }
};
