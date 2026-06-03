<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('primary_email');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('company_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_account_id')
                  ->constrained('company_accounts')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->enum('role', ['owner', 'member'])->default('member');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['company_account_id', 'user_id']);
        });

        Schema::create('company_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_account_id')
                  ->constrained('company_accounts')
                  ->cascadeOnDelete();
            $table->foreignId('customer_group_id')
                  ->constrained('customer_groups')
                  ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['company_account_id', 'customer_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_group');
        Schema::dropIfExists('company_users');
        Schema::dropIfExists('company_accounts');
    }
};
