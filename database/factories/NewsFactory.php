<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\News;

class NewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = News::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'short_description' => $this->faker->text(),
            'description' => $this->faker->text(),
            'publish_date' => $this->faker->dateTime(),
            'author_id' => $this->faker->numberBetween(-10000, 10000),
            'status' => $this->faker->word(),
            'views' => $this->faker->numberBetween(-10000, 10000),
            'sort' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}
