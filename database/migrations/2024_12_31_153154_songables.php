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
        Schema::create('songables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("song_id");
            $table->unsignedBigInteger("songable_id");
            $table->string("songable_type");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songables');
    }
};
