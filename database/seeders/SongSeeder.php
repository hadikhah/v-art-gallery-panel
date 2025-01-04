<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $songsPath = "v_art_gallery/songs";
        $thumbnailBasePath = "v_art_gallery/songs/thumbnails";


        $songFiles = Storage::disk("liara")->files($songsPath);

        // dd($songFiles);
        foreach ($songFiles as $key => $file) {


            $name = explode("/", $file)[2];
            $title = explode(".", $name)[0];
            $thumbnailPath = $thumbnailBasePath . "/" . $title . "." . "jpg";
            $url = Storage::disk("liara")->url($file);
            $thumbnailUrl = Storage::disk("liara")->url($thumbnailPath);

            Song::create([
                "name" => $name,
                "title" => $title,
                "path" => $file,
                "url" => $url,
                "thumbnail_path" => $thumbnailPath,
                "thumbnail_url" => $thumbnailUrl,
            ]);
        }
    }
}
