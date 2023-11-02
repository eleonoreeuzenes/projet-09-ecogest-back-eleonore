<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RewardSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            UserPointCategorySeeder::class,
            TagSeeder::class,
            PostSeeder::class,
            TagPostSeeder::class
        ]);
    }
}
