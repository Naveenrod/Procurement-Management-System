<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('purchase_requisition_id')->nullable()->constrained();
            $table->foreignId('issued_by')->constrained('users');
            $table->date('issue_date');
            $table->date('closing_date');
            $table->string('status')->default('draft');
            $table->text('terms')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rfqs'); }
};
