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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade'); // Tenant
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Subscriber
            $table->foreignId('plan_id')->constrained()->onDelete('cascade'); // Plan
            $table->enum('status', ['active', 'inactive', 'cancelled'])->default('active'); // Status
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending'); // Payment
            $table->dateTime('start_date'); // Start
            $table->dateTime('end_date')->nullable(); // End
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
