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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->text('description')->nullable();
            $table->string('author')->nullable();
            $table->string('developer')->nullable();
            $table->string('version')->nullable();
            $table->string('censorship')->nullable();
            $table->string('language')->nullable();
            $table->string('platform')->nullable();
            $table->date('release_date')->nullable();
            $table->string('download_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
