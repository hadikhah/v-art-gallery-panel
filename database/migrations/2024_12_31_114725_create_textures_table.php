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
        Schema::create('textures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('url');
            $table->string('path');
            $table->boolean('is_default')->default(false);
            $table->string('default_type')->nullable(); //ceiling , wall , floor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textures');
    }
};
