<?php

namespace Database\Factories;

use App\Models\Exhibition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exhibition>
 */
class ExhibitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        $status = [Exhibition::STATUS_PRIVATE, Exhibition::STATUS_PUBLIC];

        return [
            "title" => $title,
            "slug" => Str::slug($title, "-"),
            "description" => $this->faker->paragraph,
            "status" => $this->faker->randomElement($status),
        ];
    }
}
