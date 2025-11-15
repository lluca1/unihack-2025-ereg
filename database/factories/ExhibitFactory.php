<?php

namespace Database\Factories;

use App\Models\Exhibit;
use App\Models\Exposition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Exhibit>
 */
class ExhibitFactory extends Factory
{
    protected $model = Exhibit::class;

    public function definition(): array
    {
        return [
            'exposition_id' => Exposition::factory(),
            'user_id' => User::factory(),
            'title' => fake()->words(2, true),
            'description' => fake()->sentence(10),
            'media_type' => '3d-model',
            'media_path' => 'models/'.$this->faker->uuid.'.obj',
            'thumbnail_path' => null,
            'mime_type' => 'text/plain',
            'position' => fake()->numberBetween(0, 10),
        ];
    }
}
