<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('department');
            $table->foreignId('requested_by')->constrained('users');
            $table->date('required_date');
            $table->string('status')->default('draft');
            $table->string('priority')->default('medium');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchase_requisitions'); }
};
