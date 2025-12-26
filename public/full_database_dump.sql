SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `description` text,
  `author` varchar(255) DEFAULT NULL,
  `developer` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `censorship` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `download_link` varchar(255) DEFAULT NULL,
  `is_hot` tinyint(1) NOT NULL DEFAULT '0',
  `buy_link` varchar(255) DEFAULT NULL,
  `system_requirements` text,
  `gallery_images` json DEFAULT NULL,
  `download_content` text,
  `password` varchar(255) DEFAULT NULL,
  `installation_guide` text,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `games_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `category_game`;
CREATE TABLE `category_game` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_game_game_id_foreign` (`game_id`),
  KEY `category_game_category_id_foreign` (`category_id`),
  CONSTRAINT `category_game_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `category_game_game_id_foreign` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `navigation_items`;
CREATE TABLE `navigation_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `is_external` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table: users
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Admin', 'admin@kimochi.info', NULL, '$2y$12$7HDRVRBYNWpVN0RMQgBh3.pF4Vl8wrXVJmgnP82Xjns7p.J0TiGoy', NULL, '2025-12-24 14:07:21', '2025-12-24 14:07:21');

-- Data for table: categories
INSERT INTO `categories` (`id`, `name`, `slug`, `color`, `created_at`, `updated_at`) VALUES ('1', 'Android', 'android', '#ffffff', '2025-12-24 17:03:20', '2025-12-24 17:03:20');

-- Data for table: games
INSERT INTO `games` (`id`, `title`, `slug`, `cover_image`, `thumbnail_image`, `description`, `author`, `developer`, `version`, `censorship`, `language`, `platform`, `release_date`, `download_link`, `created_at`, `updated_at`, `is_hot`, `buy_link`, `system_requirements`, `gallery_images`, `download_content`, `password`, `installation_guide`, `meta_title`, `meta_description`, `meta_keywords`) VALUES ('2', 'A Prince’s Tale [v0.4.0]', 'a-princes-tale-v040', 'games/covers/01KD8PXZSZ7QBH5XZ2Y3Y68MVY.png', 'games/thumbnails/01KD8PXZT2RZNNWPE8BGAMEGZJ.webp', '<p>A Prince\'s Tale is a free-to-play text based erotic adventure game. You play as the prince of a fantasy kingdom. Recently, your father, the king, has left the kingdom in your hands. You are expected represent him in the royal council, where each councilor is looking to mentor you in some way, perhaps to benefit their own agenda. Especially the new royal sorceress seems keen on guiding you using her strange transformative magical powers...​</p>', 'Minaarigatou', 'Minaarigatou', '1.4', 'Uncensored', 'English', 'Android', '2025-12-25 00:00:00', 'https://tiny-pastry.itch.io/a-princes-tale', '2025-12-24 17:36:27', '2025-12-24 17:36:27', '0', 'https://tiny-pastry.itch.io/a-princes-tale', '<ul><li><strong>OS:</strong> Windows 7+</li><li><strong>Processor:</strong> Pentium 4</li><li><strong>Memory:</strong> 1 GB</li><li><strong>Graphics:</strong> VRAM 1 GB</li><li><strong>DirectX:</strong> 9.0c</li><li><strong>Storage:</strong> 112.7MB</li></ul><p><br></p>', '[\"games\\/gallery\\/01KD8PXZT3T4WFE2V63FAYTNZ8.webp\",\"games\\/gallery\\/01KD8PXZT5NE0WSJ2KAA7NP78D.webp\",\"games\\/gallery\\/01KD8PXZT6TCN4QXMVTGNA6VYA.webp\"]', '<p>Android (v0.2.9a)</p>', 'kimochi.info', '<p>Please spend time to read <a href=\"https://kimochi.info/faqs\">FAQs</a> before downloading!</p>', NULL, NULL, NULL);

-- Data for table: category_game
INSERT INTO `category_game` (`id`, `game_id`, `category_id`, `created_at`, `updated_at`) VALUES ('2', '2', '1', NULL, NULL);

-- Data for table: site_settings
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('1', 'site_name', 'Kimochi Gaming', 'text', '2025-12-24 16:52:17', '2025-12-24 16:52:17');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('2', 'contact_email', 'admin@kimochi.info', 'text', '2025-12-24 16:52:17', '2025-12-24 16:52:17');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('3', 'discord_link', '#', 'text', '2025-12-24 16:52:17', '2025-12-24 16:52:17');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('4', 'dmca_link', '#', 'text', '2025-12-24 16:52:17', '2025-12-24 16:52:17');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('5', 'footer_text', 'Copyright © 2025 Kimochi Gaming', 'text', '2025-12-24 16:52:17', '2025-12-24 16:52:17');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('6', 'social_facebook', '#', 'text', '2025-12-24 17:20:44', '2025-12-24 17:20:44');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('7', 'social_twitter', '#', 'text', '2025-12-24 17:20:44', '2025-12-24 17:20:44');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('8', 'social_linkedin', '#', 'text', '2025-12-24 17:20:44', '2025-12-24 17:20:44');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('9', 'social_pinterest', '#', 'text', '2025-12-24 17:20:44', '2025-12-24 17:20:44');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('10', 'social_email', 'mailto:admin@kimochi.info', 'text', '2025-12-24 17:20:44', '2025-12-24 17:20:44');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('11', 'site_logo', 'settings/01KD8R1ZFFVE8QQ7G215TW5QS2.png', 'image', '2025-12-24 17:54:40', '2025-12-24 17:56:06');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('12', 'site_favicon', 'settings/01KD8RYCDYB7YXFGX0BZVV7VV4.png', 'image', '2025-12-24 18:11:17', '2025-12-24 18:11:37');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('13', 'site_meta_title', 'Kimochi Gaming - Download Free Games', 'text', '2025-12-24 19:45:56', '2025-12-24 19:45:56');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('14', 'site_meta_description', 'Download the best games for free. Updated daily.', 'textarea', '2025-12-24 19:45:56', '2025-12-24 19:45:56');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('15', 'site_meta_keywords', 'games, download, free, pc games, android games', 'textarea', '2025-12-24 19:45:56', '2025-12-24 19:45:56');
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES ('16', 'google_site_verification', NULL, 'text', '2025-12-24 19:52:06', '2025-12-24 19:52:06');

-- Data for table: navigation_items
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('3', 'Games', 'home', '0', '0', '2025-12-24 17:10:25', '2025-12-24 17:10:32');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('4', 'FAQs', 'faqs', '1', '0', '2025-12-24 17:11:41', '2025-12-24 17:11:41');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('5', 'Report Dead Link', 'https://discord.com/', '3', '1', '2025-12-24 17:12:39', '2025-12-24 17:12:39');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('6', 'Android', 'category/android', '4', '0', '2025-12-24 17:13:05', '2025-12-24 17:13:05');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('7', 'Advance Search', '/advance-search', '5', '0', '2025-12-24 17:13:23', '2025-12-24 17:42:51');

-- Data for table: migrations
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2025_12_24_141652_create_categories_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2025_12_24_141653_create_category_game_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2025_12_24_141653_create_games_table', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2025_12_24_145517_create_faqs_table', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2025_12_24_150000_create_navigation_items_table', '4');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2025_12_24_150001_create_site_settings_table', '4');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2025_12_24_150002_add_is_hot_to_games_table', '4');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2025_12_25_100000_add_buy_link_and_socials', '5');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2025_12_25_110000_add_tabs_content_to_games', '6');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2025_12_25_120000_add_site_logo_setting', '7');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('14', '2025_12_25_130000_add_site_favicon_setting', '8');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('15', '2025_12_25_140000_add_seo_fields_to_games_table', '9');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('16', '2025_12_25_140001_add_seo_settings', '9');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('17', '2025_12_25_150000_add_google_verification', '10');

SET FOREIGN_KEY_CHECKS=1;
