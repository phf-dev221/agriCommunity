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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Order
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade'); // Tenant
            $table->foreignId('payment_gateway_id')->constrained()->onDelete('cascade'); // Gateway
            $table->decimal('amount', 8, 2); // Amount
            $table->enum('status', ['pending', 'successful', 'failed'])->default('pending'); // Status
            $table->string('transaction_id')->nullable(); // Gateway transaction ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
