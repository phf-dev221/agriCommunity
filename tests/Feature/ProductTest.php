<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use WithFaker;
    protected function authenticateUser($telephone)
    {
        // Créer un utilisateur de test
        $user = User::factory()->create([
            'name'=> $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password123'),
            'phone'=>$telephone,
            'role_id' => 1,
            'address' => 'ouakam',
        ]);



        $this->actingAs($user);

        return $user;
    }

    protected function createProduct($user)
    {
  
return [
    'name' => $this->faker->name,
    'location' => $this->faker->address(),
    'description' => $this->faker->paragraph,
    'status' => 'available',
    'sous_category_id' => 1,
   // 'image' => UploadedFile::fake()->image('article.jpg'),
    'user_id' => $user->id,
    
];
        
    }


    public function testIndex()
    {
       $user = $this->authenticateUser('778803200');

       Product::factory()->count(5)->create($this->createProduct($user));

        $response = $this->getJson(route('product.index'));

        $response->assertStatus(200);

    }

    public function testStore()
    {
        $user = $this->authenticateUser('781590082');
    
        $response = $this->postJson(route('product.store'), [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'location' => $this->faker->address(),
            'status' => 'available',
            'sous_category_id' => 1,
            'image' => [UploadedFile::fake()->image('article.jpg')],
            // Notez que 'image' est envoyé sous forme de tableau selon la règle de validation 'image' => 'required|array'
            'user_id' => $user->id,
        ]);
    
        $response->assertStatus(201);
    }

    public function testShow()
    {
       $user = $this->authenticateUser('773579461');

       $user = Product::factory()->create( $this->createProduct($user));

        $response = $this->getJson(route('product.show', 1));

        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $user = $this->authenticateUser('771234569');

        $product = Product::factory()->create($this->createProduct($user));



        $response = $this->postJson(route('product.update', $product->id), [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'location' => $this->faker->address(),
            'status' => 'available',
            'sous_category_id' => 1,
            'image' => [UploadedFile::fake()->image('article.jpg')],
            // Notez que 'image' est envoyé sous forme de tableau selon la règle de validation 'image' => 'required|array'
           
        ]);

        $response->assertStatus(200);

      //  Storage::disk('public')->delete($response['article']['image']);
    }

    public function testDestroy()
    {
        $user = $this->authenticateUser('774569874');

        $product = Product::factory()->create($this->createProduct($user));

        $response = $this->deleteJson(route('product.destroy', $product->id));

        $response->assertStatus(200)
            ->assertJson([
                'message' =>  "Product deleted successfully",
            ]);
    }
}