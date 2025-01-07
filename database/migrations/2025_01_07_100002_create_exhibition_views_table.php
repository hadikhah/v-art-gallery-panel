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
        Schema::create('exhibition_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exhibition_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('view_date')->index(); // Store the date of the view
            $table->tinyInteger('view_hour')->index(); // Store the hour (0-23)
            $table->unsignedInteger('views')->default(0); // Number of views for that hour
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibition_views');
    }
};
