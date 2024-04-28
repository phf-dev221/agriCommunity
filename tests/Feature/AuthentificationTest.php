<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthentificationTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */


    public function testRegisterUnsuccessfully()
    {
       
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' =>'password123',
            'phone' => '77000000012',
            'role_id' => 1,
            'address' => $this->faker->address()

            
        ];

        $response = $this->postJson('/api/register', $data);

        // Vérifier que la réponse est correcte avec le code HTTP 422
        $response->assertStatus(422);


    }


    public function testRegisterSuccessfully()
    {
        // Générer des données valides pour l'inscription d'un patient
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' =>'password123',
            'phone' => '770000000',
            'role_id' => 1,
            'address' => $this->faker->address()

            
        ];

        $response = $this->postJson('/api/register', $data);

        // Vérifier que la réponse est correcte avec le code HTTP 201
        $response->assertStatus(201);

        // Vérifier que l'utilisateur a été correctement enregistré dans la base de données
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'role_id' => 1,
        ]);
    }

 public function testLoginUsers()
    {
        // Créer un utilisateur de test
         User::factory()->create([
            'name'=> $this->faker->name,
            'email' => 'texto@example.com',
            'password' => bcrypt('password123'),
            'phone'=>'770000111',
           
            'role_id' => 1,
            'address' => "keur gorgi",
            
        ]);


    

        // Envoyer une requête de connexion avec des informations d'identification valides
        $response = $this->postJson('/api/login', [
            'email' => 'texto@example.com',
            'password' => 'password123',
        ]);

        // Vérifier que la réponse est correcte
        $response->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);

        $this->assertAuthenticated();

    }

    public function testUserLogoutSuccessfully()
{
    // Créer un utilisateur de test
    $user = User::factory()->create([
        'name'=> $this->faker->name,
        'email' => 'tutr@example.com',
        'password' => bcrypt('password123'),
        'phone'=>'770111111',
       
        'role_id' => 1,
        'address' => '123 Main'
        
    ]);

    $token = JWTAuth::fromUser($user);
 
    $response = $this->withHeader('Authorization', 'Bearer' . $token)
                     ->get('/api/logout');
    $response->assertStatus(200);


    // Vérifier que l'utilisateur n'est pas authentifié après la déconnexion
    $this->assertGuest();
}

// public function testBloquerUserSuccessfully()
// {
//     // Créer un administrateur de test
//     $admin = User::factory()->create([
//         'nom'=> $this->faker->name,
//         'email' => 'abdxw@example.com',
//         'password' => bcrypt('password123'),
//         'telephone'=>'771111111',
//         'genre' => 'homme',
//         'role_id' => 1,
//         'ville_id' => 1,
//         'is_blocked' => 0,
//     ]);

//     // Connecter l'administrateur
    

//     $token = JWTAuth::fromUser($admin);
//     $this->withHeader('Authorization', 'Bearer' . $token)
//                      ->post('/api/login');

//     // Créer un utilisateur de test
//     $user = User::factory()->create([
//         'nom'=> $this->faker->name,
//         'email' => 'jea@example.com',
//         'password' => bcrypt('password123'),
//         'telephone'=>'771111112',
//         'genre' => 'homme',
//         'role_id' => 3,
//         'ville_id' => 1,
//         'is_blocked' => 0,
//     ]);

//     // Envoyer une requête pour bloquer l'utilisateur
//     $response = $this->getJson('/api/bloquer-user/' . $user->id);

//     // Vérifier que la réponse est correcte avec le message attendu
//     $response->assertStatus(200)
//         ->assertJson([
//             'message' => 'utilisateur bloqué avec succès',
//         ]);

//     // Relire l'utilisateur depuis la base de données pour s'assurer qu'il est réellement bloqué
//     $user = User::findOrFail($user->id);
   
//     $this->assertEquals(1, $user->is_blocked);
// }

}