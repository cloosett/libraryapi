<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Створюємо 50 книг
        Book::factory(50)->create();

        // Увімкнути перевірку зовнішніх ключів назад
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
