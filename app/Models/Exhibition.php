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


    protected $fillable = [
        "title",
        "slug",
        "description",
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphToMany(Image::class, "imageable");
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
