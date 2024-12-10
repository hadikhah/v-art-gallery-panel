<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->inRandomOrder()->chunk(100, function ($q) {
            $q->each(function ($user) {
                Image::factory()->count(5)->create(['user_id' => $user->id]);
            });
        });
    }
}
