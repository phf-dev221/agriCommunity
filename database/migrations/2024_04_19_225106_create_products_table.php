<?php

use App\Models\Categorie;

use App\Models\SousCategorie;
use App\Models\Tenant;
use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du produit
            $table->text('description')->nullable(); // Description
            $table->decimal('price', 8, 2); // Prix
            $table->integer('stock')->default(0); // Stock
            $table->enum('status', ['available', 'unavailable'])->default('available'); // Statut
            $table->foreignIdFor(Categorie::class)->constrained()->onDelete('cascade'); // Catégorie
            $table->foreignIdFor(SousCategorie::class)->nullable()->constrained()->onDelete('set null'); // Sous-catégorie
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade'); // Créateur
            $table->foreignIdFor(Tenant::class)->constrained()->onDelete('cascade'); // Tenant
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
