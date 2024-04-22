<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SousCategory>
 */
class SousCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Fruit',
            'category_id' => 1,
        ];
    }

    public function legume(): Factory
    {
        return $this->state([
            'name' => 'legume',
            'category_id' =>1,
        ]);
    }
    public function cereale(): Factory
    {
        return $this->state([
            'name' => 'cereale',
            'category_id' =>1,
        ]);
    }
    public function tubercule(): Factory
    {
        return $this->state([
            'name' => 'tubercule',
            'category_id' =>1,
        ]);
    }
    public function bovin(): Factory
    {
        return $this->state([
            'name' => 'bovin',
            'category_id' =>2,
        ]);
    }
    public function volaille(): Factory
    {
        return $this->state([
            'name' => 'volaille',
            'category_id' =>2,
        ]);
    }
    public function ovin(): Factory
    {
        return $this->state([
            'name' => 'ovin',
            'category_id' =>2,
        ]);
    }
    public function caprin(): Factory
    {
        return $this->state([
            'name' => 'caprin',
            'category_id' =>2,
        ]);
    }
}
