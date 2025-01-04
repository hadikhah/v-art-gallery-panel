<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Texture extends Model
{
    /** @use HasFactory<\Database\Factories\TextureFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
        "title",
        "url",
        "path",
        "is_default",
        "default_type",
    ];

    public function exhibitions()
    {
        return $this->hasMany(Exhibition::class);
    }

}
