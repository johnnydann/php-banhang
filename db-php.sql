-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for webbanhang
CREATE DATABASE IF NOT EXISTS `webbanhang` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `webbanhang`;

-- Dumping structure for table webbanhang.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table webbanhang.categories: ~4 rows (approximately)
INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
	(1, 'Đồ ăn kèm', 'do-an-kem', '2025-03-18 05:20:51', '2025-03-30 18:31:24'),
	(2, 'pizza', 'pizza', '2025-03-18 05:21:26', '2025-03-18 05:21:26'),
	(3, 'Thức ăn nhanh', 'thuc-an-nhanh', '2025-03-18 05:21:48', '2025-03-18 05:21:48'),
	(4, 'Nước uống', 'nuoc-uong', '2025-03-18 05:22:16', '2025-03-18 05:22:16');

-- Dumping structure for table webbanhang.events
CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.events: ~3 rows (approximately)
INSERT INTO `events` (`id`, `title`, `image`, `description`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
	(6, 'Giảm giá đặc biệt 5', '/eventImages/67e3c467b0f06_1.jpg', 'Đi ba tính tiền một', '2025-03-21 08:00:00', '2025-03-25 22:00:00', '2025-03-26 02:09:59', '2025-03-26 02:09:59'),
	(7, 'Giảm giá đặc biệt 1', '/eventImages/67e3c48ccf976_4.jpg', 'Đi ba tính tiền một', '2025-03-21 08:00:00', '2025-03-25 22:00:00', '2025-03-26 02:10:36', '2025-03-26 02:10:36'),
	(8, 'Giảm giá đặc biệt 2', '/eventImages/67e3c498db21e_8.jpg', 'Đi ba tính tiền một', '2025-03-21 08:00:00', '2025-03-25 22:00:00', '2025-03-26 02:10:48', '2025-03-26 02:10:48');

-- Dumping structure for table webbanhang.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table webbanhang.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.migrations: ~15 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_03_14_103947_create_events_table', 1),
	(6, '2025_03_15_031356_create_permission_tables', 1),
	(7, '2025_03_15_042340_add_phone_number_to_users_table', 1),
	(8, '2025_03_18_114649_add_role_id_to_users_table', 2),
	(11, '2025_03_23_142327_add_role_and_banned_until_to_users_table', 3),
	(12, '2025_03_23_142827_add_role_to_users_table', 4),
	(13, '2025_03_23_143225_add_banned_until_to_users_table', 5),
	(14, '2025_03_23_143646_add_banned_until_to_users_table', 6),
	(15, '2025_03_23_180914_rebuild_role_column', 7),
	(17, '2025_03_23_181914_remove_role_id_from_users_table', 8),
	(21, '2025_03_25_182147_update_default_role_value_to_customer', 9),
	(22, '2025_03_31_033603_create_orders_table', 10),
	(23, '2025_03_31_033645_create_order_details_table', 10);

-- Dumping structure for table webbanhang.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table webbanhang.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.model_has_roles: ~33 rows (approximately)
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 4),
	(4, 'App\\Models\\User', 6),
	(3, 'App\\Models\\User', 7),
	(1, 'App\\Models\\User', 8),
	(1, 'App\\Models\\User', 9),
	(4, 'App\\Models\\User', 10),
	(3, 'App\\Models\\User', 11),
	(1, 'App\\Models\\User', 12),
	(4, 'App\\Models\\User', 13),
	(3, 'App\\Models\\User', 14),
	(1, 'App\\Models\\User', 15),
	(4, 'App\\Models\\User', 16),
	(3, 'App\\Models\\User', 17),
	(1, 'App\\Models\\User', 18),
	(1, 'App\\Models\\User', 19),
	(4, 'App\\Models\\User', 19),
	(1, 'App\\Models\\User', 20),
	(3, 'App\\Models\\User', 20),
	(1, 'App\\Models\\User', 21),
	(4, 'App\\Models\\User', 21),
	(3, 'App\\Models\\User', 22),
	(1, 'App\\Models\\User', 23),
	(4, 'App\\Models\\User', 24),
	(3, 'App\\Models\\User', 25),
	(1, 'App\\Models\\User', 26),
	(4, 'App\\Models\\User', 27),
	(3, 'App\\Models\\User', 28),
	(1, 'App\\Models\\User', 29),
	(1, 'App\\Models\\User', 30),
	(1, 'App\\Models\\User', 31),
	(4, 'App\\Models\\User', 32),
	(3, 'App\\Models\\User', 33),
	(1, 'App\\Models\\User', 34),
	(1, 'App\\Models\\User', 35),
	(1, 'App\\Models\\User', 36),
	(1, 'App\\Models\\User', 37),
	(1, 'App\\Models\\User', 38),
	(1, 'App\\Models\\User', 39),
	(1, 'App\\Models\\User', 40);

-- Dumping structure for table webbanhang.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_date` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','processing','paid','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.orders: ~6 rows (approximately)
INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_price`, `shipping_address`, `notes`, `status`, `payment_method`, `transaction_id`, `created_at`, `updated_at`) VALUES
	(1, 32, '2025-03-31 16:05:13', 40.00, '123 Coffee Street, City', 'Please deliver in the morning', 'pending', NULL, NULL, '2025-03-31 09:05:13', '2025-03-31 09:05:13'),
	(2, 35, '2025-04-01 09:37:02', 20.00, 'Quận 1, Tp HCM', 'Đơn hàng từ ứng dụng di động', 'pending', NULL, NULL, '2025-04-01 02:37:02', '2025-04-01 02:37:02'),
	(3, 35, '2025-04-01 09:38:21', 40.00, 'Quận 1, Tp HCM', 'Đơn hàng từ ứng dụng di động', 'pending', NULL, NULL, '2025-04-01 02:38:21', '2025-04-01 02:38:21'),
	(4, 32, '2025-04-01 09:41:07', 440.00, '123 Coffee Street, City', 'Please deliver in the morning', 'pending', NULL, NULL, '2025-04-01 02:41:07', '2025-04-01 02:41:07'),
	(5, 32, '2025-04-01 09:58:03', 40.00, '123 Coffee Street, City', 'Please deliver in the morning', 'pending', 'cash', NULL, '2025-04-01 02:58:03', '2025-04-01 02:58:03'),
	(6, 35, '2025-04-01 10:01:55', 300.00, 'Quận 1, Tp HCM', 'Đơn hàng từ ứng dụng di động', 'pending', 'cash', NULL, '2025-04-01 03:01:55', '2025-04-01 03:01:55');

-- Dumping structure for table webbanhang.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_instructions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_product_id_foreign` (`product_id`),
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.order_details: ~6 rows (approximately)
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `size`, `special_instructions`, `created_at`, `updated_at`) VALUES
	(1, 1, 3, 4, 10.00, NULL, NULL, '2025-03-31 09:05:13', '2025-03-31 09:05:13'),
	(2, 2, 1, 2, 10.00, NULL, NULL, '2025-04-01 02:37:02', '2025-04-01 02:37:02'),
	(3, 3, 1, 4, 10.00, NULL, NULL, '2025-04-01 02:38:21', '2025-04-01 02:38:21'),
	(4, 4, 3, 4, 10.00, NULL, NULL, '2025-04-01 02:41:07', '2025-04-01 02:41:07'),
	(5, 4, 2, 4, 100.00, NULL, NULL, '2025-04-01 02:41:07', '2025-04-01 02:41:07'),
	(6, 5, 3, 4, 10.00, NULL, NULL, '2025-04-01 02:58:03', '2025-04-01 02:58:03'),
	(7, 6, 2, 3, 100.00, NULL, NULL, '2025-04-01 03:01:55', '2025-04-01 03:01:55');

-- Dumping structure for table webbanhang.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table webbanhang.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.permissions: ~20 rows (approximately)
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'view products', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(2, 'create products', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(3, 'edit products', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(4, 'delete products', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(5, 'view categories', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(6, 'create categories', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(7, 'edit categories', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(8, 'delete categories', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(9, 'view events', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(10, 'create events', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(11, 'edit events', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(12, 'delete events', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(13, 'view users', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(14, 'create users', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(15, 'edit users', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(16, 'delete users', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(17, 'view orders', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(18, 'create orders', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(19, 'edit orders', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(20, 'delete orders', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56');

-- Dumping structure for table webbanhang.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.personal_access_tokens: ~19 rows (approximately)
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(1, 'App\\Models\\User', 1, 'auth_token', '505fb1a6fc848afbda69ccb74de0e7f0065dd379c369f1cb450b6319d5035b81', '["*"]', '2025-03-18 04:48:40', NULL, '2025-03-18 04:48:04', '2025-03-18 04:48:40'),
	(2, 'App\\Models\\User', 4, 'auth_token', '775b1a87ae1db5484faba50904f5b7232b1d162110c0c1d0ee8f107c94d349cd', '["*"]', NULL, NULL, '2025-03-18 04:53:11', '2025-03-18 04:53:11'),
	(3, 'App\\Models\\User', 1, 'auth_token', '496ef6c64396f03097748e60a3334f5cc3daecc8072eae3c07a9266c9e1df0d8', '["*"]', '2025-03-18 04:54:59', NULL, '2025-03-18 04:53:53', '2025-03-18 04:54:59'),
	(4, 'App\\Models\\User', 1, 'auth_token', '4ba7e2adc5b33380bc43e2ecd5979a1b32da3d41e0fb0d95dc106f5af5fb47f7', '["*"]', NULL, NULL, '2025-03-18 05:06:23', '2025-03-18 05:06:23'),
	(5, 'App\\Models\\User', 1, 'auth_token', 'efd21462a17b682ecea47488d21aa49d4d008e021a75a410e5ce55bb354e88fc', '["*"]', '2025-03-18 05:10:58', NULL, '2025-03-18 05:10:45', '2025-03-18 05:10:58'),
	(6, 'App\\Models\\User', 1, 'auth_token', '86c538c66968c8de3e906e3101bf3942b40921937baaebe104422cb70ec61171', '["*"]', '2025-03-18 05:12:57', NULL, '2025-03-18 05:12:44', '2025-03-18 05:12:57'),
	(7, 'App\\Models\\User', 4, 'auth_token', '8714e2b03a6d0c08556bb129e0777aaef8c03265e4bd8d667d642c4426b61422', '["*"]', NULL, NULL, '2025-03-18 05:16:21', '2025-03-18 05:16:21'),
	(8, 'App\\Models\\User', 6, 'auth_token', '583086b8c53626facc7c01b79ad0efc973bb093512a8098007537dcb5f1aef9b', '["*"]', '2025-03-23 05:50:19', NULL, '2025-03-18 05:20:31', '2025-03-23 05:50:19'),
	(9, 'App\\Models\\User', 4, 'auth_token', 'e7d450ea228457624e9cb385048e79e19ab05e93e223d5368c87343c63f7e451', '["*"]', '2025-03-18 05:24:06', NULL, '2025-03-18 05:23:16', '2025-03-18 05:24:06'),
	(10, 'App\\Models\\User', 4, 'auth_token', '7aabcbc7606e182429a2c1082fc4cdaf604ac76b262109d17164948739f9c1e2', '["*"]', NULL, NULL, '2025-03-18 06:55:27', '2025-03-18 06:55:27'),
	(11, 'App\\Models\\User', 4, 'auth_token', '4162cf8663cdd835fe483686a00581f5dff7b5a1bd144c1a5af306b37ceb890b', '["*"]', '2025-03-23 06:06:04', NULL, '2025-03-23 06:05:43', '2025-03-23 06:06:04'),
	(12, 'App\\Models\\User', 6, 'auth_token', '94d6cafb24adc276284c68ace60aa3bbdd0f4f8c86f9ed5dc4a0360594486895', '["*"]', '2025-03-23 06:40:02', NULL, '2025-03-23 06:06:42', '2025-03-23 06:40:02'),
	(13, 'App\\Models\\User', 6, 'auth_token', '7d9d1ddf4fb1f54515a4595d3ce09ede44e7c8cbfe093b7cb2e5613fc6c4f6cc', '["*"]', '2025-03-23 06:53:44', NULL, '2025-03-23 06:10:55', '2025-03-23 06:53:44'),
	(14, 'App\\Models\\User', 6, 'auth_token', '1eaa450804d99b6483852500e8d78d123d6cceca0b09c097a957f4973946ac1a', '["*"]', NULL, NULL, '2025-03-23 10:23:39', '2025-03-23 10:23:39'),
	(15, 'App\\Models\\User', 6, 'auth_token', '0da4e3ebc3e051cf568fece5f28f746f7c6528fed803fcfe84999f6b62012d32', '["*"]', NULL, NULL, '2025-03-23 10:23:51', '2025-03-23 10:23:51'),
	(16, 'App\\Models\\User', 6, 'auth_token', '06d10e6a93da82f5d3368eb7789c699f5a677456d1b18a2b4a9ec49aa61a386b', '["*"]', NULL, NULL, '2025-03-23 10:30:46', '2025-03-23 10:30:46'),
	(17, 'App\\Models\\User', 9, 'auth_token', 'ad85200b6a390b7ddccbc9a6ab79f93d64d5b4c0054f628cae9c9ecee5943bcc', '["*"]', NULL, NULL, '2025-03-23 10:46:53', '2025-03-23 10:46:53'),
	(18, 'App\\Models\\User', 16, 'auth_token', 'fe83afa37810148fd035c1c63c34a13961b0bdf85cb0227b9313245173855ae2', '["*"]', '2025-03-23 12:08:58', NULL, '2025-03-23 11:53:09', '2025-03-23 12:08:58'),
	(19, 'App\\Models\\User', 16, 'auth_token', '47e09243fc7c5ecac713927f69171f2053a75cab200886a0a7d728581d5ab95b', '["*"]', '2025-03-23 12:17:10', NULL, '2025-03-23 12:09:07', '2025-03-23 12:17:10'),
	(20, 'App\\Models\\User', 32, 'auth_token', 'edb03163bbea1c1d6fca4c57dfd7ec72eb754e026154adec5f537e860bf0b0f5', '["*"]', NULL, NULL, '2025-03-25 11:26:31', '2025-03-25 11:26:31'),
	(21, 'App\\Models\\User', 35, 'auth_token', '7a8273bbfb3d867f64f556e74752cb255044a0c7253e791d67ea5919fb31c5be', '["*"]', NULL, NULL, '2025-03-25 20:50:39', '2025-03-25 20:50:39'),
	(22, 'App\\Models\\User', 35, 'auth_token', 'a8ef6d414a13d50853a8690810cc69908418be7cbc260e5b81e49aca876584f0', '["*"]', NULL, NULL, '2025-03-25 20:53:18', '2025-03-25 20:53:18'),
	(23, 'App\\Models\\User', 35, 'auth_token', 'eb375153d2233990e9487e3ef3e209e12001ea8b057ab959fc72680061200e1d', '["*"]', NULL, NULL, '2025-03-25 21:04:05', '2025-03-25 21:04:05'),
	(24, 'App\\Models\\User', 32, 'auth_token', 'f36c0e51d41bb86a948001691ab12c4128318f8ee489aebe12f917b79b5daf93', '["*"]', '2025-03-26 02:10:48', NULL, '2025-03-25 23:48:32', '2025-03-26 02:10:48'),
	(25, 'App\\Models\\User', 35, 'auth_token', '1b484d4d46caf95ccb9436e2fb6ee48c4f84702ae35056bb192576d836036d0f', '["*"]', NULL, NULL, '2025-03-26 22:55:17', '2025-03-26 22:55:17'),
	(26, 'App\\Models\\User', 35, 'auth_token', 'e060e243c3e98acd3bb23db03830b3ec5fa605a8806d5d43c1e4bb55967e8548', '["*"]', NULL, NULL, '2025-03-28 01:02:44', '2025-03-28 01:02:44'),
	(27, 'App\\Models\\User', 35, 'auth_token', '553114c7251ef0c4dcdcb1d61d009a034c4f266ade615c42c474142a098ad328', '["*"]', NULL, NULL, '2025-03-29 07:44:56', '2025-03-29 07:44:56'),
	(28, 'App\\Models\\User', 35, 'auth_token', 'e67b479275fe820302d7d0dc74b13a1c75867d95c737ed4187ce2b85d051d7da', '["*"]', NULL, NULL, '2025-03-30 18:18:36', '2025-03-30 18:18:36'),
	(29, 'App\\Models\\User', 32, 'auth_token', '4c5f1d7dfe0f1e522e13e523e6942383766ec7c69a646bf53e1aaf5fc65dc35c', '["*"]', '2025-03-30 18:31:23', NULL, '2025-03-30 18:23:07', '2025-03-30 18:31:23'),
	(30, 'App\\Models\\User', 32, 'auth_token', 'bc72124c5508548fa82ce51d8ad3302d9861bffc8b7d949e3593b7ee608f3ae8', '["*"]', NULL, NULL, '2025-03-31 08:23:02', '2025-03-31 08:23:02'),
	(31, 'App\\Models\\User', 32, 'auth_token', '514ada6896953b861dfbb03fbef4de8f5c3852e1871e8596ec301602bb9f2351', '["*"]', '2025-03-31 09:05:13', NULL, '2025-03-31 08:35:29', '2025-03-31 09:05:13'),
	(32, 'App\\Models\\User', 35, 'auth_token', 'ddef513f787559e5cf7aa962508948b08ca2565dbfd16ed5b505d9d27416f1ee', '["*"]', '2025-04-01 03:01:55', NULL, '2025-04-01 01:24:56', '2025-04-01 03:01:55'),
	(33, 'App\\Models\\User', 35, 'auth_token', 'ff84b372d5d63493c4a67365452e3d02b5258897cc60851a171087be50edcef4', '["*"]', NULL, NULL, '2025-04-01 01:24:57', '2025-04-01 01:24:57'),
	(34, 'App\\Models\\User', 32, 'auth_token', '462cc40d3d18bfa9070f71a9437d072ba2cf3614c8700be3922b617695a2a2a5', '["*"]', '2025-04-01 02:58:03', NULL, '2025-04-01 02:21:09', '2025-04-01 02:58:03');

-- Dumping structure for table webbanhang.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `quantity` int DEFAULT '0',
  `description` text,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `sizes` text,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table webbanhang.products: ~9 rows (approximately)
INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `description`, `image_url`, `category_id`, `sizes`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'redbull', 10.00, 5, 'giải khát', '/productImages/67d9658ceb3cd_redbull.jpg', 4, '[null,null,null]', 1, '2025-03-18 05:22:36', '2025-03-18 05:22:36'),
	(2, 'pizza gà nướng', 100.00, 10, 'đậm vị gà', '/productImages/67e0038e1e138_pizza-hawaii.jpg', 2, '["S","M","L"]', 1, '2025-03-23 05:50:22', '2025-03-23 05:50:22'),
	(3, 'pizza gà hấp', 10.00, 6, 'vị lạ đậm say', '/productImages/67e0086b399c6_pizza.jpg', 2, '[null,null,null]', 1, '2025-03-23 06:06:57', '2025-03-23 13:51:42'),
	(4, 'pizza bò', 100.00, 10, 'đậm vị bò', '/productImages/67e00bc505f49_pizza-bo-bam.jpg', 2, '["S","M","L"]', 1, '2025-03-23 06:25:25', '2025-03-23 06:25:25'),
	(5, 'coca', 100.00, 10, 'còn gì bằng khi uống coca cùng với pizza', '/productImages/67e00bf70809e_cocacola.jpg', 4, '[null,null,null]', 1, '2025-03-23 06:26:15', '2025-03-23 06:26:15'),
	(6, '7 up', 10.00, 10, 'còn gì bằng khi uống 7 upcùng với pizza', '/productImages/67e00c2a48cad_7up.jpg', 4, '[null,null,null]', 1, '2025-03-23 06:27:06', '2025-03-23 06:27:06'),
	(7, 'pepsi', 10.00, 10, 'pepsi tưng bừng hứng khởi', '/productImages/67e00c4d67075_7up.jpg', 4, '[null,null,null]', 1, '2025-03-23 06:27:41', '2025-03-23 06:27:41'),
	(8, 'pizza chay', 100.00, 10, 'phù hợp với những người thích ăn chay', '/productImages/67e00c8c7fc4e_pizza-chay.jpg', 2, '["S","M","L"]', 1, '2025-03-23 06:28:44', '2025-03-23 06:28:44'),
	(9, 'test', 100.00, 10, 'phù hợp với những người thích ăn chay', '/productImages/67e00f3222f09_pizza-chay.jpg', 2, '["S","M","L"]', 1, '2025-03-23 06:40:02', '2025-03-23 06:53:44');

-- Dumping structure for table webbanhang.product_images
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table webbanhang.product_images: ~0 rows (approximately)

-- Dumping structure for table webbanhang.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.roles: ~4 rows (approximately)
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Customer', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(2, 'Company', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(3, 'Employee', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56'),
	(4, 'Admin', 'web', '2025-03-18 04:43:56', '2025-03-18 04:43:56');

-- Dumping structure for table webbanhang.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.role_has_permissions: ~39 rows (approximately)
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(5, 1),
	(9, 1),
	(17, 1),
	(18, 1),
	(1, 2),
	(5, 2),
	(9, 2),
	(17, 2),
	(18, 2),
	(1, 3),
	(2, 3),
	(3, 3),
	(5, 3),
	(9, 3),
	(10, 3),
	(11, 3),
	(17, 3),
	(19, 3),
	(1, 4),
	(2, 4),
	(3, 4),
	(4, 4),
	(5, 4),
	(6, 4),
	(7, 4),
	(8, 4),
	(9, 4),
	(10, 4),
	(11, 4),
	(12, 4),
	(13, 4),
	(14, 4),
	(15, 4),
	(16, 4),
	(17, 4),
	(18, 4),
	(19, 4),
	(20, 4);

-- Dumping structure for table webbanhang.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Customer',
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banned_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webbanhang.users: ~9 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone_number`, `email_verified_at`, `password`, `remember_token`, `banned_until`, `created_at`, `updated_at`, `role_id`) VALUES
	(32, 'Admin', 'admin@example.com', 'Admin', '0123456789', NULL, '$2y$12$mA5AV0NghkvDlN2iY6K/IO9nqGHda8pBcSs1vM.jFtz9sQYmrsgv.', NULL, NULL, '2025-03-25 11:26:01', '2025-03-25 11:26:01', NULL),
	(33, 'Employee', 'employee@example.com', 'Employee', '0123456788', NULL, '$2y$12$bPy5UBbsEVHP2uvqieHLzuHBkaCF3kSpMxXZooHtPQmHFYjDTsMsW', NULL, NULL, '2025-03-25 11:26:01', '2025-03-25 11:26:01', NULL),
	(34, 'Customer', 'customer@example.com', 'Customer', '0123456787', NULL, '$2y$12$Th6lJcw2D0F1DCOixRwugO39GTKyKFyO/JXtT2gZs8FvPW20Wembm', NULL, NULL, '2025-03-25 11:26:02', '2025-03-25 11:26:02', NULL),
	(35, 'test', 'test@example.com', 'Customer', '6125456854', NULL, '$2y$12$3vb6lmn35fKNBdAi10YMbuM40RUo759goj1UY8WKI1554htDZcCoO', NULL, NULL, '2025-03-25 11:26:10', '2025-03-25 11:26:10', NULL),
	(36, 'test3', 'test3@gmail.com', 'Customer', '741852963', NULL, '$2y$12$Z9QC39jNTFOqXk/IXhHfmunYoR9LA1fuDZeqW.ECn4BbsQdcti0Tq', NULL, NULL, '2025-03-25 20:55:29', '2025-03-25 20:55:29', NULL),
	(37, 'test4', 'test4@example.com', 'Customer', '6125459654', NULL, '$2y$12$rJloSJ5KDP5uCcJ/JAJXc.vG7AmqSPpGkFRYkrBRROUD2JVnlSv6O', NULL, NULL, '2025-03-31 08:21:11', '2025-03-31 08:21:11', NULL),
	(38, 'test5', 'test5@example.com', 'Customer', '6125459654', NULL, '$2y$12$MMd2iBG0D0khbOid.waSa.1bcRxBIaKqO67LQaow6ku4.XPpJpjOe', NULL, NULL, '2025-03-31 08:22:25', '2025-03-31 08:22:25', NULL),
	(39, 'test6', 'test6@example.com', 'Customer', '6125459654', NULL, '$2y$12$A1NulLtXX3QVw2xLlJ6I0egmS/ruTis3t8rMpSm/yy7QK/0/o5B7G', NULL, NULL, '2025-03-31 08:29:03', '2025-03-31 08:29:03', NULL),
	(40, 'test7', 'test7@example.com', 'Customer', '6125459654', NULL, '$2y$12$EWEh2hGHaWWrFj8dFYXEl.DpzGfqbVPRAjtHfgVDz.3E4IvXX.jhG', NULL, NULL, '2025-03-31 08:29:41', '2025-03-31 08:29:41', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
