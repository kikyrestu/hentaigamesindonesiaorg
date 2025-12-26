<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'site_meta_title', 'value' => 'Kimochi Gaming - Download Free Games', 'type' => 'text'],
            ['key' => 'site_meta_description', 'value' => 'Download the best games for free. Updated daily.', 'type' => 'textarea'],
            ['key' => 'site_meta_keywords', 'value' => 'games, download, free, pc games, android games', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', ['site_meta_title', 'site_meta_description', 'site_meta_keywords'])->delete();
    }
};
