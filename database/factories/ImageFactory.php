<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post; 

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->randomElement([ 
            '/images/hug.jpg',
            '/images/kiss.jpg',
        ]),
            'post_id' => Post::factory(), // Creates a new post if not provided
        ];
    }
}
