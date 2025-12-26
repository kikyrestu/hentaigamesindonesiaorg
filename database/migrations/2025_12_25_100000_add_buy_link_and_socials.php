<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add buy_link to games table
        Schema::table('games', function (Blueprint $table) {
            $table->string('buy_link')->nullable()->after('download_link');
        });

        // 2. Add Social Media keys to site_settings
        $socials = [
            ['key' => 'social_facebook', 'value' => '#', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_twitter', 'value' => '#', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_linkedin', 'value' => '#', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_pinterest', 'value' => '#', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_email', 'value' => 'mailto:admin@kimochi.info', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('site_settings')->insert($socials);
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('buy_link');
        });

        DB::table('site_settings')->whereIn('key', [
            'social_facebook', 'social_twitter', 'social_linkedin', 'social_pinterest', 'social_email'
        ])->delete();
    }
};
