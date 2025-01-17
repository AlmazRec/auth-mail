<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'author' => $this->faker->name,
            'year' => $this->faker->numberBetween(2010, 2025),
            'price' => $this->faker->numberBetween(299, 1999)
        ];

        // 'title' => 'required',
        //     'description' => 'required',
        //     'author' => 'required',
        //     'date' => 'required|int',
        //     'price' => 'required|int'
    }
}
