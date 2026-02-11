<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('group_product_offers', function (Blueprint $table) {
            // Add offer type: 'product', 'category', 'brand'
            $table->enum('offer_type', ['product', 'category', 'brand'])->default('product')->after('customer_group_id');

            // Make product_id nullable since we might target category/brand
            $table->foreignId('product_id')->nullable()->change();

            // Add category and brand foreign keys
            $table->foreignId('category_id')->nullable()->after('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->after('category_id')->constrained()->onDelete('cascade');

            // Add offer value type: 'percentage' or 'fixed'
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed')->after('offer_type');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
        });
    }

    public function down()
    {
        Schema::table('group_product_offers', function (Blueprint $table) {
            $table->dropColumn(['offer_type', 'category_id', 'brand_id', 'discount_type', 'discount_value']);
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
