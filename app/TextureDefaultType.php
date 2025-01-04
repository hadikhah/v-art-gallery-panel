<?php

namespace App;

enum TextureDefaultType: string
{
    case WALL = "wall";
    case CEILING = "ceiling";
    case FLOOR = "floor";

    public static function all()
    {
        return [
            "WALL" => self::WALL,
            "CEILING" => self::CEILING,
            "FLOOR" => self::FLOOR,
        ];
    }
}
