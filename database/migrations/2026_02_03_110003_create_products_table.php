<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->string('unit_of_measure');
            $table->decimal('unit_price', 12, 2);
            $table->integer('reorder_point')->default(10);
            $table->integer('reorder_quantity')->default(50);
            $table->integer('lead_time_days')->default(7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
