-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 02, 2024 at 11:24 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `viserlab_viserbank_3.0`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `role_id`, `name`, `email`, `username`, `email_verified_at`, `image`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 0, 'Super Admin', 'admin@site.com', 'admin', NULL, '66c1ec85073701723985029.png', '$2y$12$F4Yl2LcVbdidSAmcsxYVKOxewu9bkJRiBmXxC2NfDvbo4PiIU7JoS', 1, 'iOIY7rlIrpJdgKUCdFPvArfdOoJLTjbitmzl9UmjWvRorruthVDNFrQp1i8l', NULL, '2024-08-18 06:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `click_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_configurations`
--

CREATE TABLE `api_configurations` (
  `id` bigint UNSIGNED NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `credentials` text,
  `test_mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = test mode on;\r\n2 = test mode off',
  `token_type` varchar(40) DEFAULT NULL,
  `access_token` longtext,
  `token_expired_on` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `api_configurations`
--

INSERT INTO `api_configurations` (`id`, `provider`, `credentials`, `test_mode`, `token_type`, `access_token`, `token_expired_on`, `created_at`, `updated_at`) VALUES
(1, 'reloadly', '{\"client_id\":\"b7nMpB0ubxcz2fmf7NurtwgLatgxkPce\",\"client_secret\":\"laT5MDIUms-dLu7bpNMMG6Q1fQ44sS-vnxaR25OBHJQDvojnVLgqL3VfWS2T1V7\"}', 1, 'Bearer', 'eyJraWQiOiI1N2JjZjNhNy01YmYwLTQ1M2QtODQ0Mi03ODhlMTA4OWI3MDIiLCJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyMjczOCIsImlzcyI6Imh0dHBzOi8vcmVsb2FkbHktc2FuZGJveC5hdXRoMC5jb20vIiwiaHR0cHM6Ly9yZWxvYWRseS5jb20vc2FuZGJveCI6dHJ1ZSwiaHR0cHM6Ly9yZWxvYWRseS5jb20vcHJlcGFpZFVzZXJJZCI6IjIyNzM4IiwiZ3R5IjoiY2xpZW50LWNyZWRlbnRpYWxzIiwiYXVkIjoiaHR0cHM6Ly90b3B1cHMtaHMyNTYtc2FuZGJveC5yZWxvYWRseS5jb20iLCJuYmYiOjE3MjUxODc3NTAsImF6cCI6IjIyNzM4Iiwic2NvcGUiOiJzZW5kLXRvcHVwcyByZWFkLW9wZXJhdG9ycyByZWFkLXByb21vdGlvbnMgcmVhZC10b3B1cHMtaGlzdG9yeSByZWFkLXByZXBhaWQtYmFsYW5jZSByZWFkLXByZXBhaWQtY29tbWlzc2lvbnMiLCJleHAiOjE3MjUyNzQxNTAsImh0dHBzOi8vcmVsb2FkbHkuY29tL2p0aSI6IjYzYTA3N2RiLTEyZDQtNGI4NS1iNWUxLWNhZDA0OGUwMzFkNyIsImlhdCI6MTcyNTE4Nzc1MCwianRpIjoiODg4MmFlYzAtZDJmMS00ZTdhLWI4YzktOTU2YzJlOWUyZWQxIn0.ubBcGtQrDfeAUvUW0XMGVhSYUGVABrfcGAFkA8i6Zuw', '2024-09-03 04:22:03', NULL, '2024-09-02 04:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `assign_branch_staff`
--

CREATE TABLE `assign_branch_staff` (
  `id` bigint NOT NULL,
  `staff_id` int NOT NULL,
  `branch_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `balance_transfers`
--

CREATE TABLE `balance_transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `beneficiary_id` int UNSIGNED NOT NULL DEFAULT '0',
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `reject_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `wire_transfer_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=pending, 1=Completed, 2= Rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `beneficiary_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `beneficiary_id` int UNSIGNED DEFAULT NULL,
  `account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routing_number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `swift_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `map_location` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_staff`
--

CREATE TABLE `branch_staff` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 => Account Officer\r\n1 => Branch Manager',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resume` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active,0=inactive',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_staff_password_resets`
--

CREATE TABLE `branch_staff_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `continent` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calling_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cron_schedule_id` int NOT NULL DEFAULT '0',
  `next_run` datetime DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `is_running` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_jobs`
--

INSERT INTO `cron_jobs` (`id`, `name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Loan Cron', 'loan_cron', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"loan\"]', NULL, 1, '2024-09-02 10:27:01', '2024-09-02 10:22:01', 1, 1, '2023-06-22 03:29:14', '2024-09-02 04:22:01'),
(2, 'Dps Cron', 'dps_cron', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"dps\"]', NULL, 1, '2024-09-02 10:27:01', '2024-09-02 10:22:01', 1, 1, '2023-06-22 03:29:14', '2024-09-02 04:22:01'),
(3, 'Fdr Cron', 'fdr_cron', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"fdr\"]', NULL, 1, '2024-09-02 10:27:01', '2024-09-02 10:22:01', 1, 1, '2023-06-22 03:29:14', '2024-09-02 04:22:01'),
(4, 'Update Reloadly Operators', 'update_operator', '[\"App\\\\Http\\\\Controllers\\\\CronController\", \"updateOperators\"]', NULL, 1, '2024-09-02 10:27:03', '2024-09-02 10:22:03', 1, 1, '2023-06-21 11:29:14', '2024-09-02 04:22:03');

-- --------------------------------------------------------

--
-- Table structure for table `cron_job_logs`
--

CREATE TABLE `cron_job_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `cron_job_id` int UNSIGNED NOT NULL DEFAULT '0',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `duration` int NOT NULL DEFAULT '0',
  `error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_schedules`
--

CREATE TABLE `cron_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_schedules`
--

INSERT INTO `cron_schedules` (`id`, `name`, `interval`, `status`, `created_at`, `updated_at`) VALUES
(1, '5 Minutes', 300, 1, '2023-07-22 08:03:29', '2023-07-22 08:03:29'),
(2, '10 Minutes', 600, 1, '2023-07-22 08:03:35', '2023-07-22 08:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `branch_id` int UNSIGNED NOT NULL DEFAULT '0',
  `branch_staff_id` int UNSIGNED NOT NULL DEFAULT '0',
  `method_code` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `method_currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_try` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `admin_feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `success_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `is_app` tinyint(1) NOT NULL DEFAULT '0',
  `token` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dps`
--

CREATE TABLE `dps` (
  `id` bigint UNSIGNED NOT NULL,
  `dps_number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `plan_id` int UNSIGNED NOT NULL,
  `per_installment` decimal(28,8) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `installment_interval` int NOT NULL COMMENT 'In Day',
  `delay_value` int NOT NULL DEFAULT '1' COMMENT 'In Day',
  `charge_per_installment` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `delay_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `given_installment` int NOT NULL DEFAULT '0',
  `total_installment` int NOT NULL DEFAULT '0',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 = Running, 2 = Matured, 0 = Closed',
  `withdrawn_at` date DEFAULT NULL,
  `due_notification_sent` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dps_plans`
--

CREATE TABLE `dps_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_installment` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `installment_interval` int NOT NULL DEFAULT '0' COMMENT 'In Day',
  `total_installment` int NOT NULL DEFAULT '0',
  `interest_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `delay_value` int NOT NULL DEFAULT '1',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcode` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'object',
  `support` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'help section',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `act`, `name`, `description`, `image`, `script`, `shortcode`, `support`, `status`, `created_at`, `updated_at`) VALUES
(1, 'tawk-chat', 'Tawk.to', 'Key location is shown bellow', 'tawky_big.png', '<script>\r\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\r\n                        (function(){\r\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\r\n                        s1.async=true;\r\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\r\n                        s1.charset=\"UTF-8\";\r\n                        s1.setAttribute(\"crossorigin\",\"*\");\r\n                        s0.parentNode.insertBefore(s1,s0);\r\n                        })();\r\n                    </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"------\"}}', 'twak.png', 0, '2019-10-18 05:16:05', '2024-08-18 07:18:30'),
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'Key location is shown bellow', 'recaptcha3.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"6LdPC88fAAAAADQlUf_DV6Hrvgm-pZuLJFSLDOWV\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"6LdPC88fAAAAAG5SVaRYDnV2NpCrptLg2XLYKRKB\"}}', 'recaptcha.png', 0, '2019-10-18 05:16:05', '2024-08-18 07:18:21'),
(3, 'custom-captcha', 'Custom Captcha', 'Just put any random string', 'customcaptcha.png', NULL, '{\"random_key\":{\"title\":\"Random String\",\"value\":\"SecureString\"}}', 'na', 0, '2019-10-18 05:16:05', '2024-08-18 07:18:06'),
(4, 'google-analytics', 'Google Analytics', 'Key location is shown bellow', 'google_analytics.png', '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>', '{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}', 'ganalytics.png', 0, NULL, '2024-08-18 07:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `fdrs`
--

CREATE TABLE `fdrs` (
  `id` bigint UNSIGNED NOT NULL,
  `fdr_number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `plan_id` int UNSIGNED NOT NULL,
  `amount` decimal(28,8) NOT NULL,
  `per_installment` decimal(28,8) NOT NULL,
  `installment_interval` int NOT NULL COMMENT 'In Day',
  `profit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 = Running, 2= Closed',
  `next_installment_date` date DEFAULT NULL,
  `locked_date` date DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fdr_plans`
--

CREATE TABLE `fdr_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `maximum_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `installment_interval` int NOT NULL DEFAULT '0' COMMENT 'In Day',
  `interest_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `locked_days` int NOT NULL DEFAULT '0',
  `status` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `frontends`
--

CREATE TABLE `frontends` (
  `id` int UNSIGNED NOT NULL,
  `tempname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_keys` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frontends`
--

INSERT INTO `frontends` (`id`, `tempname`, `slug`, `data_keys`, `data_values`, `seo_content`, `created_at`, `updated_at`) VALUES
(1, 'global', NULL, 'seo.data', '{\"seo_image\":\"1\",\"keywords\":[\"bank\",\"e-banking\",\"digital banking\",\"digital bank\",\"laon\",\"deposit\",\"fdr\",\"dps\"],\"description\":\"Viserbank is a complete e-Banking system. We have account-holders from almost all over the world. This is getting popular day by day. Our system is secure and robust. You may feel safe about your deposited funds.\",\"social_title\":\"Viserbank\",\"social_description\":\"Viserbank is a complete e-Banking system. We have account-holders from almost all over the world. This is getting popular day by day. Our system is secure and robust. You may feel safe about your deposited funds.\",\"image\":\"65ffd794c1eec1711265684.png\"}', NULL, '2020-07-04 11:42:52', '2024-03-23 19:34:44'),
(24, 'indigo_fusion', NULL, 'about.content', '{\"has_image\":\"1\",\"title\":\"About Us\",\"heading\":\"We care about your money and safety.\",\"video_link\":\"https:\\/\\/www.youtube.com\\/embed\\/WOb4cj7izpE\",\"subheading\":\"Viserbank is a complete e-Banking system. We have account-holders from almost all over the world. This is getting popular day by day. Our system is secure and robust. You may feel safe about your deposited funds.\",\"image\":\"60c75675a19651623676533.jpg\"}', NULL, '2020-10-27 12:51:20', '2022-10-09 07:31:40'),
(25, 'indigo_fusion', NULL, 'blog.content', '{\"heading\":\"Latest News\",\"subheading\":\"Hic tenetur nihil ex. Doloremque ipsa velit, ea molestias expedita sed voluptatem ex voluptatibus temporibus sequi. sddd\"}', NULL, '2020-10-27 12:51:34', '2020-10-27 12:52:52'),
(26, 'indigo_fusion', 'this-is-a-test-blog-2', 'blog.element', '{\"has_image\":[\"1\",\"1\"],\"title\":\"this is a test blog 2\",\"description\":\"aewf asdf\",\"description_nic\":\"asdf asdf\",\"blog_icon\":\"<i class=\\\"lab la-hornbill\\\"><\\/i>\",\"blog_image_1\":\"5f99164f1baec1603868239.jpg\",\"blog_image_2\":\"5ff2e146346d21609752902.jpg\"}', NULL, '2020-10-27 12:57:19', '2021-01-03 15:35:02'),
(27, 'indigo_fusion', NULL, 'contact_us.content', '{\"heading\":\"Feel free to contact us\",\"map_source\":\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d15248.978521521234!2d-73.75141171038925!3d40.67880542694389!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1628170138354!5m2!1sen!2sbd\"}', NULL, '2020-10-27 12:59:19', '2021-08-05 07:29:21'),
(28, 'indigo_fusion', NULL, 'counter.content', '{\"heading\":\"Latest News\",\"subheading\":\"Register New Account\"}', NULL, '2020-10-27 13:04:02', '2020-10-27 13:04:02'),
(30, 'indigo_fusion', 'this-is-test-blog-1', 'blog.element', '{\"has_image\":[\"1\",\"1\"],\"title\":\"This is test blog 1\",\"description\":\"asdfasdf ffffffffff\",\"description_nic\":\"asdfasdf asdd vvvvvvvvvvvvvvvvvv\",\"blog_icon\":\"<i class=\\\"las la-highlighter\\\"><\\/i>\",\"blog_image_1\":\"5f9d0689e022d1604126345.jpg\",\"blog_image_2\":\"5f9d068a341211604126346.jpg\"}', NULL, '2020-10-30 12:39:05', '2020-11-11 16:36:39'),
(31, 'indigo_fusion', NULL, 'social_icon.element', '{\"title\":\"Facebook\",\"social_icon\":\"<i class=\\\"lab la-facebook\\\"><\\/i>\",\"url\":\"https:\\/\\/www.google.com\\/\"}', NULL, '2020-11-11 16:07:30', '2020-11-11 16:07:30'),
(33, 'indigo_fusion', NULL, 'feature.content', '{\"heading\":\"asdf\",\"subheading\":\"asdf\"}', NULL, '2021-01-03 11:40:54', '2021-01-03 11:40:55'),
(35, 'indigo_fusion', NULL, 'service.element', '{\"heading\":\"Withdraw Funds\",\"subheading\":\"Account-holders of Viserbank are able to withdraw money from their account. Without verification, any withdrawal won\'t be completed, so you can trust Viserbank.\",\"icon\":\"<i class=\\\"fas fa-money-check-alt\\\"><\\/i>\"}', NULL, '2021-03-05 13:12:10', '2021-07-27 04:07:56'),
(36, 'indigo_fusion', NULL, 'service.content', '{\"title\":\"Our Services\",\"heading\":\"We make your life comfortable with our services.\"}', NULL, '2021-03-05 13:27:34', '2021-07-09 23:54:05'),
(39, 'indigo_fusion', NULL, 'banner.content', '{\"has_image\":\"1\",\"heading\":\"Welcome to the Largest E-banking System\",\"subheading\":\"Viserbank is a safe, fast, easy, and efficient e-Banking system that enables you access to your bank account and to carry out online banking services, 24\\/7\",\"button_text\":\"Create an Account\",\"button_link\":\"user\\/register\",\"image\":\"60c756944f1271623676564.jpg\"}', NULL, '2021-05-01 18:09:30', '2022-10-09 03:42:25'),
(41, 'indigo_fusion', NULL, 'feature.element', '{\"heading\":\"Transfer Money\",\"subheading\":\"You are able to transfer your funds within the Viserbank or other banks we support by adding your beneficiaries\",\"icon\":\"<i class=\\\"fas fa-exchange-alt\\\"><\\/i>\"}', NULL, '2021-05-07 17:57:51', '2021-07-27 03:16:21'),
(42, 'indigo_fusion', NULL, 'feature.element', '{\"heading\":\"Deposit Schemes\",\"subheading\":\"We have two deposit schemes for you, one is Deposit Pension Scheme and another one is the Fixed Deposit Receipt.\",\"icon\":\"<i class=\\\"fas fa-wallet\\\"><\\/i>\"}', NULL, '2021-05-07 18:00:25', '2021-07-27 03:13:04'),
(43, 'indigo_fusion', NULL, 'feature.element', '{\"heading\":\"Take Loan\",\"subheading\":\"We have several plans to apply for a loan. You may apply to our loan plans by submitting some of your valid information.\",\"icon\":\"<i class=\\\"fas fa-coins\\\"><\\/i>\"}', NULL, '2021-05-07 18:03:20', '2021-07-27 03:08:43'),
(44, 'indigo_fusion', NULL, 'feature.element', '{\"heading\":\"Online Payment\",\"subheading\":\"We have online payment services like PayPal, Stripe, Paystack, Skrill, Flutterwave, Mollie, Payeer, RazorPay, etc.\",\"icon\":\"<i class=\\\"fas fa-file-invoice-dollar\\\"><\\/i>\"}', NULL, '2021-05-07 18:04:11', '2021-07-27 03:02:17'),
(45, 'indigo_fusion', NULL, 'about.element', '{\"heading\":\"Our Goal\",\"subheading\":\"Viserbank will serve their customers from all over the world and becomes the popular bank in this universe.\",\"icon\":\"<i class=\\\"las la-bullseye\\\"><\\/i>\"}', NULL, '2021-05-07 19:22:54', '2021-07-27 03:25:56'),
(46, 'indigo_fusion', NULL, 'about.element', '{\"heading\":\"Our Vision\",\"subheading\":\"Viserbank will serve all over the world and becomes the most popular Bank in this universe.\",\"icon\":\"<i class=\\\"far fa-eye\\\"><\\/i>\"}', NULL, '2021-05-07 19:23:32', '2021-07-29 13:27:28'),
(47, 'indigo_fusion', NULL, 'about.element', '{\"heading\":\"Our Mission\",\"subheading\":\"We are focused on building and sustaining long-term generational relationships with our customers\",\"icon\":\"<i class=\\\"las la-hourglass-start\\\"><\\/i>\"}', NULL, '2021-05-07 19:24:08', '2021-07-27 03:20:33'),
(48, 'indigo_fusion', NULL, 'service.element', '{\"heading\":\"Deposit Funds\",\"subheading\":\"Account-holders of Viserbank are able to deposit their money through our several payment systems. We have online payment services like PayPal, Stripe, Paystack, Skrill, Flutterwave, Mollie, Payeer, etc.\",\"icon\":\"<i class=\\\"fas fa-credit-card\\\"><\\/i>\"}', NULL, '2021-05-07 20:12:19', '2021-07-29 14:15:05'),
(49, 'indigo_fusion', NULL, 'service.element', '{\"heading\":\"Fast Transfer\",\"subheading\":\"Our Money transfer system is secure and easy. Send your funds to your beneficiaries within Viserbank or to other banks. Transfer within Viserbank is instant and to other banks may take 24 hours.\",\"icon\":\"<i class=\\\"las la-exchange-alt\\\"><\\/i>\"}', NULL, '2021-05-07 20:14:20', '2021-07-27 03:53:45'),
(50, 'indigo_fusion', NULL, 'why_choose.content', '{\"has_image\":\"1\",\"title\":\"Why Choose Us?\",\"heading\":\"We are giving you the best services\",\"btn_text\":\"Get Started\",\"btn_link\":\"register\",\"image\":\"60d497b25b98a1624545202.jpg\"}', NULL, '2021-05-07 20:28:54', '2021-07-27 04:12:20'),
(51, 'indigo_fusion', NULL, 'why_choose.element', '{\"heading\":\"Lowest Transaction Fee\",\"subheading\":\"Our transaction fee is much low comparing to other banks. You can deposit, transfer, and withdraw your funds with the lowest transaction charge. As our transfer system is secure and robust you can trust us.\",\"icon\":\"<i class=\\\"fas fa-file-invoice-dollar\\\"><\\/i>\"}', NULL, '2021-05-07 20:40:57', '2021-07-27 04:26:06'),
(52, 'indigo_fusion', NULL, 'why_choose.element', '{\"heading\":\"Secure Service\",\"subheading\":\"Every balance subtracting transactions need OTP verification so You can feel safe about your funds. Also, you can use the google authenticator app on your cellphone and enable 2FA security from the account menu.\",\"icon\":\"<i class=\\\"las la-user-shield\\\"><\\/i>\"}', NULL, '2021-05-07 20:41:23', '2021-07-27 04:20:23'),
(53, 'indigo_fusion', NULL, 'how_it_work.content', '{\"title\":\"How it works\",\"heading\":\"It\'s easy to join with Us\"}', NULL, '2021-05-07 20:49:36', '2021-07-08 06:46:04'),
(54, 'indigo_fusion', NULL, 'how_it_work.element', '{\"heading\":\"Open an Account\",\"subheading\":\"To be an account holder you have to open an account first.\"}', NULL, '2021-05-07 20:52:30', '2021-07-27 04:29:58'),
(55, 'indigo_fusion', NULL, 'how_it_work.element', '{\"heading\":\"Verification\",\"subheading\":\"After registration you need to verify your Email and Mobile Number.\"}', NULL, '2021-05-07 20:52:59', '2021-07-29 13:35:50'),
(56, 'indigo_fusion', NULL, 'how_it_work.element', '{\"heading\":\"Deposit\",\"subheading\":\"Deposit some funds before applying on any FDR or DPS plans.\"}', NULL, '2021-05-07 20:53:21', '2021-07-27 04:32:22'),
(57, 'indigo_fusion', NULL, 'how_it_work.element', '{\"heading\":\"Get Service\",\"subheading\":\"Now you can get any of our services as our registered account-holder\"}', NULL, '2021-05-07 20:53:54', '2021-07-27 04:37:35'),
(58, 'indigo_fusion', NULL, 'fdr.content', '{\"heading\":\"Our investment plan for your future plan.\"}', NULL, '2021-05-07 21:05:40', '2021-05-07 21:05:40'),
(59, 'indigo_fusion', NULL, 'loan_plans.content', '{\"title\":\"Our Loan Schemes\",\"heading\":\"We Have The Best Loan Plans\"}', NULL, '2021-05-07 21:11:23', '2022-10-10 07:05:47'),
(60, 'indigo_fusion', NULL, 'overview.content', '{\"heading\":\"We provide our banking services all over the world\",\"subheading\":\"Viser Bank is a secure and robust e-Banking system getting popular all over the world nowadays. We offer the best FDR, DPS &amp; Loan plans to our account holders\"}', NULL, '2021-05-07 21:33:10', '2021-07-27 05:32:26'),
(61, 'indigo_fusion', NULL, 'overview.element', '{\"heading\":\"24M\",\"subheading\":\"Account Holders\",\"icon\":\"<i class=\\\"las la-user-circle\\\"><\\/i>\"}', NULL, '2021-05-07 21:34:45', '2021-07-27 05:32:55'),
(62, 'indigo_fusion', NULL, 'overview.element', '{\"heading\":\"3B\",\"subheading\":\"Total Transaction\",\"icon\":\"<i class=\\\"las la-coins\\\"><\\/i>\"}', NULL, '2021-05-07 21:39:30', '2021-05-07 21:39:30'),
(63, 'indigo_fusion', NULL, 'overview.element', '{\"heading\":\"120\",\"subheading\":\"Total Branches\",\"icon\":\"<i class=\\\"las la-project-diagram\\\"><\\/i>\"}', NULL, '2021-05-07 21:39:58', '2021-05-07 21:39:58'),
(65, 'indigo_fusion', NULL, 'testimonial.content', '{\"quote\":\"Startup Institute is a career accelerator that allows professionals to learn new skills, take their careers in a different direction, and pursue a career they are passionate about.\",\"rating\":\"5\"}', NULL, '2021-05-07 22:01:15', '2021-05-07 22:01:15'),
(70, 'indigo_fusion', NULL, 'faq.content', '{\"heading\":\"Frequently Asked Questions\",\"subheading\":\"Though we have provided lots of information about us and how we serve what is our working process our terms and conditions our policies etc.\"}', NULL, '2021-05-07 22:25:30', '2021-08-10 01:08:42'),
(71, 'indigo_fusion', NULL, 'faq.element', '{\"question\":\"Is opening an account is free?\",\"answer\":\"Yes, we don\'t take any fees for opening an account.\"}', NULL, '2021-05-07 22:32:11', '2021-07-27 06:03:43'),
(72, 'indigo_fusion', NULL, 'faq.element', '{\"question\":\"Is it possible to send money from Viserbank to another bank?\",\"answer\":\"Yes, you can send money from Viserbank to another bank?\"}', NULL, '2021-05-07 22:33:29', '2021-07-29 13:50:38'),
(73, 'indigo_fusion', NULL, 'subscribe.content', '{\"has_image\":\"1\",\"heading\":\"Subscribe our newsletter and stay connected\",\"image\":\"610fce58d3ed41628425816.jpg\"}', NULL, '2021-05-07 22:53:45', '2021-08-08 06:30:17'),
(74, 'indigo_fusion', NULL, 'footer.content', '{\"text\":\"Copyright \\u00a9 2021  Viserbank All Right Reserved\"}', NULL, '2021-05-07 23:47:32', '2021-07-27 08:18:36'),
(75, 'indigo_fusion', NULL, 'footer.element', '{\"social_link\":\"https:\\/\\/www.facebook.com\\/\",\"social_icon\":\"<i class=\\\"fab fa-facebook-f\\\"><\\/i>\"}', NULL, '2021-05-07 23:55:42', '2021-05-07 23:55:42'),
(76, 'indigo_fusion', NULL, 'footer.element', '{\"social_link\":\"https:\\/\\/twitter.com\\/\",\"social_icon\":\"<i class=\\\"fab fa-twitter\\\"><\\/i>\"}', NULL, '2021-05-07 23:56:08', '2021-05-07 23:56:08'),
(77, 'indigo_fusion', NULL, 'footer.element', '{\"social_link\":\"https:\\/\\/www.instagram.com\\/\",\"social_icon\":\"<i class=\\\"fab fa-instagram\\\"><\\/i>\"}', NULL, '2021-05-07 23:56:24', '2021-05-07 23:56:24'),
(78, 'indigo_fusion', NULL, 'footer.element', '{\"social_link\":\"https:\\/\\/bd.linkedin.com\\/\",\"social_icon\":\"<i class=\\\"fab fa-linkedin-in\\\"><\\/i>\"}', NULL, '2021-05-07 23:56:46', '2021-05-07 23:56:46'),
(79, 'indigo_fusion', NULL, 'contact_us.element', '{\"address_type\":\"Mobile Number\",\"address\":\"+454512418544\",\"icon\":\"<i class=\\\"fas fa-phone\\\"><\\/i>\"}', NULL, '2021-05-08 15:15:24', '2022-08-24 04:56:24'),
(80, 'indigo_fusion', NULL, 'contact_us.element', '{\"address_type\":\"Email Address\",\"address\":\"demo@gmail.com\",\"icon\":\"<i class=\\\"fas fa-envelope\\\"><\\/i>\"}', NULL, '2021-05-08 15:15:49', '2021-05-08 15:15:49'),
(81, 'indigo_fusion', NULL, 'contact_us.element', '{\"address_type\":\"Office Address\",\"address\":\"Null Street, XYZ, Universe\",\"icon\":\"<i class=\\\"fas fa-map-marked\\\"><\\/i>\"}', NULL, '2021-05-08 15:16:13', '2021-07-27 06:41:59'),
(82, 'indigo_fusion', NULL, 'breadcumb.content', '{\"has_image\":\"1\",\"image\":\"60c7569dec4f01623676573.jpg\"}', NULL, '2021-05-08 15:56:53', '2021-06-14 00:46:14'),
(83, 'indigo_fusion', NULL, 'page.element', '{\"page_name\":\"Terms\",\"page_content\":\"<span style=\\\"color:rgb(0,0,0);font-family:\'Open Sans\', Arial, sans-serif;font-size:14px;text-align:justify;\\\">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.<\\/span><br \\/>\"}', NULL, '2021-06-13 18:54:31', '2021-06-13 18:54:31'),
(88, 'indigo_fusion', NULL, 'faq.element', '{\"question\":\"How to open an account?\",\"answer\":\"Get the registration form by clicking on the Sing Up button on the top bar. Provide all information and click on the Sign Up button.\"}', NULL, '2021-06-24 02:06:28', '2021-07-27 06:11:02'),
(89, 'indigo_fusion', NULL, 'faq.element', '{\"question\":\"Does Viserbank share our information for advertisement?\",\"answer\":\"No, we don\'t provide our account holder\'s information to any third-party organization.\"}', NULL, '2021-06-24 02:06:46', '2021-07-27 06:16:25'),
(92, 'indigo_fusion', NULL, 'overview.element', '{\"heading\":\"240+\",\"subheading\":\"Countries We Serve\",\"icon\":\"<i class=\\\"las la-globe-africa\\\"><\\/i>\"}', NULL, '2021-06-28 22:46:02', '2021-07-27 05:33:52'),
(94, 'indigo_fusion', NULL, 'fdr_plans.content', '{\"heading\":\"Fixed Deposit Scheme\",\"subheading\":\"Efforts are our rewards are yours!\"}', NULL, '2021-07-16 08:29:06', '2021-07-17 14:09:34'),
(95, 'indigo_fusion', NULL, 'dps_plans.content', '{\"heading\":\"Deposit Pension Scheme\",\"subheading\":\"Grow your deposit with us.\"}', NULL, '2021-07-16 09:14:07', '2021-07-16 09:14:07'),
(96, 'indigo_fusion', NULL, 'partner_section.content', '{\"heading\":\"Our Partners\"}', NULL, '2021-07-17 07:40:53', '2021-07-17 07:40:53'),
(97, 'indigo_fusion', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"610007f09805f1627391984.png\"}', NULL, '2021-07-17 07:41:24', '2021-07-27 07:19:44'),
(98, 'indigo_fusion', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"6100084858a641627392072.png\"}', NULL, '2021-07-17 07:41:33', '2021-07-27 07:21:12'),
(99, 'indigo_fusion', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"6100088e88e2d1627392142.png\"}', NULL, '2021-07-17 07:41:39', '2021-07-27 07:22:22'),
(100, 'indigo_fusion', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"610008c8078b71627392200.png\"}', NULL, '2021-07-17 07:41:45', '2021-07-27 07:23:20'),
(101, 'indigo_fusion', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"61000905dec851627392261.png\"}', NULL, '2021-07-17 07:41:51', '2021-07-27 07:24:21'),
(102, 'indigo_fusion', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"610009d71ce7c1627392471.png\"}', NULL, '2021-07-17 07:41:57', '2021-07-27 07:27:51'),
(103, 'indigo_fusion', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"61000a7d441c71627392637.png\"}', NULL, '2021-07-17 07:42:06', '2021-07-27 07:30:37'),
(105, 'indigo_fusion', NULL, 'cookie.data', '{\"short_desc\":\"We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience\",\"description\":\"<div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n gather data from you when you register on our site, submit a request, \\r\\nbuy any services, react to an overview, or round out a structure. At the\\r\\n point when requesting any assistance or enrolling on our site, as \\r\\nsuitable, you might be approached to enter your: name, email address, or\\r\\n telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br>After\\r\\n an exchange, your private data (credit cards, social security numbers, \\r\\nfinancials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n don\'t sell, exchange, or in any case move to outside gatherings by and \\r\\nby recognizable data. This does exclude confided in outsiders who help \\r\\nus in working our site, leading our business, or adjusting you, since \\r\\nthose gatherings consent to keep this data private. We may likewise \\r\\ndeliver your data when we accept discharge is suitable to follow the \\r\\nlaw, implement our site strategies, or ensure our own or others\' rights,\\r\\n property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n are consistent with the prerequisites of COPPA (Children\'s Online \\r\\nPrivacy Protection Act), we don\'t gather any data from anybody under 13 \\r\\nyears old. Our site, items, and administrations are completely \\r\\ncoordinated to individuals who are in any event 13 years of age or more \\r\\nestablished.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At\\r\\n the point when you register for our site, we cycle and keep your \\r\\ninformation we have about you however long you don\'t erase the record or\\r\\n withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n don\'t and will never share, unveil, sell, or in any case give your \\r\\ninformation to different organizations for the promoting of their items \\r\\nor administrations.<\\/p><\\/div>\",\"status\":1}', NULL, '2020-07-04 11:42:52', '2022-12-08 03:54:34'),
(107, 'indigo_fusion', NULL, 'testimonial.element', '{\"name\":\"John Smith\",\"designation\":\"CEO of CY\",\"quote\":\"Best quality service ever I had. The money transfer system is just awesome. The beneficiary listing system makes it quite efficient.\",\"rating\":\"5\"}', NULL, '2021-07-27 05:39:24', '2021-07-27 05:42:36'),
(108, 'indigo_fusion', NULL, 'testimonial.element', '{\"name\":\"Michel Johnson\",\"designation\":\"Founder of ZZ\",\"quote\":\"I had opened an account 3 years ago, I feel safe keeping my funds in Viserbank. Their Deposit schemes plans are really helpful\",\"rating\":\"5\"}', NULL, '2021-07-27 05:41:19', '2021-07-27 05:46:12'),
(109, 'indigo_fusion', NULL, 'testimonial.element', '{\"name\":\"Maria Ahsan\",\"designation\":\"Managing Director, YY\",\"quote\":\"The is just awesome,  best quality service ever I had. You can trust them and deposit your funds. Their Loan plans are really helpful\",\"rating\":\"5\"}', NULL, '2021-07-27 05:50:09', '2021-07-27 05:50:09'),
(110, 'indigo_fusion', NULL, 'testimonial.element', '{\"name\":\"Adam Gilly\",\"designation\":\"CTO, UYT\",\"quote\":\"I had opened an account 5 years ago, I feel safe keeping my funds in Viserbank. Their Deposit schemes plans are really helpful.\",\"rating\":\"5\"}', NULL, '2021-07-27 05:51:31', '2021-07-27 05:51:31'),
(111, 'indigo_fusion', 'company-policy', 'policy_pages.element', '{\"title\":\"Company Policy\",\"content\":\"<div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', NULL, '2021-07-27 06:55:55', '2021-07-27 06:55:55'),
(112, 'indigo_fusion', NULL, 'login_bg.content', '{\"has_image\":\"1\",\"heading\":\"Welcome Back!\",\"subheading\":\"Provide your username and password and login into your account\",\"image\":\"6110c9ec14e281628490220.jpg\"}', NULL, '2021-08-09 00:23:40', '2022-10-09 06:36:00'),
(113, 'indigo_fusion', NULL, 'signup_bg.content', '{\"has_image\":\"1\",\"heading\":\"Create New Account\",\"subheading\":\"You have to provide all of your valid information as we want.\",\"image\":\"6110d5890dc201628493193.jpg\"}', NULL, '2021-08-09 01:00:36', '2022-10-09 06:34:45'),
(114, 'indigo_fusion', NULL, 'forget_pass.content', '{\"has_image\":\"1\",\"heading\":\"Reset Password\",\"subheading\":\"Nothing to get worried about. You can always set a new password as long you have the access to the email you registered with.\",\"image\":\"6110ecaeaa1c11628499118.jpg\"}', NULL, '2021-08-09 02:51:58', '2021-08-09 02:51:59'),
(115, 'indigo_fusion', NULL, 'faq.element', '{\"question\":\"How to take a loan?\",\"answer\":\"We have several loan plans. Choose the best plan suitable for you and just click on the Apply Now button and put the amount.\"}', NULL, '2021-08-11 06:11:45', '2021-08-11 06:13:13'),
(117, 'indigo_fusion', 'privacy-policy', 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"content\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', NULL, '2021-06-08 20:50:42', '2022-08-24 23:47:13'),
(118, 'indigo_fusion', 'terms-of-service', 'policy_pages.element', '{\"title\":\"Terms of Service\",\"content\":\"<div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n gather data from you when you register on our site, submit a request, \\r\\nbuy any services, react to an overview, or round out a structure. At the\\r\\n point when requesting any assistance or enrolling on our site, as \\r\\nsuitable, you might be approached to enter your: name, email address, or\\r\\n telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After\\r\\n an exchange, your private data (credit cards, social security numbers, \\r\\nfinancials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n don\'t sell, exchange, or in any case move to outside gatherings by and \\r\\nby recognizable data. This does exclude confided in outsiders who help \\r\\nus in working our site, leading our business, or adjusting you, since \\r\\nthose gatherings consent to keep this data private. We may likewise \\r\\ndeliver your data when we accept discharge is suitable to follow the \\r\\nlaw, implement our site strategies, or ensure our own or others\' rights,\\r\\n property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n are consistent with the prerequisites of COPPA (Children\'s Online \\r\\nPrivacy Protection Act), we don\'t gather any data from anybody under 13 \\r\\nyears old. Our site, items, and administrations are completely \\r\\ncoordinated to individuals who are in any event 13 years of age or more \\r\\nestablished.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At\\r\\n the point when you register for our site, we cycle and keep your \\r\\ninformation we have about you however long you don\'t erase the record or\\r\\n withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n don\'t and will never share, unveil, sell, or in any case give your \\r\\ninformation to different organizations for the promoting of their items \\r\\nor administrations.<\\/p><\\/div><\\/div>\"}', NULL, '2021-06-08 20:51:18', '2022-10-09 03:39:40'),
(120, 'indigo_fusion', NULL, 'kyc_content.content', '{\"unverified_content\":\"Dear User, we need your KYC Data for some action. Don\'t hesitate to provide KYC Data, It\'s so much potential for us too. Don\'t worry,  it\'s very much secure in our system.\",\"pending_content\":\"Dear user, Your submitted KYC Data is currently pending now. Please take us some time to review your Data. Thank you so much for your cooperation.\"}', NULL, '2022-08-07 23:29:15', '2022-08-07 23:32:16'),
(121, 'indigo_fusion', NULL, 'registration_disabled.content', '{\"heading\":\"Registration Disabled\",\"subheading\":\"currently disabled your registration process. Please get in touch with your nearby Branch\",\"button_text\":\"Browse Home Page\",\"button_link\":\"\\/\"}', NULL, '2022-08-17 05:08:18', '2022-08-17 05:14:14'),
(122, 'indigo_fusion', NULL, 'faq.element', '{\"question\":\"How to open a FDR\",\"answer\":\"e have several FDR plans. Choose the best plan suitable for you and just click on the Apply Now button and put the amount.\"}', NULL, '2022-08-24 04:45:32', '2022-08-24 04:47:39'),
(123, 'indigo_fusion', NULL, 'maintenance.data', '{\"description\":\"<h2 style=\\\"text-align: center;\\\"><span style=\\\"color: var(--bs-body-color); text-align: var(--bs-body-text-align);\\\"><font size=\\\"6\\\">We\'re just tuning up a few things.<\\/font><\\/span><\\/h2><p>We apologize for the inconvenience but Front is currently undergoing planned maintenance. Thanks for your patience.<br><\\/p>\",\"image\":\"66bb2f47324771723543367.jpg\"}', NULL, NULL, '2024-08-12 22:02:47'),
(124, 'indigo_fusion', NULL, 'banned.content', '{\"has_image\":\"1\",\"heading\":\"You Are Banned\",\"image\":\"637ef312270a61669264146.png\"}', NULL, '2022-11-23 22:29:06', '2022-11-23 22:29:06'),
(125, 'crystal_sky', 'company-policy', 'policy_pages.element', '{\"title\":\"Company Policy\",\"content\":\"<div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', NULL, '2023-08-28 23:30:18', '2023-08-28 23:30:18');
INSERT INTO `frontends` (`id`, `tempname`, `slug`, `data_keys`, `data_values`, `seo_content`, `created_at`, `updated_at`) VALUES
(126, 'crystal_sky', 'privacy-policy', 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"content\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;padding:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', NULL, '2023-08-28 23:30:18', '2023-08-28 23:30:18'),
(127, 'crystal_sky', 'terms-of-service', 'policy_pages.element', '{\"title\":\"Terms of Service\",\"content\":\"<div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n gather data from you when you register on our site, submit a request, \\r\\nbuy any services, react to an overview, or round out a structure. At the\\r\\n point when requesting any assistance or enrolling on our site, as \\r\\nsuitable, you might be approached to enter your: name, email address, or\\r\\n telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After\\r\\n an exchange, your private data (credit cards, social security numbers, \\r\\nfinancials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n don\'t sell, exchange, or in any case move to outside gatherings by and \\r\\nby recognizable data. This does exclude confided in outsiders who help \\r\\nus in working our site, leading our business, or adjusting you, since \\r\\nthose gatherings consent to keep this data private. We may likewise \\r\\ndeliver your data when we accept discharge is suitable to follow the \\r\\nlaw, implement our site strategies, or ensure our own or others\' rights,\\r\\n property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n are consistent with the prerequisites of COPPA (Children\'s Online \\r\\nPrivacy Protection Act), we don\'t gather any data from anybody under 13 \\r\\nyears old. Our site, items, and administrations are completely \\r\\ncoordinated to individuals who are in any event 13 years of age or more \\r\\nestablished.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At\\r\\n the point when you register for our site, we cycle and keep your \\r\\ninformation we have about you however long you don\'t erase the record or\\r\\n withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We\\r\\n don\'t and will never share, unveil, sell, or in any case give your \\r\\ninformation to different organizations for the promoting of their items \\r\\nor administrations.<\\/p><\\/div><\\/div>\"}', NULL, '2023-08-28 23:30:18', '2023-08-28 23:30:18'),
(128, 'crystal_sky', NULL, 'footer.content', '{\"title\":\"About Us\",\"contact_title\":\"Contact With Us\",\"description\":\"Viserbank is a complete e-Banking system. We have account-holders from almost all over the world. This is getting popular day by day.\"}', NULL, '2023-08-28 23:35:10', '2023-08-29 19:49:04'),
(129, 'crystal_sky', NULL, 'subscribe.content', '{\"heading\":\"Subscribe our newsletter and stay connected\"}', NULL, '2023-08-29 19:52:59', '2023-08-29 19:52:59'),
(130, 'crystal_sky', NULL, 'social_link.element', '{\"social_icon\":\"<i class=\\\"fab fa-facebook\\\"><\\/i>\",\"social_link\":\"https:\\/\\/www.facebook.com\\/\"}', NULL, '2023-08-29 19:56:42', '2023-08-29 19:56:42'),
(131, 'crystal_sky', NULL, 'social_link.element', '{\"social_icon\":\"<i class=\\\"fab fa-twitter\\\"><\\/i>\",\"social_link\":\"https:\\/\\/www.twitter.com\\/\"}', NULL, '2023-08-29 19:56:57', '2023-08-29 19:56:57'),
(132, 'crystal_sky', NULL, 'social_link.element', '{\"social_icon\":\"<i class=\\\"fab fa-instagram\\\"><\\/i>\",\"social_link\":\"https:\\/\\/www.instagram.com\\/\"}', NULL, '2023-08-29 19:57:10', '2023-08-29 19:57:10'),
(133, 'crystal_sky', NULL, 'social_link.element', '{\"social_icon\":\"<i class=\\\"fab fa-linkedin-in\\\"><\\/i>\",\"social_link\":\"https:\\/\\/www.linkedin.com\\/\"}', NULL, '2023-08-29 19:57:22', '2023-08-29 19:57:22'),
(134, 'crystal_sky', NULL, 'banner.content', '{\"has_image\":\"1\",\"heading\":\"Welcome To The E-Banking System\",\"total_user\":\"18K\",\"title\":\"Happy User\",\"button_text\":\"Create an Account\",\"button_link\":\"user\\/login\",\"video_link\":\"https:\\/\\/www.youtube.com\\/embed\\/WOb4cj7izpE\",\"image\":\"64f849b6c03871693993398.png\",\"video_thumbnail\":\"64f84e91e82bc1693994641.png\",\"user_images\":\"64ef16f53b4581693390581.png\"}', NULL, '2023-08-29 20:11:14', '2023-09-06 09:38:10'),
(135, 'crystal_sky', NULL, 'contact_us.content', '{\"heading\":\"Contact Us\",\"subheading\":\"Feel Free To Contact Us\",\"contact_address\":\"PO Box 223158 Oliver Street East Victoria 2006 UK\",\"contact_number\":\"+99 - 0012 - 233\",\"email_address\":\"viserbank@site.com\",\"map_source\":\"https:\\/\\/www.google.com\\/maps\\/embed?pb=!1m18!1m12!1m3!1d195657.36758555457!2d-83.15597835661032!3d39.9828342635494!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x883889c1b990de71%3A0xe43266f8cfb1b533!2sColumbus%2C%20OH%2C%20USA!5e0!3m2!1sen!2sbd!4v1692170730789!5m2!1sen!2sbd\"}', NULL, '2023-08-29 20:33:43', '2023-08-29 20:33:43'),
(136, 'crystal_sky', NULL, 'feature.element', '{\"heading\":\"Transfer Money\",\"subheading\":\"You are able to transfer your funds within the Viserbank or other banks we support by adding your beneficiaries\",\"icon\":\"<i class=\\\"fas fa-exchange-alt\\\"><\\/i>\"}', NULL, '2023-08-29 20:40:13', '2023-08-29 20:45:30'),
(137, 'crystal_sky', NULL, 'feature.element', '{\"heading\":\"Deposit Schemes\",\"subheading\":\"We have two deposit schemes for you, one is Deposit Pension Scheme and another one is the Fixed Deposit Receipt.\",\"icon\":\"<i class=\\\"fas fa-wallet\\\"><\\/i>\"}', NULL, '2023-08-29 20:40:13', '2023-08-29 20:40:13'),
(138, 'crystal_sky', NULL, 'feature.element', '{\"heading\":\"Take Loan\",\"subheading\":\"We have several plans to apply for a loan. You may apply to our loan plans by submitting some of your valid information.\",\"icon\":\"<i class=\\\"fas fa-coins\\\"><\\/i>\"}', NULL, '2023-08-29 20:40:13', '2023-08-29 20:40:13'),
(139, 'crystal_sky', NULL, 'feature.element', '{\"heading\":\"Online Payment\",\"subheading\":\"We have online payment services like PayPal, Stripe, Paystack, Skrill, Flutterwave, Mollie, Payeer, RazorPay, etc.\",\"icon\":\"<i class=\\\"fas fa-file-invoice-dollar\\\"><\\/i>\"}', NULL, '2023-08-29 20:40:13', '2023-08-29 20:40:13'),
(140, 'crystal_sky', NULL, 'about.content', '{\"has_image\":\"1\",\"heading\":\"About Us\",\"subheading\":\"We Care About Your Money And Safety.\",\"image_popup_digit\":\"24\\/7\",\"image_popup_title\":\"Customer Support\",\"image_popup_icon\":\"<i class=\\\"las la-headset\\\"><\\/i>\",\"image\":\"64f6fc109851d1693907984.png\"}', NULL, '2023-08-29 20:54:51', '2023-09-07 05:00:41'),
(141, 'crystal_sky', NULL, 'about.element', '{\"heading\":\"Our Mission\",\"description\":\"gravida massa ultricies ut. Nam lacinia nisl ac libero suscipiporttitor Aliquam est elit, lobortis a ante imperdiet, bibendum\\r\\n\\r\\nWe are focused on building and sustaining long-term generational relationships with our customers magna leo nec risus. Donec nec risus id fringilla\"}', NULL, '2023-08-29 20:56:53', '2023-08-29 20:56:53'),
(142, 'crystal_sky', NULL, 'about.element', '{\"heading\":\"Our Vision\",\"description\":\"We are focused on building and sustaining long-term generational relationships with our customers magna leo nec risus. Donec nec risus id fringilla\"}', NULL, '2023-08-29 20:57:14', '2023-08-29 20:57:14'),
(143, 'crystal_sky', NULL, 'about.element', '{\"heading\":\"Our Goal\",\"description\":\"Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam magni nobis quibusdam debitis sequi eaque officia?\\r\\n\\r\\ngravida massa ultricies ut. Nam lacinia nisl ac libero suscipiporttitor Aliquam est elit, lobortis a ante imperdiet, bibendum\"}', NULL, '2023-08-29 20:57:31', '2023-08-29 20:57:31'),
(144, 'crystal_sky', NULL, 'service.content', '{\"has_image\":\"1\",\"heading\":\"Services\",\"subheading\":\"We Make Your Life Comfortable With Our Services.\",\"image\":\"64ef246fa32341693394031.jpg\"}', NULL, '2023-08-29 21:13:11', '2023-09-06 09:59:33'),
(145, 'crystal_sky', NULL, 'service.element', '{\"heading\":\"Withdraw Funds\",\"description\":\"In hac habitasse platea dictumst. Sed libero. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus.\",\"icon\":\"<i class=\\\"fas fa-money-check-alt\\\"><\\/i>\"}', NULL, '2023-08-29 21:13:11', '2023-08-29 21:19:38'),
(146, 'crystal_sky', NULL, 'service.element', '{\"heading\":\"Deposit Funds\",\"description\":\"In hac habitasse platea dictumst. Sed libero. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus.\",\"icon\":\"<i class=\\\"fas fa-credit-card\\\"><\\/i>\"}', NULL, '2023-08-29 21:13:11', '2023-08-29 21:19:57'),
(147, 'crystal_sky', NULL, 'service.element', '{\"heading\":\"Fast Transfer\",\"description\":\"In hac habitasse platea dictumst. Sed libero. Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis lacus.\",\"icon\":\"<i class=\\\"las la-exchange-alt\\\"><\\/i>\"}', NULL, '2023-08-29 21:13:11', '2023-08-29 21:19:53'),
(148, 'crystal_sky', NULL, 'how_it_work.content', '{\"title\":\"How it works\",\"heading\":\"It\'s easy to join with Us\"}', NULL, '2023-08-29 21:35:35', '2023-08-29 21:35:35'),
(149, 'crystal_sky', NULL, 'how_it_work.element', '{\"heading\":\"Open an Account\",\"subheading\":\"To be an account holder you have to open an account first.\"}', NULL, '2023-08-29 21:35:35', '2023-08-29 21:35:35'),
(150, 'crystal_sky', NULL, 'how_it_work.element', '{\"heading\":\"Verification\",\"subheading\":\"After registration you need to verify your Email and Mobile Number.\"}', NULL, '2023-08-29 21:35:35', '2023-08-29 21:35:35'),
(151, 'crystal_sky', NULL, 'how_it_work.element', '{\"heading\":\"Deposit\",\"subheading\":\"Deposit some funds before applying on any FDR or DPS plans.\"}', NULL, '2023-08-29 21:35:35', '2023-08-29 21:35:35'),
(152, 'crystal_sky', NULL, 'how_it_work.element', '{\"heading\":\"Get Service\",\"subheading\":\"Now you can get any of our services as our registered account-holder\"}', NULL, '2023-08-29 21:35:35', '2023-08-29 21:35:35'),
(153, 'crystal_sky', NULL, 'why_choose.content', '{\"has_image\":\"1\",\"heading\":\"Why Choose Us\",\"subheading\":\"We Are Giving You The Best Services\",\"icon\":\"<i class=\\\"las la-certificate\\\"><\\/i>\",\"title\":\"33+\",\"subtitle\":\"Years of Experience\",\"slogan\":\"Digital Banking Solution Est. 1990\",\"image_one\":\"64ef2e42075911693396546.png\",\"image_two\":\"64ef2e421fff61693396546.png\",\"circle_image\":\"64f86bc29519b1694002114.png\"}', NULL, '2023-08-29 21:53:08', '2023-09-07 05:01:38'),
(154, 'crystal_sky', NULL, 'why_choose.element', '{\"heading\":\"Lowest Transaction Fee\",\"description\":\"The OTP is a randomly generated code that is sent to your phone or email. You will need to enter this code in order to confirm.\"}', NULL, '2023-08-29 21:54:16', '2023-08-29 22:00:38'),
(155, 'crystal_sky', NULL, 'why_choose.element', '{\"heading\":\"Secure Service\",\"description\":\"The OTP is a randomly generated code that is sent to your phone or email. You will need to enter this code in order to confirm.\"}', NULL, '2023-08-29 21:54:16', '2023-08-29 22:00:45'),
(156, 'crystal_sky', NULL, 'dps_plans.content', '{\"heading\":\"Deposit Pension Scheme\",\"subheading\":\"Grow your deposit with us.\"}', NULL, '2023-08-29 22:03:06', '2023-08-29 22:03:06'),
(157, 'crystal_sky', NULL, 'testimonial.content', '{\"heading\":\"What People Say About Us\",\"subheading\":\"It\'s Easy To Join With Us\"}', NULL, '2023-08-29 22:14:38', '2023-09-05 09:09:55'),
(158, 'crystal_sky', NULL, 'testimonial.element', '{\"has_image\":[\"1\"],\"name\":\"Carolyn Matthews\",\"designation\":\"CTO, TGK\",\"quote\":\"I had opened an account 5 years ago, I feel safe keeping my funds in Viserbank. Their Deposit schemes plans are really helpful.\",\"rating\":\"4\",\"image\":\"64f8596cd69681693997420.jpg\"}', NULL, '2023-08-29 22:15:48', '2023-09-06 08:50:21'),
(159, 'crystal_sky', NULL, 'testimonial.element', '{\"has_image\":[\"1\"],\"name\":\"Selma Luna\",\"designation\":\"CEO, FKS\",\"quote\":\"I had opened an account 5 years ago, I feel safe keeping my funds in Viserbank. Their Deposit schemes plans are really helpful.\",\"rating\":\"5\",\"image\":\"64f85992d3fc71693997458.jpg\"}', NULL, '2023-08-29 22:16:08', '2023-09-06 08:50:58'),
(160, 'crystal_sky', NULL, 'testimonial.element', '{\"has_image\":[\"1\"],\"name\":\"Glenna Manning\",\"designation\":\"UI\\/UX Designer\",\"quote\":\"I had opened an account 5 years ago, I feel safe keeping my funds in Viserbank. Their Deposit schemes plans are really helpful.\",\"rating\":\"3\",\"image\":\"64ef331eb685c1693397790.png\"}', NULL, '2023-08-29 22:16:30', '2023-09-02 22:25:53'),
(161, 'crystal_sky', NULL, 'faq.content', '{\"heading\":\"Frequently Asked Questions\",\"subheading\":\"Any Questions? Find Here.\",\"description\":\"Don\\u2019t find your answer here? just send us a message for any query.\",\"button_text\":\"Contact with Us\",\"button_link\":\"contact\"}', NULL, '2023-08-29 22:32:17', '2023-08-29 22:36:53'),
(162, 'crystal_sky', NULL, 'faq.element', '{\"question\":\"Is opening an account is free?\",\"answer\":\"Yes, we don\'t take any fees for opening an account.\"}', NULL, '2023-08-29 22:32:17', '2023-08-29 22:32:17'),
(163, 'crystal_sky', NULL, 'faq.element', '{\"question\":\"Is it possible to send money from Viserbank to another bank?\",\"answer\":\"Yes, you can send money from Viserbank to another bank?\"}', NULL, '2023-08-29 22:32:17', '2023-08-29 22:32:17'),
(164, 'crystal_sky', NULL, 'faq.element', '{\"question\":\"How to open an account?\",\"answer\":\"Get the registration form by clicking on the Sing Up button on the top bar. Provide all information and click on the Sign Up button.\"}', NULL, '2023-08-29 22:32:17', '2023-08-29 22:32:17'),
(165, 'crystal_sky', NULL, 'faq.element', '{\"question\":\"Does Viserbank share our information for advertisement?\",\"answer\":\"No, we don\'t provide our account holder\'s information to any third-party organization.\"}', NULL, '2023-08-29 22:32:17', '2023-08-29 22:32:17'),
(166, 'crystal_sky', NULL, 'faq.element', '{\"question\":\"How to take a loan?\",\"answer\":\"We have several loan plans. Choose the best plan suitable for you and just click on the Apply Now button and put the amount.\"}', NULL, '2023-08-29 22:32:17', '2023-08-29 22:32:17'),
(167, 'crystal_sky', NULL, 'faq.element', '{\"question\":\"How to open a FDR\",\"answer\":\"e have several FDR plans. Choose the best plan suitable for you and just click on the Apply Now button and put the amount.\"}', NULL, '2023-08-29 22:32:17', '2023-08-29 22:32:17'),
(168, 'crystal_sky', NULL, 'partner_section.content', '{\"heading\":\"Our Trusted Alliance of Partners\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:39:21'),
(169, 'crystal_sky', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"64f71484e60301693914244.png\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:44:05'),
(170, 'crystal_sky', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"64f7148b9f1c11693914251.png\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:44:11'),
(171, 'crystal_sky', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"64f71492d86491693914258.png\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:44:18'),
(172, 'crystal_sky', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"64f71499c74931693914265.png\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:44:25'),
(173, 'crystal_sky', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"64f714a160c001693914273.png\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:44:33'),
(174, 'crystal_sky', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"64f714b43f3251693914292.png\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:44:52'),
(175, 'crystal_sky', NULL, 'partner_section.element', '{\"has_image\":\"1\",\"image\":\"64f714baa476d1693914298.png\"}', NULL, '2023-08-29 23:05:50', '2023-09-05 09:44:58'),
(176, 'crystal_sky', NULL, 'counter.content', '{\"has_image\":\"1\",\"image\":\"64ef4031c96091693401137.png\"}', NULL, '2023-08-29 23:12:17', '2023-08-29 23:12:18'),
(177, 'crystal_sky', NULL, 'counter.element', '{\"title\":\"Account Holders\",\"digit\":\"5\",\"symbol\":\"M\"}', NULL, '2023-08-29 23:12:45', '2023-09-01 19:58:09'),
(178, 'crystal_sky', NULL, 'counter.element', '{\"title\":\"Account Holders\",\"digit\":\"6\",\"symbol\":\"B\"}', NULL, '2023-08-29 23:12:57', '2023-09-01 19:58:14'),
(179, 'crystal_sky', NULL, 'counter.element', '{\"title\":\"Account Holders\",\"digit\":\"10\",\"symbol\":\"+\"}', NULL, '2023-08-29 23:13:05', '2023-08-29 23:13:05'),
(180, 'crystal_sky', NULL, 'counter.element', '{\"title\":\"Account Holders\",\"digit\":\"35\",\"symbol\":\"M\"}', NULL, '2023-08-29 23:13:13', '2023-08-29 23:13:13'),
(181, 'crystal_sky', NULL, 'fdr_plans.content', '{\"heading\":\"Fixed Deposit Scheme\",\"subheading\":\"Efforts are our rewards are yours!\"}', NULL, '2023-08-30 15:48:00', '2023-08-30 15:48:00'),
(182, 'crystal_sky', NULL, 'loan_plans.content', '{\"title\":\"Our Loan Schemes\",\"heading\":\"We Have The Best Loan Plans\"}', NULL, '2023-08-30 15:48:12', '2023-08-30 15:48:12'),
(183, 'crystal_sky', NULL, 'login_bg.content', '{\"has_image\":\"1\",\"heading\":\"Sign In\",\"subheading\":\"Welcome Back!\",\"image\":\"64f85aa4e4c9a1693997732.png\"}', NULL, '2023-08-30 16:31:48', '2023-09-06 08:55:33'),
(184, 'crystal_sky', NULL, 'signup_bg.content', '{\"has_image\":\"1\",\"heading\":\"Register\",\"subheading\":\"Create New Account\",\"image\":\"64f8623da5af81693999677.png\"}', NULL, '2023-08-30 17:26:21', '2023-09-06 09:27:58'),
(185, 'crystal_sky', NULL, 'banned.content', '{\"has_image\":\"1\",\"heading\":\"This Account is Banned\",\"image\":\"64f30cc83b70d1693650120.png\"}', NULL, '2023-09-01 20:20:57', '2023-09-06 10:31:00'),
(186, 'crystal_sky', NULL, 'registration_disabled.content', '{\"heading\":\"Registration Disabled\",\"subheading\":\"currently disabled your registration process. Please get in touch with your nearby Branch\",\"button_text\":\"Browse Home Page\",\"button_link\":\"\\/\"}', NULL, '2023-09-01 23:19:50', '2023-09-01 23:20:11'),
(187, 'crystal_sky', NULL, 'kyc_content.content', '{\"unverified_content\":\"Dear User, we need your KYC Data for some action. Don\'t hesitate to provide KYC Data, It\'s so much potential for us too. Don\'t worry,  it\'s very much secure in our system.\",\"pending_content\":\"Dear user, Your submitted KYC Data is currently pending now. Please take us some time to review your Data. Thank you so much for your cooperation.\"}', NULL, '2023-09-01 23:48:46', '2023-09-01 23:48:46'),
(188, 'crystal_sky', NULL, 'feature.content', '{\"heading\":\"Our Features\",\"subheading\":\"Explore Our Features\"}', NULL, '2023-09-05 08:55:15', '2023-09-05 08:57:33'),
(189, 'crystal_sky', NULL, 'breadcrumb.content', '{\"has_image\":\"1\",\"image\":\"64f83195a2f681693987221.png\"}', NULL, '2023-09-06 06:00:21', '2023-09-06 06:00:22'),
(190, 'indigo_fusion', NULL, 'breadcrumb.content', '{\"has_image\":\"1\",\"image\":\"65057a7a5aeaf1694857850.jpg\"}', NULL, '2023-09-16 13:50:50', '2023-09-16 13:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `code` int DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `image`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 101, 'Paypal', 'Paypal', '663a38d7b455d1715091671.png', 1, '{\"paypal_email\":{\"title\":\"PayPal Email\",\"global\":true,\"value\":\"sb-owud61543012@business.example.com\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:21:11'),
(2, 0, 102, 'Perfect Money', 'PerfectMoney', '663a3920e30a31715091744.png', 1, '{\"passphrase\":{\"title\":\"ALTERNATE PASSPHRASE\",\"global\":true,\"value\":\"hR26aw02Q1eEeUPSIfuwNypXX\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:22:24'),
(3, 0, 103, 'Stripe Hosted', 'Stripe', '663a39861cb9d1715091846.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:24:06'),
(4, 0, 104, 'Skrill', 'Skrill', '663a39494c4a91715091785.png', 1, '{\"pay_to_email\":{\"title\":\"Skrill Email\",\"global\":true,\"value\":\"merchant@skrill.com\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"---\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JOD\":\"JOD\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"KWD\":\"KWD\",\"MAD\":\"MAD\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"OMR\":\"OMR\",\"PLN\":\"PLN\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"SAR\":\"SAR\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TND\":\"TND\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\",\"COP\":\"COP\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:23:05'),
(5, 0, 105, 'PayTM', 'Paytm', '663a390f601191715091727.png', 1, '{\"MID\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"DIY12386817555501617\"},\"merchant_key\":{\"title\":\"Merchant Key\",\"global\":true,\"value\":\"bKMfNxPPf_QdZppa\"},\"WEBSITE\":{\"title\":\"Paytm Website\",\"global\":true,\"value\":\"DIYtestingweb\"},\"INDUSTRY_TYPE_ID\":{\"title\":\"Industry Type\",\"global\":true,\"value\":\"Retail\"},\"CHANNEL_ID\":{\"title\":\"CHANNEL ID\",\"global\":true,\"value\":\"WEB\"},\"transaction_url\":{\"title\":\"Transaction URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/oltp-web\\/processTransaction\"},\"transaction_status_url\":{\"title\":\"Transaction STATUS URL\",\"global\":true,\"value\":\"https:\\/\\/pguat.paytm.com\\/paytmchecksum\\/paytmCallback.jsp\"}}', '{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:22:07'),
(6, 0, 106, 'Payeer', 'Payeer', '663a38c9e2e931715091657.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"866989763\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"7575\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 07:14:22', '2024-05-07 02:20:57'),
(7, 0, 107, 'PayStack', 'Paystack', '663a38fc814e91715091708.png', 1, '{\"public_key\":{\"title\":\"Public key\",\"global\":true,\"value\":\"pk_test_cd330608eb47970889bca397ced55c1dd5ad3783\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"sk_test_8a0b1f199362d7acc9c390bff72c4e81f74e2ac3\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 07:14:22', '2024-05-07 02:21:48'),
(9, 0, 109, 'Flutterwave', 'Flutterwave', '663a36c2c34d61715091138.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"----------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------------------\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"------------------\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:12:18'),
(10, 0, 110, 'RazorPay', 'Razorpay', '663a393a527831715091770.png', 1, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"rzp_test_kiOtejPbRZU90E\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"osRDebzEqbsE1kbyQJ4y0re7\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:22:50'),
(11, 0, 111, 'Stripe Storefront', 'StripeJs', '663a3995417171715091861.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:24:21'),
(12, 0, 112, 'Instamojo', 'Instamojo', '663a384d54a111715091533.png', 1, '{\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_2241633c3bc44a3de84a3b33969\"},\"auth_token\":{\"title\":\"Auth Token\",\"global\":true,\"value\":\"test_279f083f7bebefd35217feef22d\"},\"salt\":{\"title\":\"Salt\",\"global\":true,\"value\":\"19d38908eeff4f58b2ddda2c6d86ca25\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:18:53'),
(13, 0, 501, 'Blockchain', 'Blockchain', '663a35efd0c311715090927.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"55529946-05ca-48ff-8710-f279d86b1cc5\"},\"xpub_code\":{\"title\":\"XPUB CODE\",\"global\":true,\"value\":\"xpub6CKQ3xxWyBoFAF83izZCSFUorptEU9AF8TezhtWeMU5oefjX3sFSBw62Lr9iHXPkXmDQJJiHZeTRtD9Vzt8grAYRhvbz4nEvBu3QKELVzFK\"}}', '{\"BTC\":\"BTC\"}', 1, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:08:47'),
(15, 0, 503, 'CoinPayments', 'Coinpayments', '663a36a8d8e1d1715091112.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"---------------------\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"---------------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"---------------------\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:11:52'),
(16, 0, 504, 'CoinPayments Fiat', 'CoinpaymentsFiat', '663a36b7b841a1715091127.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"6515561\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:12:07'),
(17, 0, 505, 'Coingate', 'Coingate', '663a368e753381715091086.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"6354mwVCEw5kHzRJ6thbGo-N\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:11:26'),
(18, 0, 506, 'Coinbase Commerce', 'CoinbaseCommerce', '663a367e46ae51715091070.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"c47cd7df-d8e8-424b-a20a\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"55871878-2c32-4f64-ab66\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 07:14:22', '2024-05-07 02:11:10'),
(24, 0, 113, 'Paypal Express', 'PaypalSdk', '663a38ed101a61715091693.png', 1, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"Ae0-tixtSV7DvLwIh3Bmu7JvHrjh5EfGdXr_cEklKAVjjezRZ747BxKILiBdzlKKyp-W8W_T7CKH1Ken\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"EOhbvHZgFNO21soQJT1L9Q00M3rK6PIEsdiTgXRBt2gtGtxwRer5JvKnVUGNU5oE63fFnjnYY7hq3HBA\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:21:33'),
(25, 0, 114, 'Stripe Checkout', 'StripeV3', '663a39afb519f1715091887.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51I6GGiCGv1sRiQlEi5v1or9eR0HVbuzdMd2rW4n3DxC8UKfz66R4X6n4yYkzvI2LeAIuRU9H99ZpY7XCNFC9xMs500vBjZGkKG\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51I6GGiCGv1sRiQlEOisPKrjBqQqqcFsw8mXNaZ2H2baN6R01NulFS7dKFji1NRRxuchoUTEDdB7ujKcyKYSVc0z500eth7otOM\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"whsec_lUmit1gtxwKTveLnSe88xCSDdnPOt8g5\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 07:14:22', '2024-05-07 02:24:47'),
(27, 0, 115, 'Mollie', 'Mollie', '663a387ec69371715091582.png', 1, '{\"mollie_email\":{\"title\":\"Mollie Email \",\"global\":true,\"value\":\"vi@gmail.com\"},\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"test_cucfwKTWfft9s337qsVfn5CC4vNkrn\"}}', '{\"AED\":\"AED\",\"AUD\":\"AUD\",\"BGN\":\"BGN\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"HRK\":\"HRK\",\"HUF\":\"HUF\",\"ILS\":\"ILS\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, '2019-09-14 07:14:22', '2024-05-07 02:19:42'),
(30, 0, 116, 'Cashmaal', 'Cashmaal', '663a361b16bd11715090971.png', 1, '{\"web_id\":{\"title\":\"Web Id\",\"global\":true,\"value\":\"3748\"},\"ipn_key\":{\"title\":\"IPN Key\",\"global\":true,\"value\":\"546254628759524554647987\"}}', '{\"PKR\":\"PKR\",\"USD\":\"USD\"}', 0, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.Cashmaal\"}}', NULL, NULL, '2024-05-07 02:09:31'),
(36, 0, 119, 'Mercado Pago', 'MercadoPago', '663a386c714a91715091564.png', 1, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"APP_USR-7924565816849832-082312-21941521997fab717db925cf1ea2c190-1071840315\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-05-07 02:19:24'),
(37, 0, 120, 'Authorize.net', 'Authorize', '663a35b9ca5991715090873.png', 1, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"59e4P9DBcZv\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"47x47TJyLw2E7DbR\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-05-07 02:07:53'),
(46, 0, 121, 'NMI', 'NMI', '663a3897754cf1715091607.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"2F822Rw39fx762MaV7Yy86jXGTC7sCDy\"}}', '{\"AED\":\"AED\",\"ARS\":\"ARS\",\"AUD\":\"AUD\",\"BOB\":\"BOB\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"RUB\":\"RUB\",\"SEC\":\"SEC\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"TWD\":\"TWD\",\"USD\":\"USD\",\"ZAR\":\"ZAR\"}', 0, NULL, NULL, NULL, '2024-05-07 02:20:07'),
(50, 0, 507, 'BTCPay', 'BTCPay', '663a35cd25a8d1715090893.png', 1, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"HsqFVTXSeUFJu7caoYZc3CTnP8g5LErVdHhEXPVTheHf\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"4436bd706f99efae69305e7c4eff4780de1335ce\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"https:\\/\\/testnet.demo.btcpayserver.org\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"SUCdqPn9CDkY7RmJHfpQVHP2Lf2\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2024-05-07 02:08:13'),
(51, 0, 508, 'Now payments hosted', 'NowPaymentsHosted', '663a38b8d57a81715091640.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"--------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------------\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2024-05-07 02:20:40'),
(52, 0, 509, 'Now payments checkout', 'NowPaymentsCheckout', '663a38a59d2541715091621.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"---------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"-----------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 1, '', NULL, NULL, '2024-05-07 02:20:21'),
(53, 0, 122, '2Checkout', 'TwoCheckout', '663a39b8e64b91715091896.png', 1, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"253248016872\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"eQM)ID@&vG84u!O*g[p+\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 0, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2024-05-07 02:24:56'),
(54, 0, 123, 'Checkout', 'Checkout', '663a3628733351715090984.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------\"},\"public_key\":{\"title\":\"PUBLIC KEY\",\"global\":true,\"value\":\"------\"},\"processing_channel_id\":{\"title\":\"PROCESSING CHANNEL\",\"global\":true,\"value\":\"------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-05-07 02:09:44'),
(56, 0, 510, 'Binance', 'Binance', '663a35db4fd621715090907.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"tsu3tjiq0oqfbtmlbevoeraxhfbp3brejnm9txhjxcp4to29ujvakvfl1ibsn3ja\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"jzngq4t04ltw8d4iqpi7admfl8tvnpehxnmi34id1zvfaenbwwvsvw7llw3zdko8\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"231129033\"}}', '{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}', 1, '{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}', NULL, NULL, '2024-05-07 02:08:27'),
(57, 0, 124, 'SslCommerz', 'SslCommerz', '663a397a70c571715091834.png', 1, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"store_password\":{\"title\":\"Store Password\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}', 0, NULL, NULL, NULL, '2024-05-07 02:23:54'),
(58, 0, 125, 'Aamarpay', 'Aamarpay', '663a34d5d1dfc1715090645.png', 1, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"signature_key\":{\"title\":\"Signature Key\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2024-08-14 04:44:22');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_currencies`
--

CREATE TABLE `gateway_currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int DEFAULT NULL,
  `gateway_alias` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cur_text` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_template` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `base_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'email configuration',
  `sms_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `firebase_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `global_shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kv` tinyint(1) NOT NULL DEFAULT '0',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'mobile verication, 0 - dont check, 1 - check',
  `sn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint(1) NOT NULL DEFAULT '0',
  `force_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `in_app_payment` tinyint(1) NOT NULL DEFAULT '1',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `secure_password` tinyint(1) NOT NULL DEFAULT '0',
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `multi_language` tinyint(1) NOT NULL DEFAULT '1',
  `registration` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Off	, 1: On',
  `active_template` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialite_credentials` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `available_version` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` datetime DEFAULT NULL,
  `detect_activity` tinyint UNSIGNED DEFAULT '0',
  `system_customized` tinyint(1) NOT NULL DEFAULT '0',
  `paginate_number` int UNSIGNED NOT NULL DEFAULT '0',
  `currency_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 => Both, 2 => Text Only, 3 = Symbol Only',
  `modules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `account_no_length` int DEFAULT NULL,
  `account_no_prefix` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_time` int NOT NULL DEFAULT '0',
  `daily_transfer_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `monthly_transfer_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `minimum_transfer_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_transfer_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_transfer_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `referral_commission_count` int UNSIGNED NOT NULL DEFAULT '0',
  `idle_time_threshold` int DEFAULT '0' COMMENT 'value in seconds',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_name`, `cur_text`, `cur_sym`, `email_from`, `email_from_name`, `email_template`, `sms_template`, `sms_from`, `push_title`, `push_template`, `base_color`, `secondary_color`, `mail_config`, `sms_config`, `firebase_config`, `global_shortcodes`, `kv`, `ev`, `en`, `sv`, `sn`, `pn`, `force_ssl`, `in_app_payment`, `maintenance_mode`, `secure_password`, `agree`, `multi_language`, `registration`, `active_template`, `socialite_credentials`, `available_version`, `last_cron`, `detect_activity`, `system_customized`, `paginate_number`, `currency_format`, `modules`, `account_no_length`, `account_no_prefix`, `otp_time`, `daily_transfer_limit`, `monthly_transfer_limit`, `minimum_transfer_limit`, `fixed_transfer_charge`, `percent_transfer_charge`, `referral_commission_count`, `idle_time_threshold`, `created_at`, `updated_at`) VALUES
(1, 'ViserBank', 'USD', '$', 'no-reply@viserlab.com', NULL, '<html>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n<title>\n</title>\n<style type=\"text/css\">\n	.ReadMsgBody {\n		width: 100%;\n		background-color: #ffffff;\n	}\n	.ExternalClass {\n		width: 100%;\n		background-color: #ffffff;\n	}\n	.ExternalClass,\n	.ExternalClass p,\n	.ExternalClass span,\n	.ExternalClass font,\n	.ExternalClass td,\n	.ExternalClass div {\n		line-height: 100%;\n	}\n	html {\n		width: 100%;\n	}\n	body {\n		-webkit-text-size-adjust: none;\n		-ms-text-size-adjust: none;\n		margin: 0;\n		padding: 0;\n	}\n	table {\n		border-spacing: 0;\n		table-layout: fixed;\n		margin: 0 auto;\n		border-collapse: collapse;\n	}\n	table table table {\n		table-layout: auto;\n	}\n	.yshortcuts a {\n		border-bottom: none !important;\n	}\n	img:hover {\n		opacity: 0.9 !important;\n	}\n	a {\n		color: #0087ff;\n		text-decoration: none;\n	}\n	.textbutton a {\n		font-family: \"open sans\", arial, sans-serif !important;\n	}\n	.btn-link a {\n		color: #ffffff !important;\n	}\n	@media only screen and (max-width: 480px) {\n		body {\n			width: auto !important;\n		}\n		*[class=\"table-inner\"] {\n			width: 90% !important;\n			text-align: center !important;\n		}\n		*[class=\"table-full\"] {\n			width: 100% !important;\n			text-align: center !important;\n		} /* image */\n		img[class=\"img1\"] {\n			width: 100% !important;\n			height: auto !important;\n		}\n	}\n\n</style>\n<table bgcolor=\"#030442\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n	<tbody>\n		<tr>\n			<td height=\"50\">\n			</td>\n		</tr>\n		<tr>\n			<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n				<table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n					<tbody>\n						<tr>\n							<td align=\"center\" width=\"600\">\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n									<tbody>\n										<tr>\n											<td bgcolor=\"#0087ff\" style=\"border-top-left-radius:6px; border-top-right-radius:6px;text-align:center;vertical-align:top;font-size:0;\" align=\"center\">\n												<table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n													<tbody>\n														<tr>\n															<td height=\"20\">\n															</td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#FFFFFF; font-size:16px; font-weight: bold;\">\n															This is a System Generated Email</td>\n														</tr>\n														<tr>\n															<td height=\"20\">\n															</td>\n														</tr>\n													</tbody>\n												</table>\n											</td>\n										</tr>\n									</tbody>\n								</table>\n								<table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n									<tbody>\n										<tr>\n											<td bgcolor=\"#FFFFFF\" align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n													<tbody>\n														<tr>\n															<td height=\"35\">\n															</td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"vertical-align:top;font-size:0;\">\n																<a href=\"#\">\n																	<img style=\"display:block; line-height:0px; font-size:0px; border:0px; width: 240px;\" width=\"240px\" src=\"https://viserlab.com/assets/images/logoIcon/logo-dark.png\" alt=\"img\">\n																</a>\n															</td>\n														</tr>\n														<tr>\n															<td height=\"40\"></td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"font-family: Open Sans, Arial, sans-serif; font-size: 22px;color:#414a51;font-weight: bold;\">\n															Hello {{fullname}} ({{username}}) </td>\n														</tr>\n														<tr>\n															<td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\n																<table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n																	<tbody>\n																		<tr>\n																			<td height=\"20\" style=\" border-bottom:3px solid #0087ff;\">\n																			</td>\n																		</tr>\n																	</tbody>\n																</table>\n															</td>\n														</tr>\n														<tr>\n															<td height=\"30\"></td>\n														</tr>\n														<tr>\n															<td align=\"left\" style=\"font-family: Open sans, Arial, sans-serif; color:#7f8c8d; font-size:16px; line-height: 28px;\">\n															{{message}}</td>\n														</tr>\n														<tr>\n															<td height=\"60\"></td>\n														</tr>\n													</tbody>\n												</table>\n											</td>\n										</tr>\n										<tr>\n											<td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"border-bottom-left-radius:6px;border-bottom-right-radius:6px;\">\n												<table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n													<tbody>\n														<tr>\n															<td height=\"10\"></td>\n														</tr>\n														<tr>\n															<td class=\"preference-link\" align=\"center\" style=\"font-family: Open sans, Arial, sans-serif; color:#95a5a6; font-size:14px;\">\n																© 2023 <a href=\"#\">{{site_name}}</a> &nbsp;. All Rights Reserved. </td>\n														</tr>\n														<tr>\n															<td height=\"10\"></td>\n														</tr>\n													</tbody>\n												</table>\n											</td>\n										</tr>\n									</tbody>\n								</table>\n							</td>\n						</tr>\n					</tbody>\n				</table>\n			</td>\n		</tr>\n		<tr>\n			<td height=\"60\"></td>\n		</tr>\n	</tbody>\n</table>\n</html>\n', 'hi {{fullname}} ({{username}}), {{message}}', 'ViserAdmin', NULL, '{\"serverKey\":\"AAAAoegzmmY:APA91bFiy5RYOIUfKyzNpQbGmER-PqZHdva_OC3lUFP2QOTaDiM7TExDcs4DU0gWZY4q9hJar4g8O6M7HzkEVWrWKjx_VOqdx2svDw2Tp8xZOzZHQJf81KRG1IBWZUMBRZOL1UEWZcYB\",\"apiKey\":\"AIzaSyCVRaQKr_OHqAEq9xKnkNh-wXieFAjr-vo\",\"authDomain\":\"viserbank-8844b.firebaseapp.com\",\"projectId\":\"viserbank-8844b\",\"storageBucket\":\"viserbank-8844b.appspot.com\",\"messagingSenderId\":\"695385430630\",\"appId\":\"1:695385430630:web:162b7db2fbd9074f752ba7\",\"measurementId\":\"G-C45YS0HNCE\"}', '00a6f7', '14233c', '{\"name\":\"php\"}', '{\"name\":\"nexmo\",\"clickatell\":{\"api_key\":\"----------------\"},\"infobip\":{\"username\":\"------------8888888\",\"password\":\"-----------------\"},\"message_bird\":{\"api_key\":\"-------------------\"},\"nexmo\":{\"api_key\":\"----------------------\",\"api_secret\":\"----------------------\"},\"sms_broadcast\":{\"username\":\"----------------------\",\"password\":\"-----------------------------\"},\"twilio\":{\"account_sid\":\"-----------------------\",\"auth_token\":\"---------------------------\",\"from\":\"----------------------\"},\"text_magic\":{\"username\":\"-----------------------\",\"apiv2_key\":\"-------------------------------\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\"],\"value\":[\"test_api 555\"]},\"body\":{\"name\":[\"from_number\"],\"value\":[\"5657545757\"]}}}', NULL, '{\n    \"site_name\":\"Name of your site\",\n    \"site_currency\":\"Currency of your site\",\n    \"currency_symbol\":\"Symbol of currency\"\n}', 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 'crystal_sky', '{\"google\":{\"client_id\":\"sdfsafsadf\",\"client_secret\":\"sadfasdfasdfs\",\"status\":1},\"facebook\":{\"client_id\":\"8941354654163\",\"client_secret\":\"SDFASDF8413512\",\"status\":1},\"linkedin\":{\"client_id\":\"657846531351\",\"client_secret\":\"5746152\",\"status\":1}}', '3.0', '2024-09-02 10:22:01', 0, 0, 15, 3, '{\"deposit\":0,\"withdraw\":0,\"dps\":0,\"fdr\":0,\"loan\":0,\"own_bank\":0,\"other_bank\":0,\"otp_email\":0,\"otp_sms\":0,\"branch_create_user\":0,\"wire_transfer\":0,\"referral_system\":0,\"airtime\":0}', 15, 'VB', 120, '15000.00000000', '60000.00000000', '2.00000000', '2.00000000', '3.00', 5, 300, NULL, '2024-09-02 04:24:13');

-- --------------------------------------------------------

--
-- Table structure for table `installments`
--

CREATE TABLE `installments` (
  `id` bigint UNSIGNED NOT NULL,
  `installmentable_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `installmentable_id` int UNSIGNED NOT NULL,
  `delay_charge` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `installment_date` date DEFAULT NULL,
  `given_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not default language, 1: default language',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `image`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, '66c1c9f61bc051723976182.png', '2024-08-18 04:12:43', '2024-08-18 04:16:22');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` bigint UNSIGNED NOT NULL,
  `loan_number` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `plan_id` int UNSIGNED NOT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `per_installment` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `installment_interval` int NOT NULL DEFAULT '0' COMMENT 'Days',
  `delay_value` int NOT NULL DEFAULT '1',
  `charge_per_installment` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `delay_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `given_installment` int NOT NULL DEFAULT '0',
  `total_installment` int NOT NULL DEFAULT '0',
  `application_form` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `admin_feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = Pending, 1 = Running, 2 = Paid, 3 = Rejected',
  `due_notification_sent` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_plans`
--

CREATE TABLE `loan_plans` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int NOT NULL DEFAULT '0',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `minimum_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `maximum_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `per_installment` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '%',
  `installment_interval` int NOT NULL DEFAULT '0' COMMENT 'In Day',
  `total_installment` int NOT NULL DEFAULT '0',
  `instruction` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `delay_value` int UNSIGNED NOT NULL DEFAULT '1',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `sender` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notification_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sms_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `push_body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `shortcodes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `email_sent_from_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent_from_address` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_sent_from` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `sms_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', '{{site_name}} - Balance Added', '<div>We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.</div><div><br></div><div>Here are the details of the transaction:</div><div><br></div><div><b>Transaction Number: </b>{{trx}}</div><div><b>Current Balance:</b> {{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>If you have any questions or require further assistance, please don\'t hesitate to contact us. We\'re here to assist you.</div>', 'We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.', '{{amount}} {{site_currency}} has been successfully added to your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 0, '{{site_name}} Finance', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-05-08 01:17:24'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', '{{site_name}} - Balance Subtracted', '<div>We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.</div><div><br></div><div>Below are the details of the transaction:</div><div><br></div><div><b>Transaction Number:</b> {{trx}}</div><div><b>Current Balance: </b>{{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>Should you require any further clarification or assistance, please do not hesitate to reach out to us. We are here to assist you in any way we can.</div><div><br></div><div>Thank you for your continued trust in {{site_name}}.</div>', 'We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.', '{{amount}} {{site_currency}} debited from your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '{{site_name}} Finance', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-05-08 01:17:48'),
(3, 'DEPOSIT_COMPLETE', 'Deposit - Automated - Successful', 'Deposit Completed Successfully', '{{site_name}} - Deposit successful', '<div>We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.</div><div><br></div><div>Below, you\'ll find the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>If you have any questions or need further assistance, feel free to reach out to our support team. We\'re here to assist you in any way we can.</div>', 'We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.', 'Deposit Completed Successfully', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '{{site_name}} Billing', NULL, NULL, 1, '2021-11-03 06:00:00', '2024-05-08 01:20:34'),
(4, 'DEPOSIT_APPROVE', 'Deposit - Manual - Approved', 'Deposit Request Approved', '{{site_name}} - Deposit Request Approved', '<div>We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.</div><div><br></div><div>Here are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received: </b>{{method_amount}} {{method_currency}}</div><div><b>Paid via: </b>{{method_name}}</div><div><b>Transaction Number: </b>{{trx}}</div><div><br></div><div>Your current balance now stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to contact our support team. We\'re here to help.</div>', 'We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.', 'Deposit of {{amount}} {{site_currency}} via {{method_name}} has been approved.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, 1, '{{site_name}} Billing', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-05-08 01:19:49'),
(5, 'DEPOSIT_REJECT', 'Deposit - Manual - Rejected', 'Deposit Request Rejected', '{{site_name}} - Deposit Request Rejected', '<div>We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of the rejected deposit:</div><div><br></div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Charge:</b> {{charge}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>If you have any questions or need further clarification, please don\'t hesitate to contact us. We\'re here to assist you.</div><div><br></div><div>Rejection Reason:</div><div>{{rejection_message}}</div><div><br></div><div>Thank you for your understanding.</div>', 'We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'Your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\"}', 1, 1, '{{site_name}} Billing', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-05-08 01:20:13'),
(6, 'DEPOSIT_REQUEST', 'Deposit - Manual - Requested', 'Deposit Request Submitted Successfully', NULL, '<div>We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Below are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Payable:</b> {{method_amount}} {{method_currency}}</div><div><b>Pay via: </b>{{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to reach out to our support team. We\'re here to assist you.</div>', 'We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'Your deposit request of {{amount}} {{site_currency}} via {{method_name}} submitted successfully.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\"}', 1, 1, '{{site_name}} Billing', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-04-24 21:27:42'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', '{{site_name}} Password Reset Code', '<div>We\'ve received a request to reset the password for your account on <b>{{time}}</b>. The request originated from\r\n            the following IP address: <b>{{ip}}</b>, using <b>{{browser}}</b> on <b>{{operating_system}}</b>.\r\n    </div><br>\r\n    <div><span>To proceed with the password reset, please use the following account recovery code</span>: <span><b><font size=\"6\">{{code}}</font></b></span></div><br>\r\n    <div><span>If you did not initiate this password reset request, please disregard this message. Your account security\r\n            remains our top priority, and we advise you to take appropriate action if you suspect any unauthorized\r\n            access to your account.</span></div>', 'To proceed with the password reset, please use the following account recovery code: {{code}}', 'To proceed with the password reset, please use the following account recovery code: {{code}}', '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, 0, '{{site_name}} Authentication Center', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-05-08 01:24:57'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'Password Reset Successful', NULL, '<div><div><span>We are writing to inform you that the password reset for your account was successful. This action was completed at {{time}} from the following browser</span>: <span>{{browser}}</span><span>on {{operating_system}}, with the IP address</span>: <span>{{ip}}</span>.</div><br><div><span>Your account security is our utmost priority, and we are committed to ensuring the safety of your information. If you did not initiate this password reset or notice any suspicious activity on your account, please contact our support team immediately for further assistance.</span></div></div>', 'We are writing to inform you that the password reset for your account was successful.', 'We are writing to inform you that the password reset for your account was successful.', '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, 1, '{{site_name}} Authentication Center', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-04-24 21:27:24'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{{site_name}} - Support Ticket Replied', '<div>\r\n    <div><span>Thank you for reaching out to us regarding your support ticket with the subject</span>:\r\n        <span>\"{{ticket_subject}}\"&nbsp;</span><span>and ticket ID</span>: {{ticket_id}}.</div><br>\r\n    <div><span>We have carefully reviewed your inquiry, and we are pleased to provide you with the following\r\n            response</span><span>:</span></div><br>\r\n    <div>{{reply}}</div><br>\r\n    <div><span>If you have any further questions or need additional assistance, please feel free to reply by clicking on\r\n            the following link</span>: <a href=\"{{link}}\" title=\"\" target=\"_blank\">{{link}}</a><span>. This link will take you to\r\n            the ticket thread where you can provide further information or ask for clarification.</span></div><br>\r\n    <div><span>Thank you for your patience and cooperation as we worked to address your concerns.</span></div>\r\n</div>', 'Thank you for reaching out to us regarding your support ticket with the subject: \"{{ticket_subject}}\" and ticket ID: {{ticket_id}}. We have carefully reviewed your inquiry. To check the response, please go to the following link: {{link}}', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, 1, '{{site_name}} Support Team', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-05-08 01:26:06'),
(10, 'EVER_CODE', 'Verification - Email', 'Email Verification Code', NULL, '<div>\r\n    <div><span>Thank you for taking the time to verify your email address with us. Your email verification code\r\n            is</span>: <b><font size=\"6\">{{code}}</font></b></div><br>\r\n    <div><span>Please enter this code in the designated field on our platform to complete the verification\r\n            process.</span></div><br>\r\n    <div><span>If you did not request this verification code, please disregard this email. Your account security is our\r\n            top priority, and we advise you to take appropriate measures if you suspect any unauthorized access.</span>\r\n    </div><br>\r\n    <div><span>If you have any questions or encounter any issues during the verification process, please don\'t hesitate\r\n            to contact our support team for assistance.</span></div><br>\r\n    <div><span>Thank you for choosing us.</span></div>\r\n</div>', '---', '---', '{\"code\":\"Email verification code\"}', 1, 0, '{{site_name}} Verification Center', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-04-24 21:27:12'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', NULL, '---', 'Your mobile verification code is {{code}}. Please enter this code in the appropriate field to verify your mobile number. If you did not request this code, please ignore this message.', '---', '{\"code\":\"SMS Verification Code\"}', 0, 1, '{{site_name}} Verification Center', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-04-24 21:27:03'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdrawal Confirmation: Your Request Processed Successfully', '{{site_name}} - Withdrawal Request Approved', '<div>We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.</div><div><br></div><div>Below are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>You will receive:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Details of Processed Payment:</b></div><div>{{admin_details}}</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.', 'Withdrawal Confirmation: Your Request Processed Successfully', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\"}', 1, 1, '{{site_name}} Finance', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-05-08 01:26:37'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdrawal Request Rejected', '{{site_name}} - Withdrawal Request Rejected', '<div>We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Refund Details:</b></div><div>{{amount}} {{site_currency}} has been refunded to your account, and your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><hr><div><br></div><div><b>Reason for Rejection:</b></div><div>{{admin_details}}</div><div><br></div><div>If you have any questions or concerns regarding this rejection or need further assistance, please do not hesitate to contact our support team. We apologize for any inconvenience this may have caused.</div>', 'We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}', 1, 1, '{{site_name}} Finance', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-06-05 22:57:32'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdrawal Request Confirmation', '{{site_name}} - Requested for withdrawal', '<div>We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\"}', 1, 1, '{{site_name}} Finance', NULL, NULL, 0, '2021-11-03 06:00:00', '2024-06-05 22:57:16'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', '{{subject}}', '{{message}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, 1, NULL, NULL, NULL, 0, '2019-09-14 07:14:22', '2024-05-08 01:18:21'),
(16, 'KYC_APPROVE', 'KYC Approved', 'KYC Details has been approved', '{{site_name}} - KYC Approved', '<div><div><span>We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.</span></div><br><div><span>Your commitment to completing the KYC process promptly is greatly appreciated, as it helps us ensure the security and integrity of our platform for all users.</span></div><br><div><span>With your KYC verification now complete, you can proceed with confidence to carry out any payout transactions you require. Should you encounter any issues or have any questions along the way, please don\'t hesitate to reach out to our support team. We\'re here to assist you every step of the way.</span></div><br><div><span>Thank you once again for choosing {{site_name}} and for your cooperation in this matter.</span></div></div>', 'We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.', 'Your  Know Your Customer (KYC) information has been approved successfully', '[]', 1, 1, '{{site_name}} Verification Center', NULL, NULL, 0, NULL, '2024-05-08 01:23:57'),
(17, 'KYC_REJECT', 'KYC Rejected', 'KYC has been rejected', '{{site_name}} - KYC Rejected', '<div><div><span>We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time.</span></div><br><div><span>We understand that this news may be disappointing, and we want to assure you that we take these matters seriously to maintain the security and integrity of our platform.</span></div><br><div><span>Reasons for rejection may include discrepancies or incomplete information in the documentation provided. If you believe there has been a misunderstanding or if you would like further clarification on why your KYC was rejected, please don\'t hesitate to contact our support team.</span></div><br><div><span>We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.</span></div><br><div><span>We apologize for any inconvenience this may cause and appreciate your understanding and cooperation in this matter.</span></div><br><div>Rejection Reason:</div><div>{{reason}}</div><div><br></div><div><span>Thank you for your continued support and patience.</span></div></div>', 'We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time. We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.', 'Your  Know Your Customer (KYC) information has been rejected', '{\"reason\":\"Rejection Reason\"}', 1, 1, '{{site_name}} Verification Center', NULL, NULL, 0, NULL, '2024-05-08 01:24:13'),
(18, 'FDR_OTP', 'OTP for FDR Apply', 'OTP for FDR Apply', NULL, '<div>Your {{site_name}} OTP is {{otp}}</div>', 'Your{{site_name}} OTP is {{otp}}', NULL, '{\r\n    \"site_name\": \"Site Name\",\r\n    \"otp\": \"One Time Password\"\r\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2022-08-03 11:27:02'),
(19, 'DPS_OTP', 'OTP for DPS Apply', 'OTP for DPS Apply', NULL, '<div>Your {{site_name}} OTP is {{otp}}</div>', 'Your {{site_name}} OTP is {{otp}}', NULL, '{\r\n    \"site_name\": \"Site Name\",\r\n    \"otp\": \"One Time Password\"\r\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2021-07-10 09:21:19'),
(20, 'OWN_BANK_TRANSFER_OTP', 'OTP for Own Bank Transfer', 'OTP for Own Bank Transfer', NULL, '<div>Your {{site_name}} OTP is {{otp}}</div>', 'Your{{site_name}} OTP is {{otp}}', NULL, '{\r\n    \"site_name\": \"Site Name\",\r\n    \"otp\": \"One Time Password\"\r\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2022-08-07 18:04:22'),
(21, 'OTHER_BANK_TRANSFER_OTP', 'OTP for Other Bank Transfer Request', 'OTP for Other Bank Transfer Request', NULL, '<div>Your {{site_name}} OTP is {{otp}}</div>', 'Your {{site_name}} OTP is {{otp}}', NULL, '{\r\n    \"site_name\": \"Site Name\",\r\n    \"otp\": \"One Time Password\"\r\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2021-07-10 09:21:19'),
(22, 'WIRE_TRANSFER_OTP', 'OTP for Wire Transfer Request', 'OTP for Wire Transfer Request', NULL, '<div>Your {{site_name}} OTP is {{otp}}</div>', 'Your {{site_name}} OTP is {{otp}}', NULL, '{\r\n    \"site_name\": \"Site Name\",\r\n    \"otp\": \"One Time Password\"\r\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2021-07-10 09:21:19'),
(23, 'FDR_OPENED', 'FDR Opened successfully', 'FDR Opened successfully', NULL, '<div>Your request for opening an FDR is approved successfully.</div>\r\n<div>FDR No. :&nbsp; &nbsp;{{fdr_number}}<br>Plan : {{plan_name}} </div>\r\n<div>Amount :&nbsp;{{amount}}</div>\r\n<div>Locked till :&nbsp;{{locked_date}}&nbsp;</div>\r\n<div>Interest Rate :&nbsp;{{interest_rate}}</div>\r\n<div>Get Interest Every :&nbsp;{{installment_interval}} days</div>\r\n<div><br></div>\r\n<div>You will get your first profit on&nbsp;{{next_installment_date}}</div>\r\n<div><br></div>', 'Your FDR request of {{amount}} is approved successfully. FDR No.: {{fdr_number}}\r\nYou cant withdraw the amount till {{locked_date}}', NULL, '{\n     \"plan_name\": \"Plan name\",\n     \"fdr_number\": \"FDR Number\",\n     \"amount\": \"Deposited Amount\",\n     \"locked_date\": \"User can not withdraw the amount till that date\",\n     \"per_installment\": \"The amount user will get in per installment\",\n     \"interest_rate\": \"Interest rate\",\n     \"installment_interval\": \"How many days the user will receive an installment\",\n     \"next_installment_date\": \"The date of next installment\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2022-11-24 00:48:06'),
(24, 'FDR_CLOSED', 'FDR Closed', 'FDR Closed', NULL, 'Your &nbsp; <span style=\"color: rgb(33, 37, 41);\">&nbsp;{{plan_name}}&nbsp;</span>FDR Plan close request was approved successfully. The main amount {{amount}} {{site_currency}} has been credited to your account.', 'Your  {{plan_name}} FDR Plan closed successfully. The main amount {{amount}} {{site_currency}} has been credited to your account.', NULL, '{\n     \"fdr_number\": \"FDR number\",\n     \"amount\": \"Deposited Amount\",\n     \"profit\": \"Sum of installment received by user\",\n     \"per_installment\": \"Profit amount per interval\",\n     \"currency\": \"Site currency\",\n     \"plan_name\": \"Plan name\",\n     \"post_balance\": \"User\'s balance after this operation\"\n\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2022-11-24 00:47:32'),
(25, 'DPS_OPENED', 'DPS Opened', 'DPS Opened successfully', NULL, '<div> Your application to open a DPS approved successfully<br>{{plan_name}} </div>', 'Your {{plan_name}}', NULL, '{\n     \"plan_name\": \"Plan Name\",\n     \"dps_number\": \"DPS Number\",\n     \"per_installment\": \"Installment amount\",\n     \"interest_rate\": \"Users profit rate on the total deposited amount\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments given by the user\",\n     \"total_installment\": \"How many installments for this plan\",\n     \"next_installment_date\": \"Next installment date\",\n     \"total_deposited\": \"The summation of installments\",\n     \"withdrawable_amount\": \"The amount user can withdraw after mature\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2021-07-27 10:49:00'),
(26, 'DPS_MATURED', 'DPS Matured', 'Dps matured', NULL, '<div> Your DPS is matured now<br>{{plan_name}} </div>', 'Your {{plan_name}}', NULL, '{\n     \"plan_name\": \"Plan Name\",\n     \"dps_number\": \"DPS Number\",\n     \"per_installment\": \"Installment amount\",\n     \"interest_rate\": \"Users profit rate on the total deposited amount\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments given by the user\",\n     \"total_installment\": \"How many installments for this plan\",\n     \"total_deposited\": \"The summation of installments\",\n     \"withdrawable_amount\": \"The amount user can withdraw after mature\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2021-07-27 10:50:55'),
(27, 'DPS_CLOSED', 'DPS CLosed', 'DPS closed successfully', NULL, '<div> Your {{plan_name}} </div>', 'Your {{plan_name}}', NULL, '{\n     \"plan_name\": \"Plan Name\",\n     \"dps_number\": \"DPS Number\",\n     \"per_installment\": \"Installment amount\",\n     \"interest_rate\": \"Users profit rate on the total deposited amount\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments are given by the user\",\n     \"total_installment\": \"How many installments for this plan\",\n     \"next_installment_date\": \"Next installment date\",\n     \"total_deposited\": \"The summation of installments\",\n     \"withdrawn_amount\": \"The amount user has withdrawn\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2021-07-10 09:21:19'),
(28, 'DPS_INSTALLMENT_DUE', 'DPS instalment Due', 'DPS instalment Due', NULL, 'Please recharge your balance for the DPS instalment&nbsp;<br><br><br>', 'Please recharge your balance for the DPS instalment', NULL, '{\n     \"dps_number\": \"DPS Number\",\n     \"per_installment\": \"Installment amount\",\n     \"interest_rate\": \"Users profit rate on the total deposited amount\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments are given by the user\",\n     \"total_installment\": \"How many installments for this plan\",\n     \"installment_date\": \"Installment date\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2022-08-26 19:26:47'),
(29, 'LOAN_APPROVE', 'Loan Approved Successfully', 'Loan Approved Successfully', NULL, '<div>Your request for a loan is approved successfully<br><br>&nbsp;Your {{plan_name}} </div>', 'Your {{plan_name}}', NULL, '{\n     \"plan_name\": \"Plan name\",\n     \"loan_number\": \"Loan number\",\n     \"amount\": \"Loan amount\",\n     \"per_installment\": \"Installment amount\",\n     \"payable_amount\": \"Payable amount for user\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments are given by the user\",\n     \"total_installment\": \"How many installments for this plan\",\n     \"next_installment_date\": \"Next installment date\"\n}', 1, 1, NULL, NULL, NULL, 1, NULL, NULL),
(30, 'LOAN_REJECT', 'Loan Request Rejected', 'Loan Request Rejected', NULL, '<div> Your application for a loan is rejected by the authority <br>{{plan_name}} </div>', 'Your {{plan_name}}', NULL, '{\n     \"plan_name\": \"Plan name\",\n     \"loan_number\": \"Loan number\",\n     \"amount\": \"Loan amount\",\n     \"per_installment\": \"Installment amount\",\n     \"payable_amount\": \"Payable amount for user\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments are given by the user\",\n     \"total_installment\": \"How many installments for this plan\",\n     \"reason_of_rejection\": \"Reason of rejection\"\n}', 1, 1, NULL, NULL, NULL, 1, NULL, NULL),
(31, 'LOAN_INSTALLMENT_DUE', 'Loan Installment Due', 'Loan installment due', NULL, '<div>Please recharge your balance for installment&nbsp;<br><br>{{plan_name}} </div>', 'Your {{plan_name}}', NULL, '{\n     \"loan_number\": \"Loan number\",\n     \"amount\": \"Loan amount\",\n     \"per_installment\": \"Installment amount\",\n     \"payable_amount\": \"Payable amount for user\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments are given by the user\",\n     \"total_installment\": \"How many installments for this plan\",\n     \"installment_date\": \"Installment date\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2022-08-22 10:12:54'),
(32, 'LOAN_PAID', 'Loan Paid', 'Loan completed', NULL, '<div> You paid all installment<br>{{plan_name}} </div>', 'Your {{plan_name}}', NULL, '{\n     \"plan_name\": \"Plan name\",\n     \"loan_number\": \"Loan number\",\n     \"amount\": \"Loan amount\",\n     \"per_installment\": \"Installment amount\",\n     \"payable_amount\": \"Payable amount for user\",\n     \"installment_interval\": \"How many days in an installment\",\n     \"delay_value\": \"How many days of delay the charge will be applied\",\n     \"charge_per_installment\": \"Installment delay charge for each day\",\n     \"delay_charge\": \"Total installment delay charge\",\n     \"given_installment\": \"How many installments are given by the user\",\n     \"total_installment\": \"How many installments for this plan\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2021-07-27 10:46:44'),
(33, 'OTHER_BANK_TRANSFER_REQUEST_SEND', 'Other Bank Money Transfer Request Sent', 'Money Transfer Request Sent Successfully', NULL, '<font color=\"#212529\"><span style=\"font-size: 12px; white-space: nowrap;\"><b>{{sending_amount}}&nbsp;{{site_currency}}</b></span></font>&nbsp;transfer request submitted&nbsp;successfully', '{{sending_amount}} {{site_currency}} sent to {recipient_account_number} successfully', NULL, '{\n     \"sender_account_number\": \"Sender account number\",\n     \"sender_account_name\": \"Sender account name\",\n     \"recipient_account_number\": \"Recipient account number\",\n     \"recipient_account_name\": \"Recipient account name\",\n     \"sending_amount\": \"The amount to be transferred\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"The amount including charge\",\n     \"bank_name\": \"Name of the bank of recipient\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 03:21:19', '2022-11-13 22:56:30'),
(34, 'OTHER_BANK_TRANSFER_COMPLETE', 'Other Bank Money Transfer Completed', 'Money Transfer Completed Successfully', NULL, '<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap;\">{{sending_amount}}&nbsp;</span><font color=\"#212529\"><span style=\"font-size: 12px; text-wrap: nowrap;\"><b>{{site_currency}}</b></span></font>&nbsp;sent to&nbsp;<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap;\">{{recipient_account_number}}</span>&nbsp;successfully', '{{sending_amount}} {{site_currency}} sent to {{recipient_account_number}} successfully', '{{sending_amount}} {{site_currency}} sent to {{recipient_account_number}} successfully', '{\n     \"sender_account_number\": \"Sender account number\",\n     \"sender_account_name\": \"Sender account name\",\n     \"recipient_account_number\": \"Recipient account number\",\n     \"recipient_account_name\": \"Recipient account name\",\n     \"sending_amount\": \"The amount to be transferred\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"The amount including charge\",\n     \"bank_name\": \"Name of the bank of recipient\"\n}', 1, 1, NULL, NULL, NULL, 0, '2021-07-10 03:21:19', '2024-06-08 22:22:27'),
(35, 'OTHER_BANK_TRANSFER_REJECT', 'Other Bank Money Transfer Rejected', 'Money Transfer Rejected', NULL, '<font color=\"#212529\"><span style=\"font-size: 12px; white-space: nowrap;\"><b>{{sending_amount}}&nbsp;{{site_currency}}</b></span></font>&nbsp;transfer request is rejected for&nbsp;{{reject_reason}} &nbsp;and the amount has refunded to your account', '{{amount}}{{currency}} sent to {{recipient_account_number}} successfully', NULL, '{\n     \"sender_account_number\": \"Sender account number\",\n     \"sender_account_name\": \"Sender account name\",\n     \"recipient_account_number\": \"Recipient account number\",\n     \"recipient_account_name\": \"Recipient account name\",\n     \"sending_amount\": \"The amount to be transferred\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"The amount including charge\",\n     \"bank_name\": \"Name of the bank of recipient\",\n     \"reject_reason\": \"Reject reason of transaction\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 03:21:19', '2022-11-24 00:50:25'),
(36, 'WIRE_TRANSFER_REQUEST_SEND', 'Wire Transfer Request Sent', 'Money Transfer Request Sent Successfully', NULL, '<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap;\">{{sending_amount}}&nbsp;</span><span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap;\">{{site_currency}} Wire</span>&nbsp;transfer request submitted&nbsp;successfully', '{{sending_amount}} {{site_currency}} Wire transfer request submitted successfully', NULL, '{\n     \"sender_account_number\": \"Sender account number\",\n     \"sender_account_name\": \"Sender account name\",\n     \"recipient_account_number\": \"Recipient account number\",\n     \"recipient_account_name\": \"Recipient account name\",\n     \"sending_amount\": \"The amount to be transferred\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"The amount including charge\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 03:21:19', '2022-11-24 00:53:45'),
(37, 'WIRE_TRANSFER_COMPLETED', 'Wire Transfer Completed', 'WireTransfer Completed Successfully', NULL, '<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap;\">{{sending_amount}}&nbsp;</span><span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap;\">{{site_currency}}</span>&nbsp;sent to&nbsp;successfully', '{{sending_amount}} {{site_currency}} sent to successfully', '{{sending_amount}} {{site_currency}} sent to successfully', '{\n     \"sender_account_number\": \"Sender account number\",\n     \"sender_account_name\": \"Sender account name\",\n     \"recipient_account_number\": \"Recipient account number\",\n     \"recipient_account_name\": \"Recipient account name\",\n     \"sending_amount\": \"The amount to be transferred\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"The amount including charge\"\n}', 1, 1, NULL, NULL, NULL, 0, '2021-07-10 03:21:19', '2024-06-08 22:23:02'),
(38, 'WIRE_TRANSFER_REJECTED', 'Wire Transfer Rejected', 'Wire Transfer Rejected Successfully', NULL, '<span style=\"color: rgb(33, 37, 41); font-size: 12px; font-weight: 600; white-space: nowrap;\">{{sending_amount}}&nbsp;</span><span style=\"white-space: nowrap; font-weight: 600; font-size: 12px; color: rgb(33, 37, 41);\">{{site_currency}}</span>&nbsp;transfer request is rejected for&nbsp;{{reject_reason}} &nbsp;and the amount has refunded to your account', '{{sending_amount}} {{site_currency}} transfer request is rejected for {{reject_reason}}  and the amount has refunded to your account', NULL, '{\n     \"sender_account_number\": \"Sender account number\",\n     \"sender_account_name\": \"Sender account name\",\n     \"recipient_account_number\": \"Recipient account number\",\n     \"recipient_account_name\": \"Recipient account name\",\n     \"sending_amount\": \"The amount to be transferred\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"The amount including charge\",\n     \"reject_reason\": \"Reject reason of transaction\"\n}', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 03:21:19', '2022-11-24 00:53:27'),
(39, 'DEPOSIT_VIA_BRANCH', 'Deposit From Branch', 'Deposited Successfullly', NULL, '<div style=\"\"><font face=\"Montserrat, sans-serif\">Dear&nbsp;</font><b style=\"font-family: Montserrat, sans-serif;\">{{username}},&nbsp;&nbsp;</b><span style=\"font-family: Montserrat, sans-serif; font-size: 1rem; text-align: var(--bs-body-text-align);\"><b>{{amount}}</b></span><span style=\"font-family: Montserrat, sans-serif; font-weight: 700; font-size: 1rem; text-align: var(--bs-body-text-align);\"> </span><span style=\"font-family: Montserrat, sans-serif; font-size: 1rem; text-align: var(--bs-body-text-align);\"><b>{{site_currency}}</b></span><span style=\"font-family: Montserrat, sans-serif; font-weight: 700; font-size: 1rem; text-align: var(--bs-body-text-align);\"> </span><span style=\"font-family: Montserrat, sans-serif; font-size: 1rem; text-align: var(--bs-body-text-align);\">added your account from the</span><span style=\"font-family: Montserrat, sans-serif; font-weight: 700; font-size: 1rem; text-align: var(--bs-body-text-align);\"> </span><span style=\"font-family: Montserrat, sans-serif; font-size: 1rem; text-align: var(--bs-body-text-align);\">branch</span><span style=\"font-family: Montserrat, sans-serif; font-weight: 700; font-size: 1rem; text-align: var(--bs-body-text-align);\">&nbsp;</span><span style=\"text-align: var(--bs-body-text-align);\"><font face=\"Montserrat, sans-serif\"><b>{{branch_name}}</b></font></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your Deposit :<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\"><span style=\"font-weight: bolder;\"><br></span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><font size=\"5\">Your current Balance is&nbsp;<span style=\"font-weight: bolder;\">{{post_balance}} {{site_currency}}</span></font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>', 'Admin Approve Your {{amount}} {{site_currency}} payment request by {{method_name}} transaction : {{trx}}', NULL, '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the Branch of user account\",\"charge\":\"Deposit Charge\",\"branch_name\":\"Name of the Branch\",\"post_balance\":\"Balance of the user after this transaction\",\"username\":\"username of the deposited user\"}', 1, 1, NULL, NULL, NULL, 1, '2021-11-02 18:00:00', '2022-08-13 10:18:15'),
(40, 'WITHDRAW_VIA_BRANCH', 'Withdraw From Branch', 'Withdrawn Successfully', NULL, '<div style=\"\"><span style=\"font-family: Montserrat, sans-serif; font-weight: bolder;\">Your&nbsp;</span><span style=\"font-family: Montserrat, sans-serif; font-weight: 700; font-size: 1rem; text-align: var(--bs-body-text-align);\">withdraw successfulllyy from the Branch&nbsp;</span><span style=\"text-align: var(--bs-body-text-align);\"><font face=\"Montserrat, sans-serif\"><b>{{branch_name}}</b></font></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\"><br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><span style=\"font-weight: bolder;\">Details of your withdraw:<br></span></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Amount : {{amount}} {{site_currency}}</div><div style=\"font-family: Montserrat, sans-serif;\">Charge:&nbsp;<font color=\"#FF0000\">{{charge}} {{site_currency}}</font></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">Transaction Number : {{trx}}</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\">-----</div><div style=\"font-family: Montserrat, sans-serif;\"><br></div><div style=\"font-family: Montserrat, sans-serif;\"><br></div>', 'Amount withdrawn successfully from the Branch {{branch_name}}', 'Amount withdrawn successfully from the Branch {{branch_name}}', '        {\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount Of Withdraw\",\"charge\":\"Amount Of Withdraw Charge\",\"branch_name\":\"Name of the withdraw branch\"}\r\n', 1, 1, NULL, NULL, NULL, 1, '2021-11-02 18:00:00', '2024-06-05 22:56:58'),
(41, 'REFERRAL_COMMISSION', 'Referral Commission', 'Referral Commission', NULL, 'Congratulation, You you  {{amount}}&nbsp;{{site_currency}}&nbsp; interest And your main balance {{post_balance}}&nbsp;{{site_currency}}&nbsp; . {{level}} . Transaction {{trx}}', 'Congratulation, You you {{amount}} {{site_currency}}  interest And your main balance {{post_balance}} {{site_currency}}  . {{level}} . Transaction {{trx}}', NULL, '{\n     \"amount\": \"amount\",\n     \"post_balance\": \"Balance after commission received\",\n     \"trx\": \"Transaction Number\",\n     \"level\": \"level\"\n}', 1, 1, NULL, NULL, NULL, 1, '2019-09-13 19:14:22', '2022-08-28 19:26:21'),
(42, 'OWN_BANK_TRANSFER_MONEY_SEND', 'Own Bank Transfer - Money Send', 'Money Transferred Successfully', NULL, 'Transfer Money Completed Successfully', 'Transfer Money Completed Successfully', NULL, '{\n     \"sender\": \"Who send the money\",\n     \"recipient\": \"Who receive the money\",\n     \"amount\": \"Transfer amount\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"Final amount after charge\",\n     \"trx\": \"Transaction number\",\n     \"post_balance\": \"Balance after this operation\"\n}', 1, 1, NULL, NULL, NULL, 1, NULL, NULL),
(43, 'OWN_BANK_TRANSFER_MONEY_RECEIVE', 'Own Bank Transfer - Money Receive', 'Transferred Money Received', NULL, 'You have received {{amount}}&nbsp;{{site_currency}}', 'You have received {{amount}} {{site_currency}}', NULL, '{\n     \"sender\": \"Who send the money\",\n     \"recipient\": \"Who receive the money\",\n     \"amount\": \"Transfer amount\",\n     \"charge\": \"Transfer charge\",\n     \"final_amount\": \"Final amount after charge\",\n     \"trx\": \"Transaction number\",\n     \"post_balance\": \"Balance after this operation\"\n}\n', 1, 1, NULL, NULL, NULL, 1, NULL, '2022-11-13 23:21:15'),
(44, 'STAFF_CREDENTIALS', 'Staff Credentials', 'Staff Login Credentials', NULL, 'Email: {{email}}<br>Password: {{password}}<div><br></div><div><br>\r\n\r\n<a href=\"{{login_link}}\" style=\"\r\n    background: #4634ff;\r\n    color: #ffff;\r\n    padding: 10px 15px;\r\n    border-radius: 5px;\r\n\">Login Now</a>\r\n</div>', 'Email: {{email}}\r\nPassword: {{password}}', NULL, '{\n     \"email\": \"Staff Email\",\n     \"password\": \"Password\",\n     \"login_link\": \"Login Link for Staff\"\n\n}', 1, 1, NULL, NULL, NULL, 1, NULL, '2022-11-26 22:20:16'),
(45, 'ACCOUNT_OPENED', 'Account Opened', 'Account Opened successfully', NULL, '<div> Your account has been opened successfully.<br>\r\n\r\n</div><div>Your Login Credentials</div><div>Username:{{username}}</div><div>Password:{{password}}</div><div><br></div>', 'Your account has been opened successfully.\r\nUsername:{{username}}\r\nPassword:{{password}}', NULL, '{\r\n    \"email\":\"Your Email Address\",\r\n    \"username\":\"Your username\",\r\n    \"Password\":\"Your Password\"\r\n}\r\n', 1, 1, NULL, NULL, NULL, 1, '2021-07-10 09:21:19', '2022-12-04 22:24:55');
INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `sms_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(46, 'WITHDRAW_OTP', 'OTP for Withdraw', 'OTP for Withdraw', NULL, '<div>Your {{site_name}} OTP is {{otp}}</div>', 'Your {{site_name}} OTP is {{otp}}', 'Your {{site_name}} OTP is {{otp}}', '{\r\n    \"sitename\": \"Site Name\",\r\n    \"otp\": \"One Time Password\"\r\n}', 1, 1, NULL, NULL, NULL, 0, NULL, NULL),
(47, 'TOP_UP', 'Airtime Top Up', 'Top Up Completed', NULL, 'Top up {{amount}} {{site_currency}} to {{mobile_number}} has been completed successfully. Your current balance is: {{post_balance}} {{site_currency}}', 'Top up {{amount}} {{site_currency}} to {{mobile_number}} has been completed successfully. Your current balance is: {{post_balance}} {{site_currency}}', 'Top up {{amount}} {{site_currency}} to {{mobile_number}} has been completed successfully. Your current balance is: {{post_balance}} {{site_currency}}', '{\r\n\"amount\": \"amount\",\r\n\"mobile_number\": \"Recipient Mobile Number\",\r\n\"post_balance\":\"Post Balance\"\r\n}', 1, 1, NULL, NULL, NULL, 0, NULL, NULL),
(48, 'AIRTIME_OTP', 'OTP for Airtime Top-Up', 'OTP for Top-Up', NULL, '<div>Your OTP is {{otp}}</div>', 'Your OTP is {{otp}}', 'Your OTP is {{otp}}', '{\r\n \"otp\": \"One Time Password\"\r\n}', 1, 1, NULL, NULL, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `operators`
--

CREATE TABLE `operators` (
  `id` bigint NOT NULL,
  `country_id` int UNSIGNED NOT NULL DEFAULT '0',
  `unique_id` int UNSIGNED NOT NULL DEFAULT '0',
  `operator_group_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bundle` tinyint(1) NOT NULL DEFAULT '0',
  `data` tinyint(1) NOT NULL DEFAULT '0',
  `pin` tinyint(1) NOT NULL DEFAULT '0',
  `denomination_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_currency_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destination_currency_symbol` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `most_popular_amount` decimal(28,8) DEFAULT NULL,
  `min_amount` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_amount` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_urls` text COLLATE utf8mb4_unicode_ci,
  `fixed_amounts` text COLLATE utf8mb4_unicode_ci,
  `fixed_amounts_descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `local_fixed_amounts` mediumtext COLLATE utf8mb4_unicode_ci,
  `local_fixed_amounts_descriptions` mediumtext COLLATE utf8mb4_unicode_ci,
  `suggested_amounts` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operator_groups`
--

CREATE TABLE `operator_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `country_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_banks`
--

CREATE TABLE `other_banks` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000' COMMENT 'Per transaction',
  `maximum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000' COMMENT 'Per transaction',
  `daily_maximum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `monthly_maximum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `daily_total_transaction` int NOT NULL DEFAULT '0',
  `monthly_total_transaction` int UNSIGNED NOT NULL DEFAULT '0',
  `fixed_charge` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `processing_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruction` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `form_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

CREATE TABLE `otp_verifications` (
  `id` bigint UNSIGNED NOT NULL,
  `verifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `verifiable_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `otp` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `send_via` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notify_template` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `send_at` datetime DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `used_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'template name',
  `secs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `seo_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `tempname`, `secs`, `seo_content`, `is_default`, `created_at`, `updated_at`) VALUES
(26, 'HOME', '/', 'templates.indigo_fusion.', '[\"feature\",\"about\",\"service\",\"why_choose\",\"how_it_work\",\"fdr_plans\",\"dps_plans\",\"loan_plans\",\"overview\",\"testimonial\",\"faq\",\"partner_section\",\"subscribe\"]', NULL, 1, '2020-07-10 18:23:58', '2023-09-07 00:01:46'),
(27, 'Contact', 'contact', 'templates.indigo_fusion.', '[\"subscribe\"]', NULL, 1, '2020-10-21 13:14:53', '2021-08-10 23:58:01'),
(28, 'About Us', 'about-us', 'templates.indigo_fusion.', '[\"about\",\"service\",\"overview\",\"testimonial\",\"partner_section\",\"subscribe\"]', NULL, 0, '2021-07-27 00:37:15', '2021-08-10 23:56:08'),
(29, 'Services', 'services', 'templates.indigo_fusion.', '[\"service\",\"overview\",\"testimonial\",\"partner_section\",\"subscribe\"]', NULL, 0, '2021-07-27 00:38:01', '2021-08-10 23:56:57'),
(30, 'FAQ', 'faq', 'templates.indigo_fusion.', '[\"faq\",\"service\",\"testimonial\",\"overview\",\"subscribe\"]', NULL, 0, '2021-07-27 00:40:09', '2021-08-10 23:57:31'),
(31, 'Branch', 'branch', 'templates.indigo_fusion.', NULL, NULL, 1, '2022-11-23 16:34:34', '2022-11-23 16:34:34'),
(32, 'Home', '/', 'templates.crystal_sky.', '[\"about\",\"service\",\"why_choose\",\"feature\",\"how_it_work\",\"dps_plans\",\"fdr_plans\",\"loan_plans\",\"testimonial\",\"faq\",\"partner_section\",\"counter\"]', '{\"image\":null,\"description\":null,\"social_title\":null,\"social_description\":null,\"keywords\":null}', 1, '2023-08-29 07:15:24', '2024-06-07 18:45:35'),
(33, 'About', 'about', 'templates.crystal_sky.', '[\"about\",\"feature\",\"how_it_work\"]', NULL, 0, '2023-08-30 05:59:35', '2023-09-04 23:40:33'),
(34, 'FAQ', 'faq', 'templates.crystal_sky.', '[\"faq\"]', NULL, 0, '2023-08-30 06:01:06', '2023-09-04 20:41:30'),
(35, 'Contact', 'contact', 'templates.crystal_sky.', NULL, NULL, 1, '2023-09-03 07:24:50', '2023-09-03 07:28:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `group`, `code`) VALUES
(1, 'View Dashboard', 'AdminController', 'admin.dashboard'),
(2, 'View Notifications', 'AdminController', 'admin.notifications'),
(3, 'Mark Single Notification As Read', 'AdminController', 'admin.notification.read'),
(4, 'Mark All Notification As Read', 'AdminController', 'admin.notifications.read.all'),
(5, 'Delete Single Notification', 'AdminController', 'admin.notifications.delete.single'),
(6, 'Delete All Notifications', 'AdminController', 'admin.notifications.delete.all'),
(7, 'Download Files Submitted by Users', 'AdminController', 'admin.download.attachment'),
(8, 'View All Accounts List', 'ManageAccountsController', 'admin.users.all'),
(9, 'View Active Accounts List', 'ManageAccountsController', 'admin.users.active'),
(10, 'View Banned Accounts List', 'ManageAccountsController', 'admin.users.banned'),
(11, 'View Email Verified Accounts List', 'ManageAccountsController', 'admin.users.email.verified'),
(12, 'View Email Unverified Accounts List', 'ManageAccountsController', 'admin.users.email.unverified'),
(13, 'View Mobile Verified Accounts List', 'ManageAccountsController', 'admin.users.mobile.verified'),
(14, 'View Mobile Unverified Accounts List', 'ManageAccountsController', 'admin.users.mobile.unverified'),
(15, 'View KYC Verified Users List', 'ManageAccountsController', 'admin.users.kyc.verified'),
(16, 'View KYC Unverified Users List', 'ManageAccountsController', 'admin.users.kyc.unverified'),
(17, 'View KYC Pending Users List', 'ManageAccountsController', 'admin.users.kyc.pending'),
(18, 'View Profile Incomplete Accounts List', 'ManageAccountsController', 'admin.users.profile.incomplete'),
(19, 'View Profile Completed Accounts List', 'ManageAccountsController', 'admin.users.profile.completed'),
(20, 'View Account\'s Details', 'ManageAccountsController', 'admin.users.detail'),
(21, 'Update Account\'s Details', 'ManageAccountsController', 'admin.users.update'),
(22, 'View Account\'s KYC Details', 'ManageAccountsController', 'admin.users.kyc.details'),
(23, 'Approve Account\'s KYC Data', 'ManageAccountsController', 'admin.users.kyc.approve'),
(24, 'Reject Account\'s KYC Data', 'ManageAccountsController', 'admin.users.kyc.reject'),
(25, 'Add/Sub Account\'s Balance', 'ManageAccountsController', 'admin.users.add.sub.balance'),
(26, 'Send Notification to a Single Account', 'ManageAccountsController', 'admin.users.notification.single'),
(27, 'View the Send Notification to All Accounts Page', 'ManageAccountsController', 'admin.users.notification.all'),
(28, 'Submit the Send Notification to All Accounts Form', 'ManageAccountsController', 'admin.users.notification.all.send'),
(29, 'View Account\'s Notification Log', 'ManageAccountsController', 'admin.users.notification.log'),
(30, 'View Account\'s Beneficiaries', 'ManageAccountsController', 'admin.users.beneficiaries'),
(31, 'View Beneficiary Details', 'ManageAccountsController', 'admin.users.beneficiary.details'),
(32, 'Login As Account', 'ManageAccountsController', 'admin.users.login'),
(33, 'Ban/Unban a Account', 'ManageAccountsController', 'admin.users.status'),
(34, 'View All Deposits List', 'DepositController', 'admin.deposit.list'),
(35, 'View Pending Deposits List', 'DepositController', 'admin.deposit.pending'),
(36, 'View Rejected Deposits List', 'DepositController', 'admin.deposit.rejected'),
(37, 'View Approved Deposits List', 'DepositController', 'admin.deposit.approved'),
(38, 'View Successful Deposits List', 'DepositController', 'admin.deposit.successful'),
(39, 'View Initiated Deposits List', 'DepositController', 'admin.deposit.initiated'),
(40, 'View Deposit\'s Details', 'DepositController', 'admin.deposit.details'),
(41, 'Approve a Manual Deposit', 'DepositController', 'admin.deposit.approve'),
(42, 'Reject a Manual Deposit', 'DepositController', 'admin.deposit.reject'),
(43, 'View All Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.all'),
(44, 'View Pending Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.pending'),
(45, 'View Approved Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.approved'),
(46, 'View Rejected Withdrawals List', 'WithdrawalController', 'admin.withdraw.data.rejected'),
(47, 'View Withdrawal Details', 'WithdrawalController', 'admin.withdraw.data.details'),
(48, 'Approve a Withdrawal Request', 'WithdrawalController', 'admin.withdraw.data.approve'),
(49, 'Reject a Withdrawal Request', 'WithdrawalController', 'admin.withdraw.data.reject'),
(50, 'View All FDRs List', 'FdrController', 'admin.fdr.index'),
(51, 'View Running FDRs List', 'FdrController', 'admin.fdr.running'),
(52, 'View Closed FDRs List', 'FdrController', 'admin.fdr.closed'),
(53, 'View FDRs List Having Due Installments', 'FdrController', 'admin.fdr.due'),
(54, 'Pay Due Installments of FDR', 'FdrController', 'admin.fdr.due.pay'),
(55, 'View FDR Installments', 'FdrController', 'admin.fdr.installments'),
(56, 'View All DPS List', 'DpsController', 'admin.dps.index'),
(57, 'View Running DPS List', 'DpsController', 'admin.dps.running'),
(58, 'View Matured DPS List', 'DpsController', 'admin.dps.matured'),
(59, 'View Closed DPS List', 'DpsController', 'admin.dps.closed'),
(60, 'View DPS List Having Due Installment', 'DpsController', 'admin.dps.due'),
(61, 'View DPS Installments', 'DpsController', 'admin.dps.installments'),
(62, 'View All Loans List', 'LoanController', 'admin.loan.index'),
(63, 'View Running Loans List', 'LoanController', 'admin.loan.running'),
(64, 'View Pending Loans List', 'LoanController', 'admin.loan.pending'),
(65, 'View Rejected Loans List', 'LoanController', 'admin.loan.rejected'),
(66, 'View Paid Loans List', 'LoanController', 'admin.loan.paid'),
(67, 'View Loans List Having Due Installment', 'LoanController', 'admin.loan.due'),
(68, 'View Loan Details Page', 'LoanController', 'admin.loan.details'),
(69, 'Approve a Loan Request', 'LoanController', 'admin.loan.approve'),
(70, 'Reject a Loan Request', 'LoanController', 'admin.loan.reject'),
(71, 'View Loan Installments', 'LoanController', 'admin.loan.installments'),
(72, 'View All Transfers List', 'MoneyTransferController', 'admin.transfers.index'),
(73, 'View Pending Transfers List', 'MoneyTransferController', 'admin.transfers.pending'),
(74, 'View Rejected Transfers List', 'MoneyTransferController', 'admin.transfers.rejected'),
(75, 'View Own Bank Transfers List', 'MoneyTransferController', 'admin.transfers.own'),
(76, 'View Other Bank Transfers List', 'MoneyTransferController', 'admin.transfers.other'),
(77, 'View Wire Transfers List', 'MoneyTransferController', 'admin.transfers.wire'),
(78, 'View Transfers Details Page', 'MoneyTransferController', 'admin.transfers.details'),
(79, 'Complete a Transfer Request', 'MoneyTransferController', 'admin.transfers.complete'),
(80, 'Reject a Transfer Request', 'MoneyTransferController', 'admin.transfers.reject'),
(81, 'View Login History', 'ReportController', 'admin.report.login.history'),
(82, 'View Notification History', 'ReportController', 'admin.report.notification.history'),
(83, 'View Notification Details', 'ReportController', 'admin.report.email.details'),
(84, 'View Transaction History', 'ReportController', 'admin.report.transaction'),
(85, 'View FDR Plans', 'FdrPlanController', 'admin.plans.fdr.index'),
(86, 'Add/Update FDR Plan', 'FdrPlanController', 'admin.plans.fdr.save'),
(87, 'Enable/Disable an FDR Plan', 'FdrPlanController', 'admin.plans.fdr.status'),
(88, 'View DPS Plans', 'DpsPlanController', 'admin.plans.dps.index'),
(89, 'View Add New DPS Plan Page', 'DpsPlanController', 'admin.plans.dps.add'),
(90, 'View Edit DPS Plan Page', 'DpsPlanController', 'admin.plans.dps.edit'),
(91, 'Add/Update DPS a Plan', 'DpsPlanController', 'admin.plans.dps.save'),
(92, 'Enable/Disable a DPS Plan', 'DpsPlanController', 'admin.plans.dps.status'),
(93, 'View Loan Plans', 'LoanPlanController', 'admin.plans.loan.index'),
(94, 'View Add New Loan Plan Page', 'LoanPlanController', 'admin.plans.loan.create'),
(95, 'View Edit Loan Plan Page', 'LoanPlanController', 'admin.plans.loan.edit'),
(96, 'Add/Update Loan Plan', 'LoanPlanController', 'admin.plans.loan.save'),
(97, 'Enable/Disable a Loan Plan', 'LoanPlanController', 'admin.plans.loan.status'),
(98, 'View Other Banks List', 'OtherBankController', 'admin.bank.index'),
(99, 'View Add New Bank Page', 'OtherBankController', 'admin.bank.create'),
(100, 'View Edit Bank Page', 'OtherBankController', 'admin.bank.edit'),
(101, 'Add/Update Other Bank Details', 'OtherBankController', 'admin.bank.store'),
(102, 'Enable/Disable Other Bank', 'OtherBankController', 'admin.bank.change.status'),
(103, 'View Wire Transfer Setting', 'WireTransferSettingController', 'admin.wire.transfer.setting'),
(104, 'Update Wire Transfer Setting', 'WireTransferSettingController', 'admin.wire.transfer.setting.save'),
(105, 'View Wire Transfer Form', 'WireTransferSettingController', 'admin.wire.transfer.form'),
(106, 'Update Wire Transfer Form', 'WireTransferSettingController', 'admin.wire.transfer.form.save'),
(107, 'View Countries List', 'AirtimeController', 'admin.airtime.countries'),
(108, 'Fetch Countries from API', 'AirtimeController', 'admin.airtime.countries.fetch'),
(109, 'Save the Fetched Countries', 'AirtimeController', 'admin.airtime.countries.save'),
(110, 'Enable/Disable a Country', 'AirtimeController', 'admin.airtime.country.status'),
(111, 'View All Operators List', 'AirtimeController', 'admin.airtime.operators'),
(112, 'Fetch Operators from API', 'AirtimeController', 'admin.airtime.operators.fetch'),
(113, 'Save Fetched Operators', 'AirtimeController', 'admin.airtime.operators.save'),
(114, 'Enable/Disable an Operator', 'AirtimeController', 'admin.airtime.operator.status'),
(115, 'View All Branches', 'BranchController', 'admin.branch.index'),
(116, 'View Add New Branch Page', 'BranchController', 'admin.branch.add'),
(117, 'Add/Update Branch ', 'BranchController', 'admin.branch.save'),
(118, 'View Branch Details Page', 'BranchController', 'admin.branch.details'),
(119, 'Enable/Disable a Branch', 'BranchController', 'admin.branch.status'),
(120, 'View Branch Staff List', 'BranchStaffController', 'admin.branch.staff.index'),
(121, 'Add New Branch Staff', 'BranchStaffController', 'admin.branch.staff.add'),
(122, 'Add/Update Branch Staff', 'BranchStaffController', 'admin.branch.staff.save'),
(123, 'View Branch Staff Details', 'BranchStaffController', 'admin.branch.staff.details'),
(124, 'Enable/Disable Branch Staff', 'BranchStaffController', 'admin.branch.staff.status'),
(125, 'Login As Another Branch Staff', 'BranchStaffController', 'admin.branch.staff.login'),
(126, 'View Frontend Content Management Options', 'FrontendController', 'admin.frontend.index'),
(127, 'View Frontend Sections Content', 'FrontendController', 'admin.frontend.sections'),
(128, 'Update Frontend Sections Content', 'FrontendController', 'admin.frontend.sections.content'),
(129, 'View Frontend Sections Single Element', 'FrontendController', 'admin.frontend.sections.element'),
(130, 'View Frontend Sections Element SEO', 'FrontendController', 'admin.frontend.sections.element.seo'),
(131, 'Update Frontend Sections Element SEO', 'FrontendController', 'admin.frontend.sections.element.seo.update'),
(132, 'Remove Frontend Section Element', 'FrontendController', 'admin.frontend.remove'),
(133, 'Manage Pages', 'FrontendController', 'admin.frontend.manage.pages'),
(134, 'Add New Page', 'FrontendController', 'admin.frontend.manage.pages.save'),
(135, 'Update Page Info', 'FrontendController', 'admin.frontend.manage.pages.update'),
(136, 'Delete a Page', 'FrontendController', 'admin.frontend.manage.pages.delete'),
(137, 'View Sections of a Page', 'FrontendController', 'admin.frontend.manage.section'),
(138, 'Update Sections of a Page', 'FrontendController', 'admin.frontend.manage.section.update'),
(139, 'View the Manage SEO for Specific Page', 'FrontendController', 'admin.frontend.manage.pages.seo'),
(140, 'Submit the Manage SEO Form', 'FrontendController', 'admin.frontend.manage.pages.seo.store'),
(141, 'View All Frontend Templates', 'FrontendController', 'admin.frontend.templates'),
(142, 'Change Active Template', 'FrontendController', 'admin.frontend.templates.active'),
(143, 'View Cookie Policy', 'FrontendController', 'admin.setting.cookie'),
(144, 'Update Cookie Policy', 'FrontendController', 'admin.setting.cookie.submit'),
(145, 'View Global SEO Manager', 'FrontendController', 'admin.frontend.seo'),
(146, 'Update Global SEO Manager', 'FrontendController', 'admin.frontend.seo.update'),
(147, 'View Sitemap Setting Page', 'FrontendController', 'admin.setting.sitemap'),
(148, 'Update Setting Sitemap', 'FrontendController', 'admin.setting.sitemap.submit'),
(149, 'View the Robots.txt Setting', 'FrontendController', 'admin.setting.robot'),
(150, 'Update the Robots.txt Setting', 'FrontendController', 'admin.setting.robot.submit'),
(151, 'View Custom CSS', 'FrontendController', 'admin.setting.custom.css'),
(152, 'Update Custom CSS', 'FrontendController', 'admin.setting.custom.css.submit'),
(153, 'View Maintenance Mode Content', 'FrontendController', 'admin.maintenance.mode'),
(154, 'Update Maintenance Mode Content', 'FrontendController', 'admin.maintenance.mode.submit'),
(155, 'View Languages List', 'LanguageController', 'admin.language.manage'),
(156, 'Add a New Language', 'LanguageController', 'admin.language.manage.store'),
(157, 'Update a Language', 'LanguageController', 'admin.language.manage.update'),
(158, 'Delete a Language', 'LanguageController', 'admin.language.manage.delete'),
(159, 'Fetch Keywords from System', 'LanguageController', 'admin.language.get.key'),
(160, 'View Keywords List', 'LanguageController', 'admin.language.key'),
(161, 'Import Keywords', 'LanguageController', 'admin.language.import.lang'),
(162, 'Add a New Keyword', 'LanguageController', 'admin.language.store.key'),
(163, 'Delete a Keyword', 'LanguageController', 'admin.language.delete.key'),
(164, 'Update a Keyword', 'LanguageController', 'admin.language.update.key'),
(165, 'View Automatic Gateways List', 'AutomaticGatewayController', 'admin.gateway.automatic.index'),
(166, 'View the Update Automatic Gateway Page', 'AutomaticGatewayController', 'admin.gateway.automatic.edit'),
(167, 'Submit the Update Automatic Gateway Form', 'AutomaticGatewayController', 'admin.gateway.automatic.update'),
(168, 'Remove a Gateway Currency', 'AutomaticGatewayController', 'admin.gateway.automatic.remove'),
(169, 'Enable/Disable a Gateway', 'AutomaticGatewayController', 'admin.gateway.automatic.status'),
(170, 'View Manual Gateway List', 'ManualGatewayController', 'admin.gateway.manual.index'),
(171, 'View the Add New Gateway Page', 'ManualGatewayController', 'admin.gateway.manual.create'),
(172, 'Submit the Add New Gateway Form', 'ManualGatewayController', 'admin.gateway.manual.store'),
(173, 'View the Update Gateway Page', 'ManualGatewayController', 'admin.gateway.manual.edit'),
(174, 'Submit the Update Gateway Form', 'ManualGatewayController', 'admin.gateway.manual.update'),
(175, 'Enable/Disable a Gateway', 'ManualGatewayController', 'admin.gateway.manual.status'),
(176, 'View Withdrawal Methods List', 'WithdrawMethodController', 'admin.withdraw.method.index'),
(177, 'View the Add New Method Page', 'WithdrawMethodController', 'admin.withdraw.method.create'),
(178, 'Submit the Add New Method Form', 'WithdrawMethodController', 'admin.withdraw.method.store'),
(179, 'View the Update Method Page', 'WithdrawMethodController', 'admin.withdraw.method.edit'),
(180, 'Submit the Update Method Form', 'WithdrawMethodController', 'admin.withdraw.method.update'),
(181, 'Enable/Disable a Method', 'WithdrawMethodController', 'admin.withdraw.method.status'),
(182, 'View Extensions List', 'ExtensionController', 'admin.extensions.index'),
(183, 'Configure an Extension', 'ExtensionController', 'admin.extensions.update'),
(184, 'Enable/Disable an Extension', 'ExtensionController', 'admin.extensions.status'),
(185, 'View Global Email Template', 'NotificationSettingController', 'admin.setting.notification.global.email'),
(186, 'Update Global Email Template Form', 'NotificationSettingController', 'admin.setting.notification.global.email.update'),
(187, 'View Email Setting', 'NotificationSettingController', 'admin.setting.notification.email'),
(188, 'Submit Email Setting Form', 'NotificationSettingController', 'admin.setting.notification.email.update'),
(189, 'Send Test Email', 'NotificationSettingController', 'admin.setting.notification.email.test'),
(190, 'View Global SMS Template', 'NotificationSettingController', 'admin.setting.notification.global.sms'),
(191, 'Update Global SMS Template Form', 'NotificationSettingController', 'admin.setting.notification.global.sms.update'),
(192, 'View SMS Setting', 'NotificationSettingController', 'admin.setting.notification.sms'),
(193, 'Update SMS Setting', 'NotificationSettingController', 'admin.setting.notification.sms.update'),
(194, 'Send Test SMS', 'NotificationSettingController', 'admin.setting.notification.sms.test'),
(195, 'View Global Push Notification Template', 'NotificationSettingController', 'admin.setting.notification.global.push'),
(196, 'Update Global Push Notification Template', 'NotificationSettingController', 'admin.setting.notification.global.push.update'),
(197, 'View Push Notification Setting', 'NotificationSettingController', 'admin.setting.notification.push'),
(198, 'Download Push Notification Config File', 'NotificationSettingController', 'admin.setting.notification.push.download'),
(199, 'Upload Push Notification Config File', 'NotificationSettingController', 'admin.setting.notification.push.upload'),
(200, 'Update Push Notification Setting', 'NotificationSettingController', 'admin.setting.notification.push.update'),
(201, 'View All Notification Templates', 'NotificationSettingController', 'admin.setting.notification.templates'),
(202, 'View Notification Update Page', 'NotificationSettingController', 'admin.setting.notification.template.edit'),
(203, 'Update Single Notification Template', 'NotificationSettingController', 'admin.setting.notification.template.update'),
(204, 'View Setting System Page', 'SystemSettingsController', 'admin.setting.system'),
(205, 'View General Settings', 'SystemSettingsController', 'admin.setting.general'),
(206, 'Update General Settings', 'SystemSettingsController', 'admin.setting.update'),
(207, 'View System Configuration', 'SystemSettingsController', 'admin.setting.system.configuration'),
(208, 'Update System Configuration', 'SystemSettingsController', 'admin.setting.system.configuration.submit'),
(209, 'View Logo & Favicon', 'SystemSettingsController', 'admin.setting.logo.icon'),
(210, 'Update Logo & Favicon', 'SystemSettingsController', 'admin.setting.logo.icon.update'),
(211, 'View Referral Setting', 'SystemSettingsController', 'admin.referral.setting'),
(212, 'Submit Referral Setting Form', 'SystemSettingsController', 'admin.referral.setting.save'),
(213, 'View API Configuration Page', 'SystemSettingsController', 'admin.api.config.index'),
(214, 'Update Airtime API Credentials', 'SystemSettingsController', 'admin.api.config.reloadly.save'),
(215, 'View KYC Setting', 'SystemSettingsController', 'admin.kyc.setting'),
(216, 'Submit KYC Setting Form', 'SystemSettingsController', 'admin.kyc.setting.submit'),
(217, 'View Social Login Setting', 'SystemSettingsController', 'admin.setting.socialite.credentials'),
(218, 'Update Social Login Credentials', 'SystemSettingsController', 'admin.setting.socialite.credentials.update'),
(219, 'Enable/Disable Social Login Options', 'SystemSettingsController', 'admin.setting.socialite.credentials.status.update'),
(220, 'View In App Purchase Page', 'SystemSettingsController', 'admin.setting.app.purchase'),
(221, 'Download Google Pay JSON File', 'SystemSettingsController', 'admin.setting.app.purchase.file.download'),
(222, 'Update/Upload Google Pay JSON File', 'SystemSettingsController', 'admin.setting.app.purchase.submit'),
(223, 'View All Tickets List', 'SupportTicketController', 'admin.ticket.index'),
(224, 'View Pending Tickets List', 'SupportTicketController', 'admin.ticket.pending'),
(225, 'View Closed Tickets List', 'SupportTicketController', 'admin.ticket.closed'),
(226, 'View Answered Tickets List', 'SupportTicketController', 'admin.ticket.answered'),
(227, 'View Ticket Page', 'SupportTicketController', 'admin.ticket.view'),
(228, 'Reply a Ticket', 'SupportTicketController', 'admin.ticket.reply'),
(229, 'Close a Ticket', 'SupportTicketController', 'admin.ticket.close'),
(230, 'Delete a Ticket', 'SupportTicketController', 'admin.ticket.delete'),
(231, 'Download Ticket Attachments', 'SupportTicketController', 'admin.ticket.download'),
(232, 'View Roles List', 'RolesController', 'admin.roles.index'),
(233, 'View Add New Role Page', 'RolesController', 'admin.roles.add'),
(234, 'Edit Role - Page', 'RolesController', 'admin.roles.edit'),
(235, 'Submit Add/Update Role Form', 'RolesController', 'admin.roles.save'),
(236, 'View All Staff List', 'AdminStaffController', 'admin.staff.index'),
(237, 'Add New Staff', 'AdminStaffController', 'admin.staff.save'),
(238, 'Enable / Disable Staff', 'AdminStaffController', 'admin.staff.status'),
(239, 'Login as Another Staff', 'AdminStaffController', 'admin.staff.login'),
(240, 'View Subscribers List', 'SubscriberController', 'admin.subscriber.index'),
(241, 'View the Send Email to All Subscribers Page', 'SubscriberController', 'admin.subscriber.send.email'),
(242, 'Remove a Subscriber', 'SubscriberController', 'admin.subscriber.remove'),
(243, 'View Application Information', 'SystemController', 'admin.system.info'),
(244, 'View Server Information', 'SystemController', 'admin.system.server.info'),
(245, 'View Clear Cache Page', 'SystemController', 'admin.system.optimize'),
(246, 'Clear Cache', 'SystemController', 'admin.system.optimize.clear'),
(247, 'View Update Page', 'SystemController', 'admin.system.update'),
(248, 'Update the System', 'SystemController', 'admin.system.update.process'),
(249, 'View Update Log', 'SystemController', 'admin.system.update.log');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_settings`
--

CREATE TABLE `referral_settings` (
  `id` int UNSIGNED NOT NULL,
  `level` int NOT NULL,
  `percent` decimal(5,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_attachments`
--

CREATE TABLE `support_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `support_message_id` int UNSIGNED NOT NULL DEFAULT '0',
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `support_ticket_id` int UNSIGNED NOT NULL DEFAULT '0',
  `admin_id` int UNSIGNED NOT NULL DEFAULT '0',
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT '0',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_configurations`
--

CREATE TABLE `table_configurations` (
  `id` bigint NOT NULL,
  `admin_id` int UNSIGNED NOT NULL DEFAULT '0',
  `table_name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible_columns` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `branch_id` int UNSIGNED NOT NULL DEFAULT '0',
  `branch_staff_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `post_balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx_type` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `update_logs`
--

CREATE TABLE `update_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `update_log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` int NOT NULL DEFAULT '0',
  `branch_staff_id` int NOT NULL DEFAULT '0',
  `account_number` varchar(140) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dial_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_by` int UNSIGNED NOT NULL DEFAULT '0',
  `referral_commission_count` int UNSIGNED NOT NULL DEFAULT '0',
  `balance` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'contains full address',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: sms unverified, 1: sms verified',
  `ver_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified	',
  `kyc_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kyc_rejection_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_ip` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wire_transfer_settings`
--

CREATE TABLE `wire_transfer_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `minimum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000' COMMENT 'Per transaction',
  `maximum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000' COMMENT 'Per transaction',
  `daily_maximum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `monthly_maximum_limit` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `daily_total_transaction` int UNSIGNED NOT NULL DEFAULT '0',
  `monthly_total_transaction` int UNSIGNED NOT NULL DEFAULT '0',
  `fixed_charge` decimal(28,8) UNSIGNED NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `instruction` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `method_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel,  ',
  `admin_feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `branch_id` int NOT NULL DEFAULT '0',
  `branch_staff_id` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_limit` decimal(28,8) DEFAULT '0.00000000',
  `max_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) DEFAULT '0.00000000',
  `rate` decimal(28,8) DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_configurations`
--
ALTER TABLE `api_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assign_branch_staff`
--
ALTER TABLE `assign_branch_staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `balance_transfers`
--
ALTER TABLE `balance_transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branches_name_unique` (`name`),
  ADD UNIQUE KEY `branches_code_unique` (`code`);

--
-- Indexes for table `branch_staff`
--
ALTER TABLE `branch_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_staff_email_unique` (`email`),
  ADD UNIQUE KEY `branch_staff_mobile_unique` (`mobile`);

--
-- Indexes for table `branch_staff_password_resets`
--
ALTER TABLE `branch_staff_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dps`
--
ALTER TABLE `dps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dps_number` (`dps_number`);

--
-- Indexes for table `dps_plans`
--
ALTER TABLE `dps_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fdrs`
--
ALTER TABLE `fdrs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fdr_number` (`fdr_number`);

--
-- Indexes for table `fdr_plans`
--
ALTER TABLE `fdr_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frontends`
--
ALTER TABLE `frontends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loan_number` (`loan_number`);

--
-- Indexes for table `loan_plans`
--
ALTER TABLE `loan_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operators`
--
ALTER TABLE `operators`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operator_groups`
--
ALTER TABLE `operator_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_banks`
--
ALTER TABLE `other_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `referral_settings`
--
ALTER TABLE `referral_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_attachments`
--
ALTER TABLE `support_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_configurations`
--
ALTER TABLE `table_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `update_logs`
--
ALTER TABLE `update_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wire_transfer_settings`
--
ALTER TABLE `wire_transfer_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_configurations`
--
ALTER TABLE `api_configurations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assign_branch_staff`
--
ALTER TABLE `assign_branch_staff`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `balance_transfers`
--
ALTER TABLE `balance_transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_staff`
--
ALTER TABLE `branch_staff`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branch_staff_password_resets`
--
ALTER TABLE `branch_staff_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dps`
--
ALTER TABLE `dps`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dps_plans`
--
ALTER TABLE `dps_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fdrs`
--
ALTER TABLE `fdrs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fdr_plans`
--
ALTER TABLE `fdr_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `frontends`
--
ALTER TABLE `frontends`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_plans`
--
ALTER TABLE `loan_plans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `operators`
--
ALTER TABLE `operators`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operator_groups`
--
ALTER TABLE `operator_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_banks`
--
ALTER TABLE `other_banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_verifications`
--
ALTER TABLE `otp_verifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15501;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_settings`
--
ALTER TABLE `referral_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_attachments`
--
ALTER TABLE `support_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_configurations`
--
ALTER TABLE `table_configurations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `update_logs`
--
ALTER TABLE `update_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wire_transfer_settings`
--
ALTER TABLE `wire_transfer_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
