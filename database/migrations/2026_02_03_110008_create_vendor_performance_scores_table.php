<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vendor_performance_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('delivery_score', 5, 2)->default(0);
            $table->decimal('quality_score', 5, 2)->default(0);
            $table->decimal('price_score', 5, 2)->default(0);
            $table->decimal('responsiveness_score', 5, 2)->default(0);
            $table->decimal('overall_score', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('scored_by')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vendor_performance_scores'); }
};
