<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory()
        ->create([
            'category_id' => 1,
            'author_id' => 1,
            'tag' => '{velotaf}',
            'title' => 'Semaine de vélo taf',
            'description' => 'Semaine de vélo taf',
            'type' => 'challenge',
            'level' => 'medium'
        ]);
        Post::factory()
        ->create([
            'category_id' => 2,
            'author_id' => 3,
            'tag' => '{alcool, consommerlocal}',
            'title' => "Acheter de l'alcool local",
            'description' => "Acheter de l'alcool local pour le consommmer AVEC ou SANS mon voisin",
            'type' => 'action',
            'level' => 'easy'
        ]);
        Post::factory()
        ->create([
            'category_id' => 7,
            'author_id' => 4,
            'tag' => '{musique}',
            'title' => 'Spotify en mode hors-ligne',
            'description' => 'Spotify en mode hors-ligne pour acheter moins de vinyle !',
            'type' => 'action',
            'level' => 'easy'
        ]);
        Post::factory()
        ->create([
            'category_id' => 7,
            'author_id' => 2,
            'tag' => '{tech}',
            'title' => 'Recycler mon PC en le passant sous linux',
            'description' => 'Recycler mon PC en le passant sous linux',
            'type' => 'action',
            'level' => 'easy'
        ]);
    }
}
