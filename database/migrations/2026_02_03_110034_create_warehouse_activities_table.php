<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('warehouse_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('warehouse_order_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('type');
            $table->text('description');
            $table->foreignId('product_id')->nullable()->constrained();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->foreignId('warehouse_location_id')->nullable()->constrained();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('warehouse_activities'); }
};
