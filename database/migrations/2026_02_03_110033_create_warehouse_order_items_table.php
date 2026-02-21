<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('warehouse_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->decimal('expected_quantity', 10, 2)->default(0);
            $table->decimal('received_quantity', 10, 2)->default(0);
            $table->decimal('picked_quantity', 10, 2)->default(0);
            $table->foreignId('warehouse_location_id')->nullable()->constrained();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('warehouse_order_items'); }
};
