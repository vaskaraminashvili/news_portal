<?php

namespace Database\Factories;

use App\Enums\AuthorStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Author;

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'surname' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'status' => $this->faker->randomElement(AuthorStatus::class),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
