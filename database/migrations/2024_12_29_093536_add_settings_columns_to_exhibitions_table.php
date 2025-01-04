<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exhibitions', function (Blueprint $table) {
            $table->unsignedBigInteger('ceiling_texture_id')->nullable()->after("description");
            $table->unsignedBigInteger('floor_texture_id')->nullable()->after("description");
            $table->unsignedBigInteger('wall_texture_id')->nullable()->after("description");

            $table->float("wall_thickness")->default(0.2)->after("description");
            $table->smallInteger("cell_size")->default(5)->after("description");
            $table->smallInteger("map_size")->default(1)->after("description");

            $table->boolean("has_background_song")->default(0)->after("description");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitions', function (Blueprint $table) {
            $table->dropColumn('ceiling_texture');
            $table->dropColumn('floor_texture');
            $table->dropColumn('wallTexture');
            $table->dropColumn('map_size');
            $table->dropColumn('wallThickness');
            $table->dropColumn('cellSize');
        });
    }
};
