<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FullBackupSeeder extends Seeder
{
    public function run()
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // users
        DB::table('users')->truncate();
        DB::table('users')->insert(
array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Admin',
    'email' => 'admin@kimochi.info',
    'email_verified_at' => NULL,
    'password' => \Illuminate\Support\Facades\Hash::make('password'),
    'remember_token' => NULL,
    'created_at' => '2025-12-24 14:07:21',
    'updated_at' => '2025-12-24 14:07:21',
  ),
)
        );

        // categories
        DB::table('categories')->truncate();
        DB::table('categories')->insert(
array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Android',
    'slug' => 'android',
    'color' => '#ffffff',
    'created_at' => '2025-12-24 17:03:20',
    'updated_at' => '2025-12-24 17:03:20',
  ),
)
        );

        // games
        DB::table('games')->truncate();
        DB::table('games')->insert(
array (
  0 => 
  array (
    'id' => 2,
    'title' => 'A Prince’s Tale [v0.4.0]',
    'slug' => 'a-princes-tale-v040',
    'cover_image' => 'games/covers/01KD8PXZSZ7QBH5XZ2Y3Y68MVY.png',
    'thumbnail_image' => 'games/thumbnails/01KD8PXZT2RZNNWPE8BGAMEGZJ.webp',
    'description' => '<p>A Prince\'s Tale is a free-to-play text based erotic adventure game. You play as the prince of a fantasy kingdom. Recently, your father, the king, has left the kingdom in your hands. You are expected represent him in the royal council, where each councilor is looking to mentor you in some way, perhaps to benefit their own agenda. Especially the new royal sorceress seems keen on guiding you using her strange transformative magical powers...​</p>',
    'author' => 'Minaarigatou',
    'developer' => 'Minaarigatou',
    'version' => '1.4',
    'censorship' => 'Uncensored',
    'language' => 'English',
    'platform' => 'Android',
    'release_date' => '2025-12-25 00:00:00',
    'download_link' => 'https://tiny-pastry.itch.io/a-princes-tale',
    'created_at' => '2025-12-24 17:36:27',
    'updated_at' => '2025-12-24 17:36:27',
    'is_hot' => 0,
    'buy_link' => 'https://tiny-pastry.itch.io/a-princes-tale',
    'system_requirements' => '<ul><li><strong>OS:</strong> Windows 7+</li><li><strong>Processor:</strong> Pentium 4</li><li><strong>Memory:</strong> 1 GB</li><li><strong>Graphics:</strong> VRAM 1 GB</li><li><strong>DirectX:</strong> 9.0c</li><li><strong>Storage:</strong> 112.7MB</li></ul><p><br></p>',
    'gallery_images' => '["games\\/gallery\\/01KD8PXZT3T4WFE2V63FAYTNZ8.webp","games\\/gallery\\/01KD8PXZT5NE0WSJ2KAA7NP78D.webp","games\\/gallery\\/01KD8PXZT6TCN4QXMVTGNA6VYA.webp"]',
    'download_content' => '<p>Android (v0.2.9a)</p>',
    'password' => 'kimochi.info',
    'installation_guide' => '<p>Please spend time to read <a href="https://kimochi.info/faqs">FAQs</a> before downloading!</p>',
    'meta_title' => NULL,
    'meta_description' => NULL,
    'meta_keywords' => NULL,
  ),
)
        );

        // category_game
        DB::table('category_game')->truncate();
        DB::table('category_game')->insert(
array (
  0 => 
  array (
    'id' => 2,
    'game_id' => 2,
    'category_id' => 1,
    'created_at' => NULL,
    'updated_at' => NULL,
  ),
)
        );

        // site_settings
        DB::table('site_settings')->truncate();
        DB::table('site_settings')->insert(
array (
  0 => 
  array (
    'id' => 1,
    'key' => 'site_name',
    'value' => 'Kimochi Gaming',
    'type' => 'text',
    'created_at' => '2025-12-24 16:52:17',
    'updated_at' => '2025-12-24 16:52:17',
  ),
  1 => 
  array (
    'id' => 2,
    'key' => 'contact_email',
    'value' => 'admin@kimochi.info',
    'type' => 'text',
    'created_at' => '2025-12-24 16:52:17',
    'updated_at' => '2025-12-24 16:52:17',
  ),
  2 => 
  array (
    'id' => 3,
    'key' => 'discord_link',
    'value' => '#',
    'type' => 'text',
    'created_at' => '2025-12-24 16:52:17',
    'updated_at' => '2025-12-24 16:52:17',
  ),
  3 => 
  array (
    'id' => 4,
    'key' => 'dmca_link',
    'value' => '#',
    'type' => 'text',
    'created_at' => '2025-12-24 16:52:17',
    'updated_at' => '2025-12-24 16:52:17',
  ),
  4 => 
  array (
    'id' => 5,
    'key' => 'footer_text',
    'value' => 'Copyright © 2025 Kimochi Gaming',
    'type' => 'text',
    'created_at' => '2025-12-24 16:52:17',
    'updated_at' => '2025-12-24 16:52:17',
  ),
  5 => 
  array (
    'id' => 6,
    'key' => 'social_facebook',
    'value' => '#',
    'type' => 'text',
    'created_at' => '2025-12-24 17:20:44',
    'updated_at' => '2025-12-24 17:20:44',
  ),
  6 => 
  array (
    'id' => 7,
    'key' => 'social_twitter',
    'value' => '#',
    'type' => 'text',
    'created_at' => '2025-12-24 17:20:44',
    'updated_at' => '2025-12-24 17:20:44',
  ),
  7 => 
  array (
    'id' => 8,
    'key' => 'social_linkedin',
    'value' => '#',
    'type' => 'text',
    'created_at' => '2025-12-24 17:20:44',
    'updated_at' => '2025-12-24 17:20:44',
  ),
  8 => 
  array (
    'id' => 9,
    'key' => 'social_pinterest',
    'value' => '#',
    'type' => 'text',
    'created_at' => '2025-12-24 17:20:44',
    'updated_at' => '2025-12-24 17:20:44',
  ),
  9 => 
  array (
    'id' => 10,
    'key' => 'social_email',
    'value' => 'mailto:admin@kimochi.info',
    'type' => 'text',
    'created_at' => '2025-12-24 17:20:44',
    'updated_at' => '2025-12-24 17:20:44',
  ),
  10 => 
  array (
    'id' => 11,
    'key' => 'site_logo',
    'value' => 'settings/01KD8R1ZFFVE8QQ7G215TW5QS2.png',
    'type' => 'image',
    'created_at' => '2025-12-24 17:54:40',
    'updated_at' => '2025-12-24 17:56:06',
  ),
  11 => 
  array (
    'id' => 12,
    'key' => 'site_favicon',
    'value' => 'settings/01KD8RYCDYB7YXFGX0BZVV7VV4.png',
    'type' => 'image',
    'created_at' => '2025-12-24 18:11:17',
    'updated_at' => '2025-12-24 18:11:37',
  ),
  12 => 
  array (
    'id' => 13,
    'key' => 'site_meta_title',
    'value' => 'Kimochi Gaming - Download Free Games',
    'type' => 'text',
    'created_at' => '2025-12-24 19:45:56',
    'updated_at' => '2025-12-24 19:45:56',
  ),
  13 => 
  array (
    'id' => 14,
    'key' => 'site_meta_description',
    'value' => 'Download the best games for free. Updated daily.',
    'type' => 'textarea',
    'created_at' => '2025-12-24 19:45:56',
    'updated_at' => '2025-12-24 19:45:56',
  ),
  14 => 
  array (
    'id' => 15,
    'key' => 'site_meta_keywords',
    'value' => 'games, download, free, pc games, android games',
    'type' => 'textarea',
    'created_at' => '2025-12-24 19:45:56',
    'updated_at' => '2025-12-24 19:45:56',
  ),
  15 => 
  array (
    'id' => 16,
    'key' => 'google_site_verification',
    'value' => NULL,
    'type' => 'text',
    'created_at' => '2025-12-24 19:52:06',
    'updated_at' => '2025-12-24 19:52:06',
  ),
)
        );

        // navigation_items
        DB::table('navigation_items')->truncate();
        DB::table('navigation_items')->insert(
array (
  0 => 
  array (
    'id' => 3,
    'label' => 'Games',
    'url' => 'home',
    'sort_order' => 0,
    'is_external' => 0,
    'created_at' => '2025-12-24 17:10:25',
    'updated_at' => '2025-12-24 17:10:32',
  ),
  1 => 
  array (
    'id' => 4,
    'label' => 'FAQs',
    'url' => 'faqs',
    'sort_order' => 1,
    'is_external' => 0,
    'created_at' => '2025-12-24 17:11:41',
    'updated_at' => '2025-12-24 17:11:41',
  ),
  2 => 
  array (
    'id' => 5,
    'label' => 'Report Dead Link',
    'url' => 'https://discord.com/',
    'sort_order' => 3,
    'is_external' => 1,
    'created_at' => '2025-12-24 17:12:39',
    'updated_at' => '2025-12-24 17:12:39',
  ),
  3 => 
  array (
    'id' => 6,
    'label' => 'Android',
    'url' => 'category/android',
    'sort_order' => 4,
    'is_external' => 0,
    'created_at' => '2025-12-24 17:13:05',
    'updated_at' => '2025-12-24 17:13:05',
  ),
  4 => 
  array (
    'id' => 7,
    'label' => 'Advance Search',
    'url' => '/advance-search',
    'sort_order' => 5,
    'is_external' => 0,
    'created_at' => '2025-12-24 17:13:23',
    'updated_at' => '2025-12-24 17:42:51',
  ),
)
        );

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
