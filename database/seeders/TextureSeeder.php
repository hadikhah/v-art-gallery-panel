<?php

namespace Database\Seeders;

use App\Models\Texture;
use App\TextureDefaultType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TextureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $texturesPath = "textures";

        $default_ceiling = "textures/Gray Waves.jpg";
        $default_wall = "textures/polished gray.jpeg";
        $default_floor = "textures/Serene Sky Wash.jpeg";

        $files = Storage::disk("liara")->files($texturesPath);

        foreach ($files as $file) {

            $is_default = true;
            $default_type = null;

            switch ($file) {
                case $default_ceiling:
                    $default_type = TextureDefaultType::CEILING;
                    break;

                case $default_wall:
                    $default_type = TextureDefaultType::WALL;
                    break;

                case $default_floor:
                    $default_type = TextureDefaultType::FLOOR;
                    break;

                default:
                    $is_default = false;
                    break;
            }

            $name = explode("/", $file)[1];
            $title = explode(".", $name)[1];

            Texture::create([
                "title" => $title,
                "name" => $name,
                "path" => $file,
                "url" => Storage::disk("liara")->url($file),
                "is_default" => $is_default,
                "default_type" => $default_type
            ]);
        }

        // dd($files);
    }
}
