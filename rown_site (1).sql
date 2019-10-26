-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 01, 2019 at 08:09 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rown_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(191) NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `type`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'muhammadharoon526@gmail.com', 1, '$2y$10$1RCwWxAZBNjUOfSWeW2ruuVSdbEkXxQ8m2NJwNOIztTdOSiW0A1HS', 'uRgDfS8KBOScuwIeZcxykDGko4IrPXObq9anPgWDiSQ6lULylDdyXvkfuNl4', NULL, NULL),
(2, 'adam@weblinerz.co.uk', 2, '$2y$10$1RCwWxAZBNjUOfSWeW2ruuVSdbEkXxQ8m2NJwNOIztTdOSiW0A1HS', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `best_sale_of_week`
--

DROP TABLE IF EXISTS `best_sale_of_week`;
CREATE TABLE IF NOT EXISTS `best_sale_of_week` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `best_selling`
--

DROP TABLE IF EXISTS `best_selling`;
CREATE TABLE IF NOT EXISTS `best_selling` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_description`, `category_status`, `created_at`, `updated_at`) VALUES
(1, 'Mobiles', 'all types of mobiles will be here\r\n', 1, NULL, NULL),
(2, 'Books', 'all types of books will be here', 1, NULL, NULL),
(3, 'Furniture', 'all types of furniture will be lies in this category', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `type`, `address`, `email`, `contact_no`, `image`, `created_at`, `updated_at`) VALUES
(4, 'Arpasoft', 'software company', 'wallayat complex bahria town rawal pindi', 'arpasoft@gmail.com', '03365715780', 'arpasoft.jpg', '2019-01-16 07:08:25', '2019-01-16 07:08:25'),
(5, 'ROWN', 'Furniture', 'Abs', 'abc@gmail.com', '03014558745', NULL, '2019-01-21 09:52:31', '2019-01-21 09:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `deliveryinfo`
--

DROP TABLE IF EXISTS `deliveryinfo`;
CREATE TABLE IF NOT EXISTS `deliveryinfo` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `city` int(11) NOT NULL,
  `zone` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliveryinfo`
--

INSERT INTO `deliveryinfo` (`id`, `user_id`, `order_id`, `city`, `zone`, `address`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 0, 'korang town', 'street # 44 korang town islamabad', '2018-09-11 09:43:52', '2018-09-11 09:43:52'),
(2, 6, 1, 1, 'korang town', 'street # 44', '2018-09-13 14:12:56', '2018-09-13 14:12:56'),
(3, 9, 1, 0, 'korang town', 'Near Air port', '2018-09-19 02:47:53', '2018-09-19 02:47:53'),
(4, 6, 1, 0, 'korang town', 'Near Air port', '2018-10-27 17:15:51', '2018-10-27 17:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `entr_products`
--

DROP TABLE IF EXISTS `entr_products`;
CREATE TABLE IF NOT EXISTS `entr_products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `sal_new` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `replace_sale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `featured_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entr_products`
--

INSERT INTO `entr_products` (`id`, `user_id`, `category`, `product_name`, `type`, `quantity`, `price`, `sal_new`, `replace_sale`, `description`, `featured_image`, `image1`, `image2`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Q Mobile x67', '1', 2, '6700.00', '0', '0', 'nill', 'arpasoft.jpg', 'esoft.JPG', NULL, 1, NULL, '2019-01-16 09:18:30', '2019-01-16 10:27:25');

-- --------------------------------------------------------

--
-- Table structure for table `entr_vendrs`
--

DROP TABLE IF EXISTS `entr_vendrs`;
CREATE TABLE IF NOT EXISTS `entr_vendrs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `contact_no` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entr_vendrs`
--

INSERT INTO `entr_vendrs` (`id`, `name`, `email`, `address`, `contact_no`, `password`, `image`, `type`, `status`, `deleted_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Haroon', 'muhammadharoon526@gmail.com', 'korang town islamabad', '0523993226', '$2y$10$WfG2LnyxQ85fcfG/lXSfmeBKJpvCLm7MULMxEy.oT56nP0zXYBvLW', NULL, 1, 1, NULL, 'NmVC0dw1WmNE2PuXjk5HnaqM9rt8fhUVTt0sMLRzjwbEJ89P2amXAYDt8bzg', '2019-01-16 09:17:32', '2019-01-16 09:17:32'),
(2, 'Muzammal Hussain', 'muzammal.hussain180@gmail.com', 'akfhsd', '03014558745', '$2y$10$vMTM6f/8i648VnQNNzbbz.WYKKMi8bwpYZxDu3eUexbMfhRcT7ar2', NULL, 1, 1, NULL, NULL, '2019-01-16 13:50:15', '2019-01-16 13:50:15'),
(3, 'Vendor', 'muzammal.180@gmail.com', 'akfhsd', '03014558745', '$2y$10$Qkbo/fbwT.7AnYky8sgApupnPpvpzN1Yn5ztMMn3Mz9pnEVbIooG.', NULL, 2, 1, NULL, NULL, '2019-01-17 02:55:19', '2019-01-17 02:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `featured_products`
--

DROP TABLE IF EXISTS `featured_products`;
CREATE TABLE IF NOT EXISTS `featured_products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hot_deal`
--

DROP TABLE IF EXISTS `hot_deal`;
CREATE TABLE IF NOT EXISTS `hot_deal` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `from_date` datetime NOT NULL,
  `to_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(55, '2014_10_12_000000_create_users_table', 1),
(56, '2014_10_12_100000_create_password_resets_table', 1),
(57, '2018_08_29_215804_create_replaceitems_table', 1),
(58, '2018_08_30_173430_create_user_payemnts_table', 1),
(59, '2018_08_30_233046_create_whishlist_table', 1),
(60, '2018_08_31_212033_create_shoppingcart_table', 1),
(61, '2018_08_31_221124_create_categories_table', 1),
(62, '2018_09_03_053150_create_promocode_table', 1),
(63, '2018_09_04_181957_create_deliveryinfo_table', 1),
(64, '2018_09_04_205201_create_orders_table', 1),
(65, '2018_09_05_040313_create_ordered_products_table', 1),
(66, '2018_09_05_165534_create_best_sale_of_week_table', 1),
(67, '2018_09_05_175029_create_hot_deal_table', 1),
(68, '2018_09_05_191156_create_best_selling_table', 1),
(69, '2018_09_05_193212_create_featured_products_table', 1),
(70, '2018_09_05_195231_create_special_products_table', 1),
(71, '2018_09_05_202451_create_most_popular_products_table', 1),
(72, '2018_09_05_211840_create_user_messages_table', 1),
(73, '2018_09_06_185246_create_news_letters_table', 1),
(95, '2019_01_15_223543_create_vendor_application_table', 3),
(82, '2018_09_09_080416_create_company_table', 2),
(92, '2018_09_12_231546_create_admins_table', 3),
(93, '2019_01_06_214041_create_entr_products_table', 3),
(94, '2019_01_10_190029_create_entr_vendrs_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `most_popular_products`
--

DROP TABLE IF EXISTS `most_popular_products`;
CREATE TABLE IF NOT EXISTS `most_popular_products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_letters`
--

DROP TABLE IF EXISTS `news_letters`;
CREATE TABLE IF NOT EXISTS `news_letters` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordered_products`
--

DROP TABLE IF EXISTS `ordered_products`;
CREATE TABLE IF NOT EXISTS `ordered_products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `total_items` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ordered_products`
--

INSERT INTO `ordered_products` (`id`, `user_id`, `order_id`, `product_id`, `total_items`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 6, 2, 1000, 1, '2018-09-11 09:47:21', '2018-09-11 09:47:21'),
(2, 6, 2, 4, 1, 600, 1, '2018-09-13 14:14:53', '2018-09-13 14:14:53'),
(3, 9, 3, 3, 2, 32000, 1, '2018-09-19 02:49:40', '2018-09-19 02:49:40'),
(4, 9, 3, 4, 1, 600, 1, '2018-09-19 02:49:40', '2018-09-19 02:49:40'),
(5, 6, 4, 2, 1, 600, 1, '2018-10-27 17:16:34', '2018-10-27 17:16:34'),
(6, 6, 4, 2, 2, 1200, 1, '2018-10-27 17:16:34', '2018-10-27 17:16:34'),
(7, 6, 6, 2, 3, 1800, 1, '2019-01-16 07:37:00', '2019-01-16 07:37:00'),
(8, 6, 6, 3, 3, 48000, 1, '2019-01-16 07:37:00', '2019-01-16 07:37:00'),
(9, 6, 7, 2, 1, 600, 1, '2019-09-19 04:10:52', '2019-09-19 04:10:52'),
(10, 6, 7, 3, 1, 16000, 1, '2019-09-19 04:10:52', '2019-09-19 04:10:52'),
(11, 6, 8, 2, 1, 600, 1, '2019-09-19 07:49:27', '2019-09-19 07:49:27'),
(12, 6, 8, 3, 4, 64000, 1, '2019-09-19 07:49:27', '2019-09-19 07:49:27');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `invoice_no` bigint(20) NOT NULL,
  `due_amount` decimal(8,2) NOT NULL,
  `paid_amount` decimal(8,2) DEFAULT NULL,
  `delivery_info` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `invoice_no`, `due_amount`, `paid_amount`, `delivery_info`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 1, '960.00', '960.00', 1, 1, '2018-09-11 09:47:21', '2018-09-11 09:48:15'),
(2, 6, 2, '576.00', '576.00', 2, 1, '2018-09-13 14:14:53', '2018-09-13 14:16:27'),
(3, 9, 3, '32600.00', '3200.00', 3, 0, '2018-09-19 02:49:40', '2018-09-19 02:50:11'),
(4, 6, 4, '1728.00', '1728.00', 4, 1, '2018-10-27 17:16:34', '2018-10-27 17:19:39'),
(5, 6, 5, '1800.00', NULL, 4, 0, '2018-12-30 06:24:53', '2018-12-30 06:24:53'),
(6, 6, 6, '49800.00', NULL, 4, 0, '2019-01-16 07:37:00', '2019-01-16 07:37:00'),
(7, 6, 7, '16600.00', NULL, 4, 0, '2019-09-19 04:10:52', '2019-09-19 04:10:52'),
(8, 6, 8, '64600.00', '6646.00', 4, 0, '2019-09-19 07:49:27', '2019-09-19 07:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promocode`
--

DROP TABLE IF EXISTS `promocode`;
CREATE TABLE IF NOT EXISTS `promocode` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `to_users` int(11) DEFAULT NULL,
  `code_title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `discount_percentage` decimal(8,2) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promocode`
--

INSERT INTO `promocode` (`id`, `to_users`, `code_title`, `description`, `code`, `from`, `to`, `discount_percentage`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'first entry', 'djkddjk', '3345', '2018-09-12', '2018-09-14', '4.00', 1, NULL, NULL),
(2, NULL, 'Come Back', 'we returned ', '4526', '2019-09-19', '2019-10-19', '10.00', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `replaceitems`
--

DROP TABLE IF EXISTS `replaceitems`;
CREATE TABLE IF NOT EXISTS `replaceitems` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` decimal(8,2) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sal_new` int(11) NOT NULL,
  `replace_sell` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `product_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `replaceitems`
--

INSERT INTO `replaceitems` (`id`, `user_id`, `product_category`, `product_name`, `product_price`, `product_quantity`, `product_image`, `product_type`, `sal_new`, `replace_sell`, `status`, `product_description`, `created_at`, `updated_at`) VALUES
(1, 6, '1', 'Q Mobile x67', '7000.00', 2, '14.jpg', '0', 0, 0, 1, 'q mobile with 64 GB internal mobiles', '2018-09-11 09:29:58', '2018-09-12 02:49:47'),
(2, 6, '2', 'English book 1', '600.00', 4, '90-1.jpg', '1', 0, 0, 1, 'English Grammer book', '2018-09-11 09:31:04', '2018-09-13 13:56:15'),
(3, 6, '3', 'SOfa set', '16000.00', 1, 'DoubleCornerShelf.jpg', '0', 0, 1, 0, 'Sofa sets with tables and chairs', '2018-09-11 09:32:21', '2018-09-11 09:32:21'),
(4, 7, '2', 'Urdu', '600.00', 2, 'Brackets.jpg', '1', 0, 0, 1, 'books for FA FSC 12th class', '2018-09-11 09:37:00', '2018-09-12 06:29:17'),
(5, 7, '1', 'Q I5', '1200.00', 1, '1-34.jpg', '0', 1, 1, 1, 'storage: 8gb memory card\r\nbettry duration: 1 day\r\nhands free', '2018-09-11 09:38:47', '2018-09-12 06:26:28'),
(7, 10, '1', 'Books', '200.00', 2, 'FB_IMG_1431422287379.jpg', '0', 1, 1, 0, '10/10', '2019-01-17 03:03:11', '2019-01-17 03:03:11');

-- --------------------------------------------------------

--
-- Table structure for table `shoppingcart`
--

DROP TABLE IF EXISTS `shoppingcart`;
CREATE TABLE IF NOT EXISTS `shoppingcart` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `total_items` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `special_products`
--

DROP TABLE IF EXISTS `special_products`;
CREATE TABLE IF NOT EXISTS `special_products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `city`, `contact_no`, `address`, `image`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(9, 'MUHAMMAD ARSALAN', 'ar@gamil.com', 'Mardan', '03416785645', 'Shewa Adda,swabi', '20623562_1195986793881332_415094653_o (1).jpg', '$2y$10$fmatNWsH0X4V/TGowWfDPuz367HL9jssgSPpBSAoFDxv3sugKRdfK', NULL, '2018-09-19 02:44:32', '2018-09-19 02:45:02'),
(6, 'Muhammad Haroon', 'muhammadharoon526@gmail.com', 'islamabad', '+923365715780', 'korang town islamabad', 'Haroon.JPG', '$2y$10$1RCwWxAZBNjUOfSWeW2ruuVSdbEkXxQ8m2NJwNOIztTdOSiW0A1HS', 'JYkXKSx9ijZkRuZotQ03wmpGtq2HDwC5eRPiapVWxYAtXIQsJQmkdIFn60oF', '2018-09-11 09:24:19', '2018-09-11 09:24:37'),
(7, 'Bahar Ali', 'baharali456@gmail.com', 'mardan', '03416785645', 'Managai tarakai swabi', '20160629_025907.jpg', '$2y$10$zb4xiM62D0FsqAuUGOc29OchDokgFUb7HIHBTaPpUN1W4UZUMH2ia', '1R44RC3G8eIa5c7Xe29sVLqHKCTZbzZ1i8kX7U17SWZogOrHRcn083AdJRMt', '2018-09-11 09:33:48', '2018-09-11 09:34:22'),
(10, 'Muzammal Hussain', 'muzammal.hussain180@gmail.com', 'Rawalpindi', '03014558745', 'akfhsd', NULL, '$2y$10$j4LR354u/ERDILQWpCcrWOlj/bcPoVp9ZEvUyjR0D9SBlzYAHmMR2', 'WlL9r3XNbQm5GRFymSanJkd932Aun42ggsHYn3QaZbLkP1DANi79gK9yRJKp', '2019-01-16 13:47:44', '2019-01-16 13:47:44');

-- --------------------------------------------------------

--
-- Table structure for table `user_messages`
--

DROP TABLE IF EXISTS `user_messages`;
CREATE TABLE IF NOT EXISTS `user_messages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_email` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_messages`
--

INSERT INTO `user_messages` (`id`, `user_name`, `user_email`, `phone_no`, `title`, `user_message`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Muhammad Haneef', 'admin@esoft.us', '0336 5715780', 'First Article', 'hello i want help regardaing using this site', 1, '2018-09-12 06:59:42', '2018-09-12 07:29:34'),
(2, 'Muhammad Haroon', 'muhammadharoon526@gmail.com', '0336 5715780', 'just for testing', 'hi admin doesn\'t worry i only just checking that works or not', 1, '2018-09-12 07:03:58', '2018-09-12 07:41:19'),
(3, 'MUHAMMAD ARSALAN', 'muhammadhaneef12@gmial.com', '0336 5715780', 'Physics', 'hi admin doesn\'t worry i only just checking that works or not', 1, '2018-09-12 07:05:12', '2018-09-12 07:41:25'),
(4, 'Muhammad Haneef', 'muhammadharoon@esoft.us', '0336 5715780', 'Physics', 'yyyy', 1, '2018-09-12 07:05:34', '2018-09-13 05:05:50'),
(5, 'Hazrat Usman Khan', 'usman1122@gmail.com', '03449121053', 'Apply', 'i want to join Rown Team please guide me', 1, '2018-09-13 14:41:44', '2018-09-13 14:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `user_payemnts`
--

DROP TABLE IF EXISTS `user_payemnts`;
CREATE TABLE IF NOT EXISTS `user_payemnts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_payemnts`
--

INSERT INTO `user_payemnts` (`id`, `user_id`, `payment_name`, `payment_description`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 'Easy Paisa', 'Name : M.haroon\r\ncell# 16204-0353847-9', 1, '2018-09-11 09:46:20', '2018-09-11 09:46:20'),
(2, 9, 'Bank Account', 'Account Title : Muhammad Arsalan\r\nBank : Alpalah bank (swabi branch)\r\ncode:758995949-djheji848840-989', 1, '2018-09-19 02:49:07', '2018-09-19 02:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_application`
--

DROP TABLE IF EXISTS `vendor_application`;
CREATE TABLE IF NOT EXISTS `vendor_application` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whishlist`
--

DROP TABLE IF EXISTS `whishlist`;
CREATE TABLE IF NOT EXISTS `whishlist` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
