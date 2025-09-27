-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 07:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tenant-management`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `flat_id` bigint(20) UNSIGNED NOT NULL,
  `bill_category_id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('paid','unpaid') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `due_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `flat_id`, `bill_category_id`, `month`, `amount`, `status`, `notes`, `due_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-09', 1500.00, 'paid', 'September Electricity Bill', 0.00, '2025-09-27 11:16:01', '2025-09-27 11:38:28'),
(2, 1, 1, '2025-09', 1500.00, 'unpaid', 'September Electricity Bill', 0.00, '2025-09-27 11:26:44', '2025-09-27 11:26:44'),
(3, 1, 1, '2025-09', 1500.00, 'unpaid', 'September Electricity', 3000.00, '2025-09-27 11:33:01', '2025-09-27 11:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `bill_categories`
--

CREATE TABLE `bill_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `house_owner_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bill_categories`
--

INSERT INTO `bill_categories` (`id`, `house_owner_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 5, 'Electricity', '2025-09-27 11:04:13', '2025-09-27 11:04:13');

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `house_owner_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `name`, `address`, `house_owner_id`, `created_at`, `updated_at`) VALUES
(1, 'Sunrise Apartments', '123 Main Street', 5, '2025-09-27 11:25:03', '2025-09-27 11:25:03'),
(2, 'Sunrise Apartments', '123 Main Street', 5, '2025-09-27 11:26:24', '2025-09-27 11:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('a75f3f172bfb296f2e10cbfc6dfc1883', 'i:1;', 1758945880),
('a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1758945880;', 1758945880),
('d2bfa8e8b749d2772a21edee7b70a2b3', 'i:1;', 1758930449),
('d2bfa8e8b749d2772a21edee7b70a2b3:timer', 'i:1758930449;', 1758930449),
('df21bfa12c4e294c70f64916c0fbc9a5', 'i:1;', 1758951198),
('df21bfa12c4e294c70f64916c0fbc9a5:timer', 'i:1758951198;', 1758951198);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flats`
--

CREATE TABLE `flats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `flat_number` varchar(255) NOT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `owner_contact` varchar(255) DEFAULT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flats`
--

INSERT INTO `flats` (`id`, `flat_number`, `owner_name`, `owner_contact`, `building_id`, `created_at`, `updated_at`) VALUES
(1, 'A-101', 'John Doe', '0300-1234567', 2, '2025-09-27 10:58:48', '2025-09-27 10:58:48'),
(2, 'A-102', 'John Doe', '0300-1234567', 2, '2025-09-27 12:30:50', '2025-09-27 12:30:50');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_26_211832_create_personal_access_tokens_table', 1),
(5, '2025_09_26_212010_create_permission_tables', 1),
(6, '2025_09_26_215820_create_buildings_table', 1),
(7, '2025_09_26_215829_create_flats_table', 1),
(8, '2025_09_26_235549_create_tenants_table', 2),
(9, '2025_09_27_001205_create_flats_table', 3),
(10, '2025_09_27_001206_create_flats_table', 4),
(11, '2025_09_27_001207_create_flats_table', 5),
(12, '2025_09_27_040007_create_bill_categories_table', 6),
(13, '2025_09_27_040628_create_bills_table', 7),
(14, '2025_09_26_215821_create_buildings_table', 8),
(15, '2025_09_26_215822_create_buildings_table', 9),
(16, '2025_09_27_050235_add_flat_id_to_users_table', 10);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manage house owners', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(2, 'manage tenants', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(3, 'view tenants', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(4, 'remove tenants', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(5, 'assign tenants to buildings', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(6, 'create flats', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(7, 'manage flats', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(8, 'create bill categories', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(9, 'create bills', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(10, 'add dues', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 3, 'api-token', '19bd13bda3719b1f4daccb85e92430750aafb04f307e6f9cc9d83e23666ffd0f', '[\"*\"]', NULL, NULL, '2025-09-27 05:10:01', '2025-09-27 05:10:01'),
(2, 'App\\Models\\User', 4, 'api-token', 'ddfd6870824b3af742a0c2c76db46cba72cdf3f313509af67eb62a0fb3d14f79', '[\"*\"]', NULL, NULL, '2025-09-27 05:10:12', '2025-09-27 05:10:12'),
(3, 'App\\Models\\User', 3, 'api-token', 'adabdfc313b36f56c6b84e4c0b429ec9c7e26c4f09bb76cd133cd436d39fc239', '[\"*\"]', '2025-09-27 05:15:23', NULL, '2025-09-27 05:11:49', '2025-09-27 05:15:23'),
(4, 'App\\Models\\User', 5, 'auth_token', 'b8bfb649670aac949586769164aca2b5e471b4a0be7e7c1753cef874c391686f', '[\"*\"]', '2025-09-27 06:33:54', NULL, '2025-09-27 06:26:26', '2025-09-27 06:33:54'),
(5, 'App\\Models\\User', 3, 'auth_token', '47123297a0110b6fd8f3742c1fe4e6cb2fdf890096aa2d186802330b5e6bf70a', '[\"*\"]', '2025-09-27 06:34:04', NULL, '2025-09-27 06:33:45', '2025-09-27 06:34:04'),
(6, 'App\\Models\\User', 3, 'auth_token', 'ec754d702912f60e7cfcba29625bdbf9c011b65f38c7c03a94f3af5053dd8e93', '[\"*\"]', '2025-09-27 06:46:29', NULL, '2025-09-27 06:45:43', '2025-09-27 06:46:29'),
(7, 'App\\Models\\User', 5, 'auth_token', 'd62461c872d0e099e8b3977fcdd44cc75c516533a8aa71b8a86eeea5d8ad7219', '[\"*\"]', '2025-09-27 12:32:18', NULL, '2025-09-27 06:47:34', '2025-09-27 12:32:18'),
(8, 'App\\Models\\User', 5, 'auth_token', '71bc62339fd42915ad3278b9a2b3569f1129a7b9a1874a281b1ef45575cf5e32', '[\"*\"]', '2025-09-27 12:30:50', NULL, '2025-09-27 10:51:01', '2025-09-27 12:30:50'),
(9, 'App\\Models\\User', 5, 'auth_token', '4bd9eeb8a9df7b99fae3058e9bb10e1ad0ad903a6038faecf394e482b3a4965e', '[\"*\"]', '2025-09-27 12:01:06', NULL, '2025-09-27 11:03:41', '2025-09-27 12:01:06');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(2, 'house_owner', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38'),
(3, 'tenant', 'web', '2025-09-27 05:09:38', '2025-09-27 05:09:38');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `house_owner_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `name`, `email`, `contact`, `house_owner_id`, `created_at`, `updated_at`) VALUES
(2, 'SAM', 'sam@tenant.com', '03001234567', 2, '2025-09-27 07:08:44', '2025-09-27 07:08:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `flat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','house_owner','tenant') NOT NULL DEFAULT 'tenant',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `flat_id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, 'shayan', 'shayan@example.com', 'tenant', '2025-09-27 05:09:19', '$2y$12$5.wKvcuUowdo88xZQ46xoOwZRo0q9gcJTOBJ07NVQJgt8QMtE3UEi', '26Zuye7wi1', '2025-09-27 05:09:20', '2025-09-27 05:09:20'),
(2, NULL, 'Ali Raza', 'owner2@example.com', 'house_owner', NULL, '$2y$12$j11JlTu3nOjdgZ1PGP.p3uVRrOAj4DW0FIUqZNJ3PFAyk8QOLGXdy', NULL, '2025-09-27 05:09:23', '2025-09-27 06:53:54'),
(4, NULL, 'Owner One', 'sam@example.com', 'house_owner', NULL, '$2y$12$hynBhLZNUbQasZAqZdgF9uCUJmv5ZxcWTOu88m0/eck/XmOslk.2e', NULL, '2025-09-27 05:10:12', '2025-09-27 05:10:12'),
(5, NULL, 'Super Admin', 'shan@gmail.com', 'admin', NULL, '$2y$12$a8aBsxQaFcovEgHzjygGGOf/TAKtSdeUDmQ6ItFByBeB1LX.1hSkm', NULL, '2025-09-27 06:20:44', '2025-09-27 06:20:44'),
(6, NULL, 'Ali Khan', 'ali@owner.com', 'house_owner', NULL, '$2y$12$S5gOyNQNpxd8oXW6Sftx1.gMH7OjDLc8p9NsFfeGTwoWRbwaxF/fS', NULL, '2025-09-27 06:47:48', '2025-09-27 06:47:48'),
(7, NULL, 'Ali Khan', 'ali@example.com', 'tenant', NULL, '$2y$12$3XhusfmXV8gp5PQezDaLve1OICCT2mcnboYbN74Ml3JxNnqZHn.gq', NULL, '2025-09-27 12:09:34', '2025-09-27 12:09:34'),
(8, NULL, 'asim Khan', 'asim@example.com', 'tenant', NULL, '$2y$12$Wj4Zh/A7xQYe4sF0bwt1b.HPRhS12wTPoodGEW7Y81Zujy9sYbnEO', NULL, '2025-09-27 12:19:46', '2025-09-27 12:19:46'),
(9, 1, 'shaen Khan', 'shaen@example.com', 'tenant', NULL, '$2y$12$l3QIf.M1ZJWeWIqGFA9gTOZ7Nnvj/gTOvTSUjSQRjBXAiQvYyvDv6', NULL, '2025-09-27 12:25:27', '2025-09-27 12:25:27'),
(10, 2, 'sara', 'sara@example.com', 'tenant', NULL, '$2y$12$H3h3eZSRiDd1qNRus4UIleKGH/ZY8Bcg4scjjUGaGt54Nm22.wA2u', NULL, '2025-09-27 12:31:42', '2025-09-27 12:31:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bills_flat_id_foreign` (`flat_id`),
  ADD KEY `bills_bill_category_id_foreign` (`bill_category_id`);

--
-- Indexes for table `bill_categories`
--
ALTER TABLE `bill_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_categories_house_owner_id_foreign` (`house_owner_id`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buildings_house_owner_id_foreign` (`house_owner_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `flats`
--
ALTER TABLE `flats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flats_building_id_foreign` (`building_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenants_email_unique` (`email`),
  ADD KEY `tenants_house_owner_id_foreign` (`house_owner_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_flat_id_foreign` (`flat_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bill_categories`
--
ALTER TABLE `bill_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flats`
--
ALTER TABLE `flats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_bill_category_id_foreign` FOREIGN KEY (`bill_category_id`) REFERENCES `bill_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bills_flat_id_foreign` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_categories`
--
ALTER TABLE `bill_categories`
  ADD CONSTRAINT `bill_categories_house_owner_id_foreign` FOREIGN KEY (`house_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `buildings`
--
ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_house_owner_id_foreign` FOREIGN KEY (`house_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flats`
--
ALTER TABLE `flats`
  ADD CONSTRAINT `flats_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tenants`
--
ALTER TABLE `tenants`
  ADD CONSTRAINT `tenants_house_owner_id_foreign` FOREIGN KEY (`house_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_flat_id_foreign` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
