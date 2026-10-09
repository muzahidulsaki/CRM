-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 05, 2026 at 01:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projukti_sheba_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `record_type` varchar(255) DEFAULT NULL,
  `record_id` bigint(20) UNSIGNED DEFAULT NULL,
  `record_title` varchar(255) DEFAULT NULL,
  `previous_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`previous_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `description` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(80) NOT NULL,
  `secret_key` varchar(64) DEFAULT NULL,
  `allowed_domain` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('projukti-sheba-crm-cache-setting.lead_sources', 'a:9:{i:0;s:12:\"Website Form\";i:1;s:8:\"WhatsApp\";i:2;s:22:\"Facebook/Instagram Ads\";i:3;s:11:\"Google Maps\";i:4;s:13:\"Google Search\";i:5;s:7:\"YouTube\";i:6;s:8:\"LinkedIn\";i:7;s:8:\"Referral\";i:8;s:12:\"Manual Entry\";}', 2106568402),
('projukti-sheba-crm-cache-setting.service_categories', 'a:8:{i:0;s:20:\"Software Development\";i:1;s:19:\"Website Development\";i:2;s:24:\"Creative & Video Editing\";i:3;s:15:\"Graphics Design\";i:4;s:17:\"Digital Marketing\";i:5;s:9:\"SMO & SMM\";i:6;s:12:\"Ads Campaign\";i:7;s:6:\"Others\";}', 2106568402);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_payments`
--

CREATE TABLE `client_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_no` varchar(50) NOT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` enum('Cash','Bank Transfer','bKash','Nagad','Rocket','Card','Cheque','Other') NOT NULL DEFAULT 'Bank Transfer',
  `transaction_reference` varchar(255) DEFAULT NULL,
  `received_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `crm_notifications`
--

CREATE TABLE `crm_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'medium',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_code` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `normalized_phone` varchar(20) NOT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `normalized_whatsapp` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Bangladesh',
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `employment_type` enum('permanent','contractual','freelance') NOT NULL DEFAULT 'permanent',
  `joining_date` date DEFAULT NULL,
  `payment_type` enum('monthly_salary','per_second_video','per_task','hourly') NOT NULL DEFAULT 'monthly_salary',
  `salary_or_rate` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Monthly salary amount or per-second video rate / per-task rate',
  `payment_schedule` varchar(255) NOT NULL DEFAULT 'monthly' COMMENT 'monthly, weekly, per_work',
  `emergency_contact` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` enum('active','inactive','on_leave') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_payouts`
--

CREATE TABLE `employee_payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(30) NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `payout_type` enum('monthly_salary','video_billing','task_contract','hourly_work','advance','bonus') NOT NULL DEFAULT 'monthly_salary',
  `salary_month` varchar(7) DEFAULT NULL COMMENT 'Format: YYYY-MM for monthly salary',
  `base_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `bonus_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `deduction_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(14,2) NOT NULL DEFAULT 0.00 COMMENT 'base + bonus - deduction',
  `paid_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('Cash','bKash','Nagad','Rocket','Bank Transfer') NOT NULL DEFAULT 'bKash',
  `account_details` varchar(255) DEFAULT NULL COMMENT 'Mobile number or Bank Acc no',
  `transaction_reference` varchar(255) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `status` enum('Draft','Paid','Cancelled') NOT NULL DEFAULT 'Paid',
  `linked_video_billing_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Array of video_billing IDs cleared' CHECK (json_valid(`linked_video_billing_ids`)),
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_no` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'Other',
  `title` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` enum('Cash','Bank Transfer','bKash','Nagad','Rocket','Card','Other') NOT NULL DEFAULT 'Cash',
  `paid_to` varchar(255) DEFAULT NULL,
  `paid_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recurring_frequency` enum('Monthly','Quarterly','Yearly') DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Paid','Pending','Overdue') NOT NULL DEFAULT 'Paid',
  `attachment_path` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` enum('fixed','percentage') NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('Draft','Sent','Partially Paid','Paid','Overdue','Cancelled') NOT NULL DEFAULT 'Draft',
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `service_category` varchar(255) DEFAULT NULL,
  `item_description` varchar(255) NOT NULL,
  `quantity` decimal(8,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_code` varchar(30) NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `normalized_phone` varchar(20) NOT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `normalized_whatsapp` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Bangladesh',
  `website` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `other_socials` text DEFAULT NULL,
  `lead_source` varchar(255) NOT NULL DEFAULT 'Manual Entry',
  `interested_service` varchar(255) DEFAULT NULL,
  `budget` decimal(14,2) DEFAULT NULL,
  `discussed_amount` decimal(14,2) DEFAULT NULL,
  `lead_temperature` enum('Hot','Warm','Cold') NOT NULL DEFAULT 'Warm',
  `lead_status` enum('New','Contacted','Interested','Meeting/Demo','Proposal Sent','Negotiation','Won','Lost') NOT NULL DEFAULT 'New',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `expected_work_date` date DEFAULT NULL,
  `last_contact_date` datetime DEFAULT NULL,
  `next_followup_date` datetime DEFAULT NULL,
  `lost_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_follow_ups`
--

CREATE TABLE `lead_follow_ups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('phone_call','whatsapp','email','meeting','internal_note') NOT NULL DEFAULT 'phone_call',
  `scheduled_at` datetime DEFAULT NULL,
  `conducted_at` datetime DEFAULT NULL,
  `status` enum('scheduled','completed','missed','cancelled') NOT NULL DEFAULT 'scheduled',
  `subject` varchar(255) DEFAULT NULL,
  `notes` text NOT NULL,
  `next_action` varchar(255) DEFAULT NULL,
  `next_followup_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2026_10_01_000001_create_roles_and_permissions_tables', 1),
(5, '2026_10_01_000002_add_role_and_fields_to_users_table', 1),
(6, '2026_10_01_000003_create_employees_table', 1),
(7, '2026_10_01_000004_create_settings_table', 1),
(8, '2026_10_01_000005_create_activity_logs_table', 1),
(9, '2026_10_02_000001_create_customers_table', 1),
(10, '2026_10_02_000002_create_leads_table', 1),
(11, '2026_10_02_000003_create_lead_follow_ups_table', 1),
(12, '2026_10_02_000004_create_quotations_and_items_tables', 1),
(13, '2026_10_02_000005_create_orders_table', 1),
(14, '2026_10_02_000006_create_projects_table', 1),
(15, '2026_10_02_000007_create_tasks_table', 1),
(16, '2026_10_02_000008_create_video_billings_table', 1),
(17, '2026_10_02_000009_create_worklogs_table', 1),
(18, '2026_10_02_000010_create_employee_payouts_table', 1),
(19, '2026_10_02_000011_create_support_tickets_table', 1),
(20, '2026_10_02_000012_create_ticket_replies_table', 1),
(21, '2026_10_02_000013_create_invoices_and_items_tables', 1),
(22, '2026_10_02_000014_create_client_payments_table', 1),
(23, '2026_10_02_000015_create_service_renewals_table', 1),
(24, '2026_10_02_000016_create_expenses_table', 1),
(25, '2026_10_05_000001_create_api_tokens_and_automation_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(30) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_person_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_category` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `payment_terms` text DEFAULT NULL,
  `order_date` date NOT NULL,
  `start_date` date DEFAULT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `status` enum('Pending','Confirmed','Project Started','Working','Under Review','Completed','Delivered','Cancelled') NOT NULL DEFAULT 'Confirmed',
  `notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `slug` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES
(1, 'View Leads', 'leads.view', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(2, 'Create Leads', 'leads.create', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(3, 'Edit Leads', 'leads.edit', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(4, 'Delete Leads', 'leads.delete', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(5, 'Assign Leads', 'leads.assign', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(6, 'View Customers', 'customers.view', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(7, 'Manage Customers', 'customers.manage', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(8, 'Create Quotations', 'quotations.create', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(9, 'Manage Quotations', 'quotations.manage', 'crm', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(10, 'View Orders', 'orders.view', 'projects', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(11, 'Create Orders', 'orders.create', 'projects', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(12, 'View Projects', 'projects.view', 'projects', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(13, 'Manage Projects', 'projects.manage', 'projects', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(14, 'View Tasks', 'tasks.view', 'projects', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(15, 'Manage Tasks', 'tasks.manage', 'projects', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(16, 'Perform QA', 'tasks.qa', 'projects', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(17, 'View Employees', 'employees.view', 'team', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(18, 'Manage Employees', 'employees.manage', 'team', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(19, 'Log Work', 'work_logs.create', 'team', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(20, 'View Video Billing', 'video_billing.view', 'team', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(21, 'Manage Video Billing', 'video_billing.manage', 'team', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(22, 'View Support Tickets', 'tickets.view', 'support', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(23, 'Manage Support Tickets', 'tickets.manage', 'support', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(24, 'Resolve Support Tickets', 'tickets.resolve', 'support', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(25, 'View Payments', 'payments.view', 'finance', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(26, 'Record Payments', 'payments.record', 'finance', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(27, 'Manage Expenses', 'expenses.manage', 'finance', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(28, 'Manage Renewals', 'renewals.manage', 'finance', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(29, 'View Financial Reports', 'reports.financial', 'finance', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(30, 'Manage Settings', 'settings.manage', 'settings', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_code` varchar(30) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('Pending','Planning','In Development','In QA','Client Review','Completed','Delivered','Delayed','Cancelled') NOT NULL DEFAULT 'Pending',
  `progress_percent` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `git_repository` varchar(255) DEFAULT NULL,
  `live_url` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_code` varchar(30) NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `quotation_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `status` enum('Draft','Sent','Negotiation','Accepted','Rejected','Expired') NOT NULL DEFAULT 'Draft',
  `subtotal` decimal(14,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `payment_terms` text DEFAULT NULL,
  `delivery_terms` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `converted_to_order_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED NOT NULL,
  `service_category` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'Full system access and control.', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(2, 'Marketing & Sales Manager', 'sales_manager', 'Manages leads, follow-ups, quotations, sales, and customers.', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(3, 'Project Manager', 'project_manager', 'Manages orders, projects, tasks, team allocation, QA and delivery.', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(4, 'Web Developer', 'web_developer', 'Works on assigned web projects, tasks, submits work logs and fixes tickets.', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(5, 'Mobile App Developer', 'mobile_app_developer', 'Works on assigned mobile app projects, tasks, and work logs.', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(6, 'Video Editor', 'video_editor', 'Handles video production, tracks duration in seconds, logs and submissions.', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(7, 'Graphics Designer', 'graphics_designer', 'Handles creative branding, UI/UX, and graphic design tasks.', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(8, 'Operator', 'operator', 'General data entry, lead logging, and customer support assistance.', '2026-10-05 07:52:36', '2026-10-05 07:52:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(9, 1, 9, NULL, NULL),
(10, 1, 10, NULL, NULL),
(11, 1, 11, NULL, NULL),
(12, 1, 12, NULL, NULL),
(13, 1, 13, NULL, NULL),
(14, 1, 14, NULL, NULL),
(15, 1, 15, NULL, NULL),
(16, 1, 16, NULL, NULL),
(17, 1, 17, NULL, NULL),
(18, 1, 18, NULL, NULL),
(19, 1, 19, NULL, NULL),
(20, 1, 20, NULL, NULL),
(21, 1, 21, NULL, NULL),
(22, 1, 22, NULL, NULL),
(23, 1, 23, NULL, NULL),
(24, 1, 24, NULL, NULL),
(25, 1, 25, NULL, NULL),
(26, 1, 26, NULL, NULL),
(27, 1, 27, NULL, NULL),
(28, 1, 28, NULL, NULL),
(29, 1, 29, NULL, NULL),
(30, 1, 30, NULL, NULL),
(31, 2, 7, NULL, NULL),
(32, 2, 6, NULL, NULL),
(33, 2, 5, NULL, NULL),
(34, 2, 2, NULL, NULL),
(35, 2, 3, NULL, NULL),
(36, 2, 1, NULL, NULL),
(37, 2, 11, NULL, NULL),
(38, 2, 10, NULL, NULL),
(39, 2, 26, NULL, NULL),
(40, 2, 8, NULL, NULL),
(41, 2, 9, NULL, NULL),
(42, 3, 17, NULL, NULL),
(43, 3, 10, NULL, NULL),
(44, 3, 13, NULL, NULL),
(45, 3, 12, NULL, NULL),
(46, 3, 15, NULL, NULL),
(47, 3, 16, NULL, NULL),
(48, 3, 14, NULL, NULL),
(49, 3, 23, NULL, NULL),
(50, 3, 24, NULL, NULL),
(51, 3, 22, NULL, NULL),
(52, 3, 21, NULL, NULL),
(53, 3, 20, NULL, NULL),
(54, 3, 19, NULL, NULL),
(55, 4, 12, NULL, NULL),
(56, 4, 14, NULL, NULL),
(57, 4, 24, NULL, NULL),
(58, 4, 22, NULL, NULL),
(59, 4, 19, NULL, NULL),
(60, 5, 12, NULL, NULL),
(61, 5, 14, NULL, NULL),
(62, 5, 24, NULL, NULL),
(63, 5, 22, NULL, NULL),
(64, 5, 19, NULL, NULL),
(65, 6, 14, NULL, NULL),
(66, 6, 20, NULL, NULL),
(67, 6, 19, NULL, NULL),
(68, 7, 14, NULL, NULL),
(69, 7, 19, NULL, NULL),
(70, 8, 6, NULL, NULL),
(71, 8, 2, NULL, NULL),
(72, 8, 1, NULL, NULL),
(73, 8, 22, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_renewals`
--

CREATE TABLE `service_renewals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `renewal_no` varchar(50) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_name` varchar(255) NOT NULL,
  `category` enum('Domain','Hosting','VPS/Server','Software/ERP','Maintenance','Subscription','SSL Certificate','Other') NOT NULL DEFAULT 'Domain',
  `billing_cycle` enum('Monthly','Quarterly','Half-Yearly','Yearly','Biennial','Custom') NOT NULL DEFAULT 'Yearly',
  `renewal_fee` decimal(12,2) NOT NULL,
  `last_payment_date` date DEFAULT NULL,
  `next_due_date` date NOT NULL,
  `reminder_days` int(11) NOT NULL DEFAULT 7,
  `status` enum('Active','Upcoming Due','Due Today','Overdue','Suspended','Cancelled') NOT NULL DEFAULT 'Active',
  `auto_renew` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aSzhFcdXGksjlsHOcG8jN6HCTLDpDvUbYz9ihcNH', 1, '127.0.0.1', 'Symfony', 'eyJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3Rva2VuIjoiQ3ZGd3NtTFV4Yjd2aEJSTEkwQWdLTUcxQWZIOU9jNXh1VDhhZ3Y4ZSIsInBhc3N3b3JkX2hhc2hfd2ViIjoiNGVmZmZjN2QzNGU1N2ZkNWIwNWRkYTVmYjczYjU2N2Y1NGRlMDdlMGU0MjcwMDhiNjc2NWRmMjI3N2Q2NmY3YyIsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1791208402),
('CxvI2jIZriPUmqDUwarP7doq9ffIDnxOX4Y3Dkp5', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJtN1M4N3hxWlo3VDRMNTM4RkRETmVMRThJWExXQTBPTHV0MFJNRk1KIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1791208394),
('FM97AUXWedONyCVDKmw3dpSIyb9szTe7eRbkSVEI', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJScXB1NHB0ZG16Q3pYSHhHS0tNYmJvZnVGUjgzSVVxQ2F2VFRoWDRlIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zdXBwb3J0Iiwicm91dGUiOiJwdWJsaWMtc3VwcG9ydC5jcmVhdGUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1791208395),
('knIBbuy1rliuciD0oir0EnqwAWNJKGSZ5ehMAkXF', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJlMDZ2c2JkTGFzZnVGcVZJd2MxOFRvbXZkS3dtTlMydGhJV3lqZ0M2IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1791208395),
('mNCP6cycwEHmyHtYG5golybrQr5mttfkjJsuLdXZ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIyajV5cjdkZm5JUGJCVHJaVXFOVzVvS0RheVpRSlVWd2xPNnY1TXZVIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6IjRlZmZmYzdkMzRlNTdmZDViMDVkZGE1ZmI3M2I1NjdmNTRkZTA3ZTBlNDI3MDA4YjY3NjVkZjIyNzdkNjZmN2MifQ==', 1791208423),
('Z7fjUo6YeevjyhmJARR44v7cHkIaGVkBXQUldSsM', NULL, '127.0.0.1', '', 'eyJfdG9rZW4iOiJXdlp2V3BmRnFZcFpEOVRkTFZrdUY0VjNvWVduOWRRaExBQThOWXoyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zdXBwb3J0XC90cmFjayIsInJvdXRlIjoicHVibGljLXN1cHBvcnQudHJhY2sifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1791208395);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string' COMMENT 'string, json, boolean, integer, decimal',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group`, `key`, `value`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'company', 'company_name', 'Projukti Sheba', 'string', 'Company Name', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(2, 'company', 'company_phone', '+8801700000000', 'string', 'Primary Phone', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(3, 'company', 'company_email', 'info@projuktisheba.com', 'string', 'Primary Email', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(4, 'general', 'currency_symbol', '৳', 'string', 'Default Currency Symbol', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(5, 'general', 'currency_code', 'BDT', 'string', 'Default Currency Code', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(6, 'general', 'timezone', 'Asia/Dhaka', 'string', 'Application Timezone', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(7, 'finance', 'default_video_rate_per_sec', '8.00', 'decimal', 'Default rate per second for video editing in BDT', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(8, 'crm', 'lead_sources', '[\"Website Form\",\"WhatsApp\",\"Facebook\\/Instagram Ads\",\"Google Maps\",\"Google Search\",\"YouTube\",\"LinkedIn\",\"Referral\",\"Manual Entry\"]', 'json', 'Available lead sources', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(9, 'sales', 'service_categories', '[\"Software Development\",\"Website Development\",\"Creative & Video Editing\",\"Graphics Design\",\"Digital Marketing\",\"SMO & SMM\",\"Ads Campaign\",\"Others\"]', 'json', 'Main service categories', '2026-10-05 07:52:36', '2026-10-05 07:52:36'),
(10, 'finance', 'payment_methods', '[\"Cash\",\"Bank Transfer\",\"bKash\",\"Nagad\",\"Rocket\",\"Card\\/Online\"]', 'json', 'Supported payment channels', '2026-10-05 07:52:36', '2026-10-05 07:52:36');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_no` varchar(50) NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `service_url` varchar(255) DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `issue_type` varchar(255) NOT NULL DEFAULT 'Technical Support',
  `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Medium',
  `status` enum('New','Assigned','Working','Reworking','Testing','Resolved','Client Confirmed','Closed','Reopened') NOT NULL DEFAULT 'New',
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `assigned_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_developer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `estimated_hours` decimal(8,2) DEFAULT NULL,
  `actual_hours` decimal(8,2) DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_code` varchar(30) NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `task_type` enum('Development','Video Editing','Graphics Design','Marketing Campaign','QA / Testing','Bug Fix','Other') NOT NULL DEFAULT 'Development',
  `priority` enum('Low','Medium','High','Urgent') NOT NULL DEFAULT 'Medium',
  `status` enum('Pending','Working','Reworking','Completed','Checked','Delivered') NOT NULL DEFAULT 'Pending',
  `estimated_hours` decimal(8,2) DEFAULT NULL,
  `actual_hours` decimal(8,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `submission_notes` text DEFAULT NULL,
  `submission_url` varchar(255) DEFAULT NULL,
  `submitted_at` datetime DEFAULT NULL,
  `qa_by` bigint(20) UNSIGNED DEFAULT NULL,
  `qa_notes` text DEFAULT NULL,
  `qa_status` enum('Pending','Passed','Failed') NOT NULL DEFAULT 'Pending',
  `checked_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_replies`
--

CREATE TABLE `ticket_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sender_type` enum('Client','Agent','Developer','System') NOT NULL DEFAULT 'Agent',
  `sender_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `avatar`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Projukti Sheba Admin', 'admin@projuktisheba.com', '+8801700000000', NULL, 'active', '2026-10-05 07:52:36', '$2y$12$ALIhHl15JjqBSfwpwRzPuO84GeDMU8.DtOJtp1rpSsCtuBWhgsMJa', NULL, '2026-10-05 07:52:36', '2026-10-05 07:52:36');

-- --------------------------------------------------------

--
-- Table structure for table `video_billings`
--

CREATE TABLE `video_billings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `billing_code` varchar(30) NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `video_title` varchar(255) NOT NULL,
  `video_url` varchar(255) DEFAULT NULL COMMENT 'YouTube / Drive / Deliverable link',
  `duration_minutes` int(11) NOT NULL DEFAULT 0,
  `duration_seconds` int(11) NOT NULL DEFAULT 0,
  `total_seconds` int(11) NOT NULL DEFAULT 0 COMMENT 'Calculated: (minutes * 60) + seconds',
  `rate_per_second` decimal(10,2) NOT NULL DEFAULT 8.00 COMMENT 'Rate in BDT per second',
  `number_of_videos` int(11) NOT NULL DEFAULT 1 COMMENT 'Video quantity count',
  `total_payable` decimal(14,2) NOT NULL DEFAULT 0.00 COMMENT 'total_seconds * rate_per_second * number_of_videos',
  `paid_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `work_start_date` date DEFAULT NULL,
  `work_end_date` date DEFAULT NULL,
  `status` enum('Draft','In Progress','Submitted','Approved','Rejected') NOT NULL DEFAULT 'Submitted',
  `payment_status` enum('Unpaid','Partially Paid','Paid') NOT NULL DEFAULT 'Unpaid',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `webhook_logs`
--

CREATE TABLE `webhook_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(50) NOT NULL DEFAULT 'whatsapp',
  `event_type` varchar(50) DEFAULT NULL,
  `external_id` varchar(150) DEFAULT NULL,
  `sender_phone` varchar(50) DEFAULT NULL,
  `normalized_phone` varchar(50) DEFAULT NULL,
  `sender_name` varchar(150) DEFAULT NULL,
  `message_body` text DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `status` varchar(30) NOT NULL DEFAULT 'processed',
  `response_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worklogs`
--

CREATE TABLE `worklogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `log_date` date NOT NULL,
  `hours_worked` decimal(5,2) NOT NULL DEFAULT 0.00,
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `billable_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `work_summary` text NOT NULL,
  `status` enum('Logged','Approved','Rejected') NOT NULL DEFAULT 'Logged',
  `payment_status` enum('Unpaid','Paid') NOT NULL DEFAULT 'Unpaid',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_module_record_id_index` (`module`,`record_id`),
  ADD KEY `activity_logs_user_id_index` (`user_id`);

--
-- Indexes for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_tokens_token_unique` (`token`),
  ADD KEY `api_tokens_created_by_foreign` (`created_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `client_payments`
--
ALTER TABLE `client_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_payments_payment_no_unique` (`payment_no`),
  ADD KEY `client_payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `client_payments_order_id_foreign` (`order_id`),
  ADD KEY `client_payments_customer_id_foreign` (`customer_id`),
  ADD KEY `client_payments_received_by_id_foreign` (`received_by_id`);

--
-- Indexes for table `crm_notifications`
--
ALTER TABLE `crm_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crm_notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_customer_code_unique` (`customer_code`),
  ADD KEY `customers_created_by_foreign` (`created_by`),
  ADD KEY `customers_email_index` (`email`),
  ADD KEY `customers_phone_index` (`phone`),
  ADD KEY `customers_normalized_phone_index` (`normalized_phone`),
  ADD KEY `customers_normalized_whatsapp_index` (`normalized_whatsapp`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`);

--
-- Indexes for table `employee_payouts`
--
ALTER TABLE `employee_payouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_payouts_voucher_no_unique` (`voucher_no`),
  ADD KEY `employee_payouts_employee_id_foreign` (`employee_id`),
  ADD KEY `employee_payouts_created_by_foreign` (`created_by`),
  ADD KEY `employee_payouts_payout_type_index` (`payout_type`),
  ADD KEY `employee_payouts_status_index` (`status`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expenses_expense_no_unique` (`expense_no`),
  ADD KEY `expenses_paid_by_id_foreign` (`paid_by_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`),
  ADD KEY `invoices_customer_id_foreign` (`customer_id`),
  ADD KEY `invoices_order_id_foreign` (`order_id`),
  ADD KEY `invoices_project_id_foreign` (`project_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

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
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `leads_lead_code_unique` (`lead_code`),
  ADD KEY `leads_customer_id_foreign` (`customer_id`),
  ADD KEY `leads_assigned_to_foreign` (`assigned_to`),
  ADD KEY `leads_created_by_foreign` (`created_by`),
  ADD KEY `leads_email_index` (`email`),
  ADD KEY `leads_phone_index` (`phone`),
  ADD KEY `leads_normalized_phone_index` (`normalized_phone`),
  ADD KEY `leads_normalized_whatsapp_index` (`normalized_whatsapp`),
  ADD KEY `leads_lead_source_index` (`lead_source`),
  ADD KEY `leads_interested_service_index` (`interested_service`),
  ADD KEY `leads_lead_temperature_index` (`lead_temperature`),
  ADD KEY `leads_lead_status_index` (`lead_status`),
  ADD KEY `leads_next_followup_date_index` (`next_followup_date`);

--
-- Indexes for table `lead_follow_ups`
--
ALTER TABLE `lead_follow_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_follow_ups_lead_id_foreign` (`lead_id`),
  ADD KEY `lead_follow_ups_customer_id_foreign` (`customer_id`),
  ADD KEY `lead_follow_ups_user_id_foreign` (`user_id`),
  ADD KEY `lead_follow_ups_type_index` (`type`),
  ADD KEY `lead_follow_ups_scheduled_at_index` (`scheduled_at`),
  ADD KEY `lead_follow_ups_status_index` (`status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`),
  ADD KEY `orders_quotation_id_foreign` (`quotation_id`),
  ADD KEY `orders_lead_id_foreign` (`lead_id`),
  ADD KEY `orders_sales_person_id_foreign` (`sales_person_id`),
  ADD KEY `orders_project_manager_id_foreign` (`project_manager_id`),
  ADD KEY `orders_service_category_index` (`service_category`),
  ADD KEY `orders_order_date_index` (`order_date`),
  ADD KEY `orders_expected_delivery_date_index` (`expected_delivery_date`),
  ADD KEY `orders_status_index` (`status`);

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
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_project_code_unique` (`project_code`),
  ADD KEY `projects_order_id_foreign` (`order_id`),
  ADD KEY `projects_customer_id_foreign` (`customer_id`),
  ADD KEY `projects_project_manager_id_foreign` (`project_manager_id`),
  ADD KEY `projects_category_index` (`category`),
  ADD KEY `projects_deadline_index` (`deadline`),
  ADD KEY `projects_status_index` (`status`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_quotation_code_unique` (`quotation_code`),
  ADD KEY `quotations_lead_id_foreign` (`lead_id`),
  ADD KEY `quotations_customer_id_foreign` (`customer_id`),
  ADD KEY `quotations_created_by_foreign` (`created_by`),
  ADD KEY `quotations_status_index` (`status`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_items_quotation_id_foreign` (`quotation_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permission_role_id_permission_id_unique` (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `service_renewals`
--
ALTER TABLE `service_renewals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_renewals_renewal_no_unique` (`renewal_no`),
  ADD KEY `service_renewals_customer_id_foreign` (`customer_id`),
  ADD KEY `service_renewals_project_id_foreign` (`project_id`),
  ADD KEY `service_renewals_next_due_date_index` (`next_due_date`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `support_tickets_ticket_no_unique` (`ticket_no`),
  ADD KEY `support_tickets_customer_id_foreign` (`customer_id`),
  ADD KEY `support_tickets_order_id_foreign` (`order_id`),
  ADD KEY `support_tickets_project_id_foreign` (`project_id`),
  ADD KEY `support_tickets_assigned_manager_id_foreign` (`assigned_manager_id`),
  ADD KEY `support_tickets_assigned_developer_id_foreign` (`assigned_developer_id`),
  ADD KEY `support_tickets_email_index` (`email`),
  ADD KEY `support_tickets_phone_index` (`phone`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tasks_task_code_unique` (`task_code`),
  ADD KEY `tasks_project_id_foreign` (`project_id`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_assigned_by_foreign` (`assigned_by`),
  ADD KEY `tasks_qa_by_foreign` (`qa_by`),
  ADD KEY `tasks_task_type_index` (`task_type`),
  ADD KEY `tasks_priority_index` (`priority`),
  ADD KEY `tasks_status_index` (`status`),
  ADD KEY `tasks_deadline_index` (`deadline`),
  ADD KEY `tasks_qa_status_index` (`qa_status`);

--
-- Indexes for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_replies_support_ticket_id_foreign` (`support_ticket_id`),
  ADD KEY `ticket_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `video_billings`
--
ALTER TABLE `video_billings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `video_billings_billing_code_unique` (`billing_code`),
  ADD KEY `video_billings_employee_id_foreign` (`employee_id`),
  ADD KEY `video_billings_project_id_foreign` (`project_id`),
  ADD KEY `video_billings_task_id_foreign` (`task_id`),
  ADD KEY `video_billings_customer_id_foreign` (`customer_id`),
  ADD KEY `video_billings_approved_by_foreign` (`approved_by`),
  ADD KEY `video_billings_created_by_foreign` (`created_by`),
  ADD KEY `video_billings_status_index` (`status`),
  ADD KEY `video_billings_payment_status_index` (`payment_status`);

--
-- Indexes for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `webhook_logs_external_id_index` (`external_id`),
  ADD KEY `webhook_logs_sender_phone_index` (`sender_phone`),
  ADD KEY `webhook_logs_normalized_phone_index` (`normalized_phone`);

--
-- Indexes for table `worklogs`
--
ALTER TABLE `worklogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worklogs_employee_id_foreign` (`employee_id`),
  ADD KEY `worklogs_task_id_foreign` (`task_id`),
  ADD KEY `worklogs_project_id_foreign` (`project_id`),
  ADD KEY `worklogs_approved_by_foreign` (`approved_by`),
  ADD KEY `worklogs_status_index` (`status`),
  ADD KEY `worklogs_payment_status_index` (`payment_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `api_tokens`
--
ALTER TABLE `api_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_payments`
--
ALTER TABLE `client_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `crm_notifications`
--
ALTER TABLE `crm_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_payouts`
--
ALTER TABLE `employee_payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_follow_ups`
--
ALTER TABLE `lead_follow_ups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role_permission`
--
ALTER TABLE `role_permission`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `service_renewals`
--
ALTER TABLE `service_renewals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `video_billings`
--
ALTER TABLE `video_billings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `webhook_logs`
--
ALTER TABLE `webhook_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worklogs`
--
ALTER TABLE `worklogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD CONSTRAINT `api_tokens_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `client_payments`
--
ALTER TABLE `client_payments`
  ADD CONSTRAINT `client_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `client_payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `client_payments_received_by_id_foreign` FOREIGN KEY (`received_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `crm_notifications`
--
ALTER TABLE `crm_notifications`
  ADD CONSTRAINT `crm_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_payouts`
--
ALTER TABLE `employee_payouts`
  ADD CONSTRAINT `employee_payouts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_payouts_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_paid_by_id_foreign` FOREIGN KEY (`paid_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lead_follow_ups`
--
ALTER TABLE `lead_follow_ups`
  ADD CONSTRAINT `lead_follow_ups_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `lead_follow_ups_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_follow_ups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_sales_person_id_foreign` FOREIGN KEY (`sales_person_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotations_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_renewals`
--
ALTER TABLE `service_renewals`
  ADD CONSTRAINT `service_renewals_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_renewals_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_assigned_developer_id_foreign` FOREIGN KEY (`assigned_developer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_assigned_manager_id_foreign` FOREIGN KEY (`assigned_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_qa_by_foreign` FOREIGN KEY (`qa_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ticket_replies`
--
ALTER TABLE `ticket_replies`
  ADD CONSTRAINT `ticket_replies_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `video_billings`
--
ALTER TABLE `video_billings`
  ADD CONSTRAINT `video_billings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `video_billings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `video_billings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `video_billings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `video_billings_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `video_billings_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `worklogs`
--
ALTER TABLE `worklogs`
  ADD CONSTRAINT `worklogs_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `worklogs_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `worklogs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `worklogs_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
