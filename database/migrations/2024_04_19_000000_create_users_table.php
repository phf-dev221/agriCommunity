<?php


use App\Models\Tenant;
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
        
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Prénom
              
                $table->string('email')->unique(); // E-mail
                $table->string('password'); // Mot de passe $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Rôle (superadmin, etc.)
                $table->foreignIdFor(Tenant::class)->nullable()->constrained()->onDelete('cascade'); // Tenant (null pour superadmin)
                $table->string('address')->nullable(); // Adresse
                $table->string('phone')->nullable(); // Téléphone
                $table->string('google_id')->nullable();
                $table->string('facebook_id')->nullable();
                $table->string('avatar')->nullable();
                $table->timestamp('email_verified_at')->nullable(); // Vérification e-mail
                $table->rememberToken(); // Token pour "Se souvenir de moi"
                $table->timestamps();
            });
    

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
