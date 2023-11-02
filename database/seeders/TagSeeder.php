<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::factory()
        ->create(['label' => 'velotaf']);
        Tag::factory()
        ->create(['label' => 'alcool']);
        Tag::factory()
        ->create(['label' => 'consommerlocal']);
        Tag::factory()
        ->create(['label' => 'musique']);
        Tag::factory()
        ->create(['label' => 'tech']);
    }
}
