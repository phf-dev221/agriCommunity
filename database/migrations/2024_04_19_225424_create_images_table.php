<?php

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url'); // URL ou chemin de l’image
            $table->foreignIdFor(Product::class)->constrained()->onDelete('cascade'); // Produit
            $table->foreignIdFor(Tenant::class)->constrained()->onDelete('cascade'); // Tenant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
