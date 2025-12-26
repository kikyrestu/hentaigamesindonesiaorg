<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->text('system_requirements')->nullable();
            $table->json('gallery_images')->nullable();
            $table->text('download_content')->nullable(); // For custom download text/buttons
            $table->string('password')->default('kimochi.info');
            $table->text('installation_guide')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn([
                'system_requirements', 
                'gallery_images', 
                'download_content', 
                'password', 
                'installation_guide'
            ]);
        });
    }
};
