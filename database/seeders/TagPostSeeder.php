<?php

namespace Database\Seeders;
use App\Models\TagPost;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TagPost::factory()
        ->create([
            'tag_id' => 1,
            'post_id' => 1,
        ]);
        TagPost::factory()
        ->create([
            'tag_id' => 2,
            'post_id' => 2,
        ]);
        TagPost::factory()
        ->create([
            'tag_id' => 3,
            'post_id' => 2,
        ]);
        TagPost::factory()
        ->create([
            'tag_id' => 4,
            'post_id' => 3,
        ]);
        TagPost::factory()
        ->create([
            'tag_id' => 5,
            'post_id' => 4,
        ]);
    }
}
