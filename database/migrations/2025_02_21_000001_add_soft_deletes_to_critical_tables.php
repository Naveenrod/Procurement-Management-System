<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The tables that need soft deletes added.
     */
    private array $tables = [
        'purchase_requisitions',
        'purchase_orders',
        'purchase_order_items',
        'invoices',
        'invoice_items',
        'goods_receipts',
        'goods_receipt_items',
        'rfqs',
        'rfq_items',
        'rfq_responses',
        'contracts',
        'budgets',
        'inventory_transactions',
        'shipments',
        'vehicles',
        'drivers',
        'trips',
        'maintenance_records',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropSoftDeletes();
            });
        }
    }
};
