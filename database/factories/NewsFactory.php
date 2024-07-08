<?php

namespace Database\Factories;

use App\Enums\News\NewsLayout;
use App\Enums\News\NewsPlace;
use App\Enums\News\NewsStatus;
use App\Models\Author;
use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'slug' => $this->faker->slug(),
            'short_description' => $this->faker->text(),
            'description' => $this->faker->text(),
            'publish_date' => $this->faker->dateTimeBetween('-1 months', 'now'),
            'author_id' => Author::factory(),
            'status' => $this->faker->randomElement(NewsStatus::class),
            'views' => $this->faker->numberBetween(0, 100),
            'sort' => $this->faker->numberBetween(1, 10),
            'place' => $this->faker->randomElement(NewsPlace::class),
            'layout' => $this->faker->randomElement(NewsLayout::class),
        ];
    }
}
