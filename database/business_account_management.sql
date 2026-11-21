-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2026 at 09:13 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `business_account_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `parent_account_id` int UNSIGNED DEFAULT NULL,
  `account_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opening_balance_side` enum('DEBIT','CREDIT') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DEBIT',
  `is_active` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `account_categorie` enum('ASSET','LIABILITY','EQUITY','REVENUE','EXPENSE') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `company_id`, `parent_account_id`, `account_code`, `account_name`, `opening_balance`, `opening_balance_side`, `is_active`, `created_at`, `updated_at`, `account_categorie`) VALUES
(1, 1, 1000, '1002', 'gokgok', 456123.00, 'DEBIT', 1, '2026-11-12 04:34:41', '2026-11-12 04:34:41', 'LIABILITY'),
(2, 1, NULL, '452', 'rokon', 520235.00, 'CREDIT', 1, '2026-11-12 04:39:05', '2026-11-12 04:39:05', 'ASSET'),
(3, 1, NULL, '4000', 'Service revenue', 0.00, 'DEBIT', 1, '2026-11-12 06:46:41', '2026-11-12 06:46:41', 'REVENUE'),
(4, 1, NULL, '5000', 'Office expenses', 0.00, 'DEBIT', 1, '2026-11-12 06:46:41', '2026-11-12 06:46:41', 'EXPENSE'),
(5, 1, NULL, '4500', 'saklin', 45000.00, 'DEBIT', 1, '2026-11-16 05:28:43', '2026-11-16 05:28:43', 'REVENUE');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` int UNSIGNED DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int UNSIGNED NOT NULL,
  `company_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BDT',
  `financial_year_start` date DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`, `email`, `phone`, `address`, `logo`, `currency`, `financial_year_start`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pwad73', 'pwad@gmail.com', '0123456', 'Dhaka', NULL, 'BDT', '2026-11-06', 1, '2026-11-06 15:44:22', '2026-11-06 15:44:22'),
(2, 'Pwad', 'abc@gmail.com', '12302', 'Dhaka', NULL, 'BDT', '2026-11-06', 1, '2026-11-06 15:47:02', '2026-11-06 15:47:02'),
(3, 'asdf', 'asdf@gmail.com', '1205520', 'Dhaka', NULL, 'BDT', '0000-00-00', 1, '2026-11-06 15:47:46', '2026-11-06 15:47:46'),
(4, '', 'yeasinarafat@gmail.com', '', '', NULL, '', '0000-00-00', 0, '2026-11-06 16:03:22', '2026-11-06 16:03:22');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `customer_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `company_id`, `customer_name`, `phone`, `email`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Rokon', '+8801755329910', 'yeasinarafat5331@gmail.com', 'dhaka', 1, '2026-11-14 05:58:24', '2026-11-14 05:58:24');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `employee_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `basic_salary` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `login_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_role` enum('admin','accountant','employees') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `company_id`, `user_id`, `employee_name`, `phone`, `designation`, `department`, `joining_date`, `basic_salary`, `status`, `created_at`, `updated_at`, `login_email`, `login_password`, `login_role`) VALUES
(16, 1, 28, 'Saad', '01755329910', 'admin', 'admin', '2026-11-15', 500000.00, 1, '2026-11-15 04:25:37', '2026-11-15 04:25:37', 'saad@gmail.com', 'fbd6b3466f628924f9e89af9ea28ac8dbce8890b', 'admin'),
(17, 1, 29, 'Yeasin Arafat', '01755329910', 'IT', 'WEB laraval', NULL, 45000.01, 1, '2026-11-15 04:47:36', '2026-11-15 04:47:36', 'yeasinarafat5331@gmail.com', '9651b57f1c7d1d77ac1f03cd78a39ee31ea83b3a', 'employees'),
(18, 1, 30, 'rokon', '01576999127', 'As.hr', 'HR', '2026-11-02', 52000.00, 1, '2026-11-15 05:38:07', '2026-11-15 05:38:07', 'rokon@gmail.com', '892e0efdb2bd3d90d4a3ab598e8dd4b8baa74025', 'employees'),
(19, 1, 31, 'admin', '01576999127', 'admin', 'admin', '2026-11-03', 45000.00, 1, '2026-11-15 05:40:11', '2026-11-15 05:40:11', 'admin@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin'),
(21, 1, 33, 'Accountend', '01576999127', 'accountent', 'As.Accountent', '2026-11-03', 45250.00, 1, '2026-11-16 03:07:08', '2026-11-16 03:07:08', 'accountent@gmail.com', '02f7eb57679e95c8d3514c984ccb7fe92f3495fc', 'accountant');

--
-- Triggers `employees`
--
DELIMITER $$
CREATE TRIGGER `employ_delete` AFTER DELETE ON `employees` FOR EACH ROW DELETE FROM users WHERE user_id = OLD.user_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `employees_after_update` AFTER UPDATE ON `employees` FOR EACH ROW BEGIN
    IF NEW.user_id IS NOT NULL THEN
        UPDATE users
        SET name  = NEW.employee_name,
            email = COALESCE(NEW.login_email, email)
        WHERE user_id = NEW.user_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `employes_to_uaser_insart` BEFORE INSERT ON `employees` FOR EACH ROW IF NEW.user_id IS NULL AND NEW.login_email IS NOT NULL THEN
        INSERT INTO users (company_id, name, email, password, status, role)
        VALUES (
            NEW.company_id,
            LEFT(NEW.employee_name, 120),
            NEW.login_email,
            NEW.login_password,
            NEW.status,
            COALESCE(NEW.login_role, 'employees')
        );

        SET NEW.user_id = LAST_INSERT_ID();
    END IF
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `expense_category_id` int UNSIGNED NOT NULL,
  `account_id` int UNSIGNED NOT NULL COMMENT 'Cash/bank account',
  `amount` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `receipt_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_id` int UNSIGNED DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `company_id`, `expense_category_id`, `account_id`, `amount`, `expense_date`, `description`, `receipt_file`, `journal_id`, `created_by`, `created_at`) VALUES
(1, 1, 3, 2, 5000.00, '2026-11-17', '', NULL, NULL, 31, '2026-11-17 07:10:54');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `expense_category_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int UNSIGNED NOT NULL COMMENT 'Expense account'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`expense_category_id`, `company_id`, `category_name`, `account_id`) VALUES
(1, 1, 'Office expenses', 4),
(2, 1, 'Travel', 4),
(3, 1, 'Software & services', 4),
(4, 1, 'Other expense', 4);

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `income_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `income_category_id` int UNSIGNED NOT NULL,
  `account_id` int UNSIGNED NOT NULL COMMENT 'Cash/bank account',
  `amount` decimal(15,2) NOT NULL,
  `income_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `journal_id` int UNSIGNED DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`income_id`, `company_id`, `income_category_id`, `account_id`, `amount`, `income_date`, `description`, `journal_id`, `created_by`, `created_at`) VALUES
(1, 1, 3, 2, 50000.00, '2026-11-10', 'haidjfiof', NULL, NULL, '2026-11-12 06:47:24');

-- --------------------------------------------------------

--
-- Table structure for table `income_categories`
--

CREATE TABLE `income_categories` (
  `income_category_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `category_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_id` int UNSIGNED NOT NULL COMMENT 'Revenue account'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_categories`
--

INSERT INTO `income_categories` (`income_category_id`, `company_id`, `category_name`, `account_id`) VALUES
(1, 1, 'Sales income', 3),
(2, 1, 'Service income', 3),
(3, 1, 'Other income', 3);

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `journal_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `journal_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `reference_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `source_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Example: SALE, EXPENSE, PAYMENT',
  `source_id` int UNSIGNED DEFAULT NULL COMMENT 'Polymorphic source; validate in application',
  `status` enum('DRAFT','POSTED','REVERSED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_lines`
--

CREATE TABLE `journal_lines` (
  `line_id` int UNSIGNED NOT NULL,
  `journal_id` int UNSIGNED NOT NULL,
  `account_id` int UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `party_type` enum('CUSTOMER','SUPPLIER','OTHER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` int UNSIGNED DEFAULT NULL,
  `supplier_id` int UNSIGNED DEFAULT NULL,
  `account_id` int UNSIGNED NOT NULL COMMENT 'Cash/bank account',
  `payment_type` enum('RECEIPT','PAYMENT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `journal_id` int UNSIGNED DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_allocations`
--

CREATE TABLE `payment_allocations` (
  `allocation_id` int UNSIGNED NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `invoice_type` enum('SALE','PURCHASE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` int UNSIGNED NOT NULL COMMENT 'Polymorphic invoice ID; validate type and company in application',
  `allocated_amount` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payroll_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `employee_id` int UNSIGNED NOT NULL,
  `salary_month` date NOT NULL COMMENT 'Use the first day of the salary month',
  `basic_salary` decimal(15,2) NOT NULL,
  `bonus` decimal(15,2) NOT NULL DEFAULT '0.00',
  `deductions` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(15,2) NOT NULL,
  `payment_status` enum('PENDING','PAID') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `journal_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `product_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `current_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `company_id`, `product_name`, `product_code`, `purchase_price`, `selling_price`, `current_stock`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Laptop', '1021', 2500.00, 3000.00, 16.00, 1, '2026-11-14 04:27:25', '2026-11-14 06:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `purchase_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('DRAFT','POSTED','PARTIAL','PAID','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `journal_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `purchase_item_id` int UNSIGNED NOT NULL,
  `purchase_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `role_name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `company_id`, `role_name`) VALUES
(2, 1, 'accountant'),
(1, 1, 'admin'),
(3, 1, 'employees'),
(4, 1, 'HR');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `customer_id` int UNSIGNED NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_date` date NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('DRAFT','POSTED','PARTIAL','PAID','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `journal_id` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `company_id`, `customer_id`, `invoice_number`, `sale_date`, `subtotal`, `discount`, `tax_amount`, `total_amount`, `status`, `journal_id`, `created_at`) VALUES
(1, 1, 1, 'INV-2026-0001', '2026-11-14', 0.00, 200.00, 0.00, 17800.00, 'DRAFT', NULL, '2026-11-14 06:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `sales_item_id` int UNSIGNED NOT NULL,
  `sale_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`sales_item_id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `total`) VALUES
(1, 1, 1, 6.00, 3000.00, 18000.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `movement_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `movement_type` enum('IN','OUT','ADJUSTMENT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int UNSIGNED DEFAULT NULL COMMENT 'Source record; validate in application',
  `movement_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`movement_id`, `company_id`, `product_id`, `movement_type`, `quantity`, `reference_type`, `reference_id`, `movement_date`, `created_at`) VALUES
(1, 1, 1, 'ADJUSTMENT', 10.00, 'Manual adjustment', NULL, '2026-11-14', '2026-11-14 05:46:51'),
(2, 1, 1, 'ADJUSTMENT', -5.00, 'Manual adjustment', NULL, '2026-11-14', '2026-11-14 05:46:58'),
(3, 1, 1, 'OUT', 6.00, 'Sale', 1, '2026-11-14', '2026-11-14 06:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL,
  `supplier_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `company_id` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sha1',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `company_id`, `name`, `email`, `password`, `status`, `created_at`, `updated_at`) VALUES
(28, 1, 'Saad', 'saad@gmail.com', 'fbd6b3466f628924f9e89af9ea28ac8dbce8890b', 1, '2026-11-15 04:25:37', '2026-11-15 04:25:37'),
(29, 1, 'Yeasin Arafat', 'yeasinarafat5331@gmail.com', '9651b57f1c7d1d77ac1f03cd78a39ee31ea83b3a', 1, '2026-11-15 04:47:36', '2026-11-15 04:47:36'),
(30, 1, 'rokon', 'rokon@gmail.com', '892e0efdb2bd3d90d4a3ab598e8dd4b8baa74025', 1, '2026-11-15 05:38:07', '2026-11-15 05:38:07'),
(31, 1, 'admin', 'admin@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, '2026-11-15 05:40:11', '2026-11-15 05:40:11'),
(33, 1, 'Accountend', 'accountent@gmail.com', '02f7eb57679e95c8d3514c984ccb7fe92f3495fc', 1, '2026-11-16 03:07:08', '2026-11-16 03:07:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `user_id`, `role_id`) VALUES
(1, 28, 1),
(2, 29, 3),
(3, 30, 3),
(4, 31, 1),
(6, 33, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `uq_accounts_company_code` (`company_id`,`account_code`),
  ADD KEY `idx_accounts_parent` (`parent_account_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_audit_company_created` (`company_id`,`created_at`),
  ADD KEY `idx_audit_user` (`user_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `idx_customers_company` (`company_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `uq_employees_user` (`user_id`),
  ADD KEY `idx_employees_company` (`company_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `idx_expenses_company_date` (`company_id`,`expense_date`),
  ADD KEY `idx_expenses_category` (`expense_category_id`),
  ADD KEY `idx_expenses_account` (`account_id`),
  ADD KEY `idx_expenses_journal` (`journal_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`expense_category_id`),
  ADD UNIQUE KEY `uq_expense_categories_company_name` (`company_id`,`category_name`),
  ADD KEY `idx_expense_categories_account` (`account_id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`income_id`),
  ADD KEY `idx_incomes_company_date` (`company_id`,`income_date`),
  ADD KEY `idx_incomes_category` (`income_category_id`),
  ADD KEY `idx_incomes_account` (`account_id`),
  ADD KEY `idx_incomes_journal` (`journal_id`);

--
-- Indexes for table `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`income_category_id`),
  ADD UNIQUE KEY `uq_income_categories_company_name` (`company_id`,`category_name`),
  ADD KEY `idx_income_categories_account` (`account_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`journal_id`),
  ADD UNIQUE KEY `uq_journal_company_number` (`company_id`,`journal_number`),
  ADD KEY `idx_journal_company_date` (`company_id`,`transaction_date`),
  ADD KEY `idx_journal_source` (`source_type`,`source_id`),
  ADD KEY `idx_journal_created_by` (`created_by`);

--
-- Indexes for table `journal_lines`
--
ALTER TABLE `journal_lines`
  ADD PRIMARY KEY (`line_id`),
  ADD KEY `idx_journal_lines_journal` (`journal_id`),
  ADD KEY `idx_journal_lines_account` (`account_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_payments_company_date` (`company_id`,`payment_date`),
  ADD KEY `idx_payments_customer` (`customer_id`),
  ADD KEY `idx_payments_supplier` (`supplier_id`),
  ADD KEY `idx_payments_account` (`account_id`),
  ADD KEY `idx_payments_journal` (`journal_id`);

--
-- Indexes for table `payment_allocations`
--
ALTER TABLE `payment_allocations`
  ADD PRIMARY KEY (`allocation_id`),
  ADD KEY `idx_payment_allocations_payment` (`payment_id`),
  ADD KEY `idx_payment_allocations_invoice` (`invoice_type`,`invoice_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`),
  ADD UNIQUE KEY `uq_payroll_employee_month` (`employee_id`,`salary_month`),
  ADD KEY `idx_payroll_company` (`company_id`),
  ADD KEY `idx_payroll_journal` (`journal_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `uq_products_company_code` (`company_id`,`product_code`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`purchase_id`),
  ADD UNIQUE KEY `uq_purchases_company_invoice` (`company_id`,`invoice_number`),
  ADD KEY `idx_purchases_supplier_date` (`supplier_id`,`purchase_date`),
  ADD KEY `idx_purchases_journal` (`journal_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`purchase_item_id`),
  ADD KEY `idx_purchase_items_purchase` (`purchase_id`),
  ADD KEY `idx_purchase_items_product` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `uq_roles_company_name` (`company_id`,`role_name`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD UNIQUE KEY `uq_sales_company_invoice` (`company_id`,`invoice_number`),
  ADD KEY `idx_sales_customer_date` (`customer_id`,`sale_date`),
  ADD KEY `idx_sales_journal` (`journal_id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`sales_item_id`),
  ADD KEY `idx_sales_items_sale` (`sale_id`),
  ADD KEY `idx_sales_items_product` (`product_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`movement_id`),
  ADD KEY `idx_stock_company` (`company_id`),
  ADD KEY `idx_stock_product_date` (`product_id`,`movement_date`),
  ADD KEY `idx_stock_reference` (`reference_type`,`reference_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD KEY `idx_suppliers_company` (`company_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uq_users_company_email` (`company_id`,`email`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`),
  ADD UNIQUE KEY `uq_user_role` (`user_id`,`role_id`),
  ADD KEY `idx_user_roles_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `expense_category_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `income_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `income_category_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `journal_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_lines`
--
ALTER TABLE `journal_lines`
  MODIFY `line_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_allocations`
--
ALTER TABLE `payment_allocations`
  MODIFY `allocation_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payroll_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `purchase_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `purchase_item_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `sales_item_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `movement_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `user_role_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employees_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
