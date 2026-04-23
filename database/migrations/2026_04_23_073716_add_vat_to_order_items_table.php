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
        Schema::table('order_items', function (Blueprint $table) {
            // VAT rate stored per line item — snapshot at time of order
            // decimal(5,2) handles 0.00% to 999.99% — covers UK 0%, 5%, 20%
            $table->decimal('vat_rate', 5, 2)->default(0)->after('price');

            // Actual VAT amount in £ calculated at order time
            $table->decimal('vat_amount', 10, 2)->default(0)->after('vat_rate');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['vat_rate', 'vat_amount']);
        });
    }
};
