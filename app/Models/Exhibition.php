<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    /** @use HasFactory<\Database\Factories\ExhibitionFactory> */
    use HasFactory;

    const STATUS_PRIVATE = "private";
    const STATUS_PUBLIC = "public";

    public static $statusList = [self::STATUS_PRIVATE, self::STATUS_PUBLIC];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'has_background_song' => 'boolean',
    ];

    protected $fillable = [
        "title",
        "slug",
        "description",
        "status",

        "ceiling_texture_id",
        "wall_texture_id",
        "floor_texture_id",

        "has_background_song",

        "wall_thickness",
        "cell_size",
        "map_size",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphToMany(Image::class, "imageable");
    }
    public function ceilingTexture()
    {
        return $this->belongsTo(Texture::class, 'ceiling_texture_id');
    }

    public function floorTexture()
    {
        return $this->belongsTo(Texture::class, 'floor_texture_id');
    }

    public function wallTexture()
    {
        return $this->belongsTo(Texture::class, 'wall_texture_id');
    }

    public function songs()
    {
        return $this->morphToMany(Song::class, 'songable');
    }

    public function viewRate()
    {
        return $this->hasMany(ExhibitionView::class);
    }

    public static function getStatusList()
    {
        return [
            [

                "key" => "STATUS_PUBLIC",
                "value" => self::STATUS_PUBLIC,

            ],
            [

                "key" => "STATUS_PRIVATE",
                "value" => self::STATUS_PRIVATE
            ]
        ];
    }
}
