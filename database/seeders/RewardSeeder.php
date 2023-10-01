<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reward::factory()->create([
            'title' => 'Jeune Pousse',
            'type' => 'badge',
            'point' => 0
        ]);
        Reward::factory()->create([
            'title' => 'Bourgeon Actif',
            'type' => 'badge',
            'point' => 250
        ]);
        Reward::factory()->create([
            'title' => 'Tige Engagée',
            'type' => 'badge',
            'point' => 1000
        ]);
        Reward::factory()->create([
            'title' => 'Arbre Protecteur',
            'type' => 'badge',
            'point' => 5000
        ]);
        Reward::factory()->create([
            'title' => 'Forêt Exemplaire',
            'type' => 'badge',
            'point' => 15000
        ]);
        Reward::factory()->create([
            'title' => 'Trophée',
            'type' => 'trophy',
            'point' => 250
        ]);
    }
}
