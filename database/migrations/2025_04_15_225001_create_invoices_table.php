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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Order
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Buyer
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade'); // Tenant
            $table->string('invoice_number')->unique(); // Unique invoice ID
            $table->decimal('amount', 8, 2); // Amount
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending'); // Status
            $table->dateTime('issued_at'); // Issue date
            $table->dateTime('due_at'); // Due date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
