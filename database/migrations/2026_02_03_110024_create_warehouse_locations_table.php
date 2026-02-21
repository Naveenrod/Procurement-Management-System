<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('warehouse_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->string('zone');
            $table->string('aisle');
            $table->string('rack');
            $table->string('shelf');
            $table->string('bin')->nullable();
            $table->integer('capacity')->default(100);
            $table->integer('occupied')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('warehouse_locations'); }
};
