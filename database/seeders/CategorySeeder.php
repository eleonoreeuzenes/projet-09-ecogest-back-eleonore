<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {      
        Category::factory()->create([
            'title' => 'Mobilité'
        ]);
        Category::factory()->create([
            'title' => 'Alimentation'
        ]);
        Category::factory()->create([
            'title' => 'Déchets'
        ]);
        Category::factory()->create([
            'title' => 'Biodiversité'
        ]);
        Category::factory()->create([
            'title' => 'Energie'
        ]);
        Category::factory()->create([
            'title' => 'Do It Yourself'
        ]);
        Category::factory()->create([
            'title' => 'Technologies'
        ]);
        Category::factory()->create([
            'title' => 'Seconde vie'
        ]);
        Category::factory()->create([
            'title' => 'Divers'
        ]);
    }
}