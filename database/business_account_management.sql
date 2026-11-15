-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 15, 2026 at 11:57 AM
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
(19, 1, 31, 'admin', '01576999127', 'admin', 'admin', '2026-11-03', 45000.00, 1, '2026-11-15 05:40:11', '2026-11-15 05:40:11', 'admin@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin');

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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `uq_employees_user` (`user_id`),
  ADD KEY `idx_employees_company` (`company_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employees_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
