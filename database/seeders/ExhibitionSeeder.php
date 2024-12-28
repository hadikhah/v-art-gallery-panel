<?php

namespace Database\Seeders;

use App\Models\Exhibition;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExhibitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->inRandomOrder()->chunk(50, function ($q) {
            $q->each(function ($user) {
                $exhibitions = Exhibition::factory()->count(2)->create(['user_id' => $user->id]);

                // dd($exhibition);
                $exhibitions->each(function ($exhibition) use ($user) {
                    $exhibition->images()->attach($user->images()->first()->id);
                });
            });
        });
    }
}
