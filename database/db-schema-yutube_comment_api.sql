-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 02, 2025 at 06:40 AM
-- Server version: 5.7.40
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yutube_comment_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_edited` tinyint(1) NOT NULL DEFAULT '0',
  `content` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint(20) DEFAULT NULL,
  `reply_to` bigint(20) DEFAULT NULL,
  `video_id` bigint(20) DEFAULT NULL,
  `parent_comment_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_video_id_foreign` (`video_id`),
  KEY `comments_parent_comment_id_foreign` (`parent_comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `created_at`, `updated_at`, `is_edited`, `content`, `user_id`, `reply_to`, `video_id`, `parent_comment_id`) VALUES
(1, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Iusto aut neque inventore quis odit.', 1, NULL, 1, NULL),
(2, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 1, 'Updated content', 2, NULL, 2, NULL),
(4, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Harum dolores maxime omnis id quia aut autem.', 4, NULL, 4, NULL),
(5, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Sed molestiae fuga est non sunt et.', 3, NULL, 4, NULL),
(6, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Cupiditate mollitia qui ullam fugit nemo.', 3, NULL, 4, NULL),
(7, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Quaerat in qui eaque dolor aut.', 5, NULL, 5, NULL),
(8, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Possimus iusto sapiente praesentium laborum eius.', 5, NULL, 5, NULL),
(9, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Ea distinctio quasi blanditiis dolor sunt et velit nulla.', 1, NULL, 5, NULL),
(10, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Culpa molestiae iusto unde repudiandae.', 5, NULL, 5, NULL),
(11, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Quibusdam porro consequatur accusamus vitae delectus.', 2, NULL, 5, NULL),
(12, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Explicabo aut ea voluptas aut omnis nesciunt et.', 2, NULL, 2, NULL),
(13, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Enim omnis quod aspernatur blanditiis sit numquam modi.', 2, NULL, 2, NULL),
(14, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Et possimus qui suscipit.', 1, NULL, 4, NULL),
(15, '2025-03-02 06:18:26', '2025-03-02 06:36:06', 1, 'EDIT You may have to fight a battle more than once to win it.', 4, NULL, 4, NULL),
(16, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Perferendis consequatur nulla consequatur incidunt.', 3, NULL, 4, NULL),
(17, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'This is a reply', 11, NULL, 4, 16),
(18, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Ut sit officia aut et animi.', 5, NULL, 2, NULL),
(19, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 0, 'Omnis qui molestiae ipsa dicta eligendi id.', 5, NULL, 9, 18),
(20, '2025-03-02 06:23:35', '2025-03-02 06:23:35', 0, 'The right words at the right time can change your mindset and become a catalyst for change in your life', 1, NULL, 2, NULL),
(21, '2025-03-02 06:24:33', '2025-03-02 06:24:33', 0, 'Don’t give up what you want most for what you want now.', 3, NULL, 2, 20),
(22, '2025-03-02 06:25:07', '2025-03-02 06:25:07', 0, 'It does not matter how slowly you go so long as you do not stop.', 1, 5, 2, 20);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '0001_01_01_000003_create_personal_access_tokens_table', 1),
(5, '2025_02_27_034447_create_videos_table', 1),
(6, '2025_02_27_040803_create_comments_table', 1),
(7, '2025_02_27_044213_create_reactions_table', 1),
(8, '2025_03_01_144725_add_column_reply_to_into_comment_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reactions`
--

DROP TABLE IF EXISTS `reactions`;
CREATE TABLE IF NOT EXISTS `reactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT '-1/1/2',
  `user_id` bigint(20) DEFAULT NULL,
  `comment_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reactions_user_id_foreign` (`user_id`),
  KEY `reactions_comment_id_foreign` (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reactions`
--

INSERT INTO `reactions` (`id`, `created_at`, `updated_at`, `type`, `user_id`, `comment_id`) VALUES
(1, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 1, 7, 12),
(3, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 2, 6, 15),
(4, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 2, 1, 15),
(5, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 2, 10, 15),
(6, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 2, 11, 16),
(7, '2025-03-02 06:24:33', '2025-03-02 06:24:33', 2, 3, 20),
(8, '2025-03-02 06:25:07', '2025-03-02 06:25:07', 2, 1, 20),
(11, '2025-03-02 06:36:32', '2025-03-02 06:36:32', 1, 5, 10),
(12, '2025-03-02 06:36:43', '2025-03-02 06:36:43', -1, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Iva Johnston DDS', 'bednar.jalon@example.org', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', 'GKEFQeWZY8', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(2, 'Carole McClure', 'logan69@example.org', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', 'H1pfjPrHxJ', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(3, 'Minerva Shields', 'greenfelder.felicita@example.org', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', '8fq0KMWl5I', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(4, 'Leopold Rogahn', 'everardo48@example.net', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', 'JJtg5P8hnG', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(5, 'Dudley Barrows', 'nicholaus.daugherty@example.org', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', '61Cc5BAQrd', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(6, 'Pietro Keebler', 'spencer.witting@example.net', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', '5qpE6heIdx', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(7, 'Ms. Thora Williamson DVM', 'kuvalis.philip@example.org', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', 'Ebhdm3372P', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(8, 'Dr. Elvis Walker IV', 'smith.jeromy@example.org', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', 'HxrURnRZXa', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(9, 'Mr. Bailey Bergstrom', 'sonny19@example.com', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', '3At9WJLQDo', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(10, 'Prof. Emery Robel III', 'theodora49@example.net', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', 'J4j1NgvFDe', '2025-03-02 06:18:26', '2025-03-02 06:18:26'),
(11, 'Dewitt Kihn', 'uromaguera@example.org', '2025-03-02 06:18:26', '$2y$04$mfK4FNq/03C.fbWIQpnOtuC8JCvflt8paoS8eH/rBv9XGEHHo71jG', 'zLFvnXSIsf', '2025-03-02 06:18:26', '2025-03-02 06:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `videos_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `created_at`, `updated_at`, `title`, `user_id`) VALUES
(1, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Animi id a nobis et molestiae fugit totam.', 1),
(2, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Quo est eum totam et tempora ut.', 2),
(3, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Dolorem eos reprehenderit eius a.', 1),
(4, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Molestias iusto quod qui mollitia ut eaque sed est.', 3),
(5, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Provident eius quas fugit dolores recusandae.', 2),
(6, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Enim reiciendis iste tempora.', 1),
(7, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Repudiandae dicta nemo sapiente velit quo architecto eum quidem.', 6),
(8, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Blanditiis repellendus sunt iste dolor.', 8),
(9, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Voluptatem vel quisquam commodi sit voluptatibus.', 3),
(10, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Facere vel explicabo molestiae nesciunt amet.', 2),
(11, '2025-03-02 06:18:26', '2025-03-02 06:18:26', 'Doloribus molestias et neque.', 10);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
