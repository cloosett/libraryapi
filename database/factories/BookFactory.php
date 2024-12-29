<?php

namespace Database\Factories;

use App\Models\Book;
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

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3), // Фейкове ім'я книги
            'author' => $this->faker->name, // Фейкове ім'я автора
            'year' => $this->faker->year, // Фейковий рік
            'genre' => $this->faker->word, // Фейковий жанр
            'description' => $this->faker->text(200), // Фейковий опис книги
            'user_id' => 9999,
            'image' => 'img/book.jpg',
        ];
    }
}
