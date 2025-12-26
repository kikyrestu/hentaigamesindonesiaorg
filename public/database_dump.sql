SET FOREIGN_KEY_CHECKS=0;

-- Table: users
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Admin', 'admin@kimochi.info', NULL, '$2y$12$7HDRVRBYNWpVN0RMQgBh3.pF4Vl8wrXVJmgnP82Xjns7p.J0TiGoy', NULL, '2025-12-24 14:07:21', '2025-12-24 14:07:21');

-- Table: categories
INSERT INTO `categories` (`id`, `name`, `slug`, `color`, `created_at`, `updated_at`) VALUES ('1', 'Android', 'android', '#ffffff', '2025-12-24 17:03:20', '2025-12-24 17:03:20');

-- Table: games
INSERT INTO `games` (`id`, `title`, `slug`, `cover_image`, `thumbnail_image`, `description`, `author`, `developer`, `version`, `censorship`, `language`, `platform`, `release_date`, `download_link`, `created_at`, `updated_at`, `is_hot`, `buy_link`, `system_requirements`, `gallery_images`, `download_content`, `password`, `installation_guide`, `meta_title`, `meta_description`, `meta_keywords`) VALUES ('2', 'A Prince’s Tale [v0.4.0]', 'a-princes-tale-v040', 'games/covers/01KD8PXZSZ7QBH5XZ2Y3Y68MVY.png', 'games/thumbnails/01KD8PXZT2RZNNWPE8BGAMEGZJ.webp', '<p>A Prince\'s Tale is a free-to-play text based erotic adventure game. You play as the prince of a fantasy kingdom. Recently, your father, the king, has left the kingdom in your hands. You are expected represent him in the royal council, where each councilor is looking to mentor you in some way, perhaps to benefit their own agenda. Especially the new royal sorceress seems keen on guiding you using her strange transformative magical powers...​</p>', 'Minaarigatou', 'Minaarigatou', '1.4', 'Uncensored', 'English', 'Android', '2025-12-25 00:00:00', 'https://tiny-pastry.itch.io/a-princes-tale', '2025-12-24 17:36:27', '2025-12-24 17:36:27', '0', 'https://tiny-pastry.itch.io/a-princes-tale', '<ul><li><strong>OS:</strong> Windows 7+</li><li><strong>Processor:</strong> Pentium 4</li><li><strong>Memory:</strong> 1 GB</li><li><strong>Graphics:</strong> VRAM 1 GB</li><li><strong>DirectX:</strong> 9.0c</li><li><strong>Storage:</strong> 112.7MB</li></ul><p><br></p>', '[\"games\\/gallery\\/01KD8PXZT3T4WFE2V63FAYTNZ8.webp\",\"games\\/gallery\\/01KD8PXZT5NE0WSJ2KAA7NP78D.webp\",\"games\\/gallery\\/01KD8PXZT6TCN4QXMVTGNA6VYA.webp\"]', '<p>Android (v0.2.9a)</p>', 'kimochi.info', '<p>Please spend time to read <a href=\"https://kimochi.info/faqs\">FAQs</a> before downloading!</p>', NULL, NULL, NULL);

-- Table: category_game
INSERT INTO `category_game` (`id`, `game_id`, `category_id`, `created_at`, `updated_at`) VALUES ('2', '2', '1', NULL, NULL);

-- Table: site_settings
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

-- Table: navigation_items
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('3', 'Games', 'home', '0', '0', '2025-12-24 17:10:25', '2025-12-24 17:10:32');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('4', 'FAQs', 'faqs', '1', '0', '2025-12-24 17:11:41', '2025-12-24 17:11:41');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('5', 'Report Dead Link', 'https://discord.com/', '3', '1', '2025-12-24 17:12:39', '2025-12-24 17:12:39');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('6', 'Android', 'category/android', '4', '0', '2025-12-24 17:13:05', '2025-12-24 17:13:05');
INSERT INTO `navigation_items` (`id`, `label`, `url`, `sort_order`, `is_external`, `created_at`, `updated_at`) VALUES ('7', 'Advance Search', '/advance-search', '5', '0', '2025-12-24 17:13:23', '2025-12-24 17:42:51');

SET FOREIGN_KEY_CHECKS=1;
