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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade'); // Tenant (null for global)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Creator
            $table->string('title'); // Title
            $table->text('content'); // Content
            $table->boolean('is_active')->default(true); // Active status
            $table->dateTime('published_at'); // Publish date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
