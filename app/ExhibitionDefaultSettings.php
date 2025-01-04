<?php

namespace App;

enum ExhibitionDefaultSettings
{

    const DEFAULT_MAP_SIZE = 1;
    const DEFAULT_WALL_THICKNESS = 0.2;
    const DEFAULT_CELL_SIZE = 5;
    const DEFAULT_TOTAL_IMAGES = 5;

    public static function DEFAULT_MAP_SIZE()
    {
        return self::DEFAULT_MAP_SIZE;
    }
    public static function DEFAULT_WALL_THICKNESS()
    {
        return self::DEFAULT_WALL_THICKNESS;
    }
    public static function DEFAULT_CELL_SIZE()
    {
        return self::DEFAULT_CELL_SIZE;
    }
    public static function DEFAULT_TOTAL_IMAGES()
    {
        return self::DEFAULT_TOTAL_IMAGES;
    }
    public static function getDefaultSettings()
    {
        return [
            "mapSize" => self::DEFAULT_MAP_SIZE(),
            "cellSize" => self::DEFAULT_CELL_SIZE(),
            "wallThickness" => self::DEFAULT_WALL_THICKNESS()
        ];
    }
}
