<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    /** @use HasFactory<\Database\Factories\SongFactory> */
    use HasFactory;


    protected $fillable = [
        "title",
        "name",
        "path",
        "url",
        "thumbnail_path",
        "thumbnail_url",
    ];

    public function exhibition()
    {
        return $this->morphedByMany(Exhibition::class, 'songable');
    }
}
