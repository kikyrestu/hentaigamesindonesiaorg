<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            ['key' => 'site_name', 'value' => 'Kimochi Gaming', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_email', 'value' => 'admin@kimochi.info', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'discord_link', 'value' => '#', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'dmca_link', 'value' => '#', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'footer_text', 'value' => 'Copyright © 2025 Kimochi Gaming', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
