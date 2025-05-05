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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade'); // Tenant
            $table->string('code')->unique(); // Coupon code
            $table->decimal('discount_amount', 8, 2)->nullable(); // Fixed discount
            $table->decimal('discount_percentage', 5, 2)->nullable(); // Percentage
            $table->dateTime('valid_from'); // Start date
            $table->dateTime('valid_until')->nullable(); // End date
            $table->integer('max_uses')->default(0); // 0 = unlimited
            $table->integer('used_count')->default(0); // Usage count
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
