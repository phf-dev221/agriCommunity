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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Recipient
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade'); // Tenant
            $table->string('type'); // e.g., "order_created"
            $table->json('data'); // e.g., { "order_id": 123 }
            $table->enum('channel', ['internal', 'email', 'both'])->default('internal'); // Channel
            $table->enum('status', ['pending', 'sent', 'failed', 'unread', 'read'])->default('pending'); // Status
            $table->dateTime('sent_at')->nullable(); // Sent date
            $table->dateTime('read_at')->nullable(); // Read date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
