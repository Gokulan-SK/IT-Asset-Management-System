-- Asset Management System Database Schema
-- Run this script to create the necessary tables

-- Create database
CREATE DATABASE IF NOT EXISTS `asset_management` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `asset_management`;

-- Employee table
CREATE TABLE IF NOT EXISTS `employee` (
  `emp_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `phone` varchar(10) NOT NULL UNIQUE,
  `dob` date NOT NULL,
  `designation` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `password_hash` varchar(255) NOT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`emp_id`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Asset table
CREATE TABLE IF NOT EXISTS `asset` (
  `asset_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `subcategory` varchar(50) DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `serial_number` varchar(100) UNIQUE DEFAULT NULL,
  `license_key` varchar(255) DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `warranty_period` int(11) DEFAULT NULL COMMENT 'Warranty period in months',
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `asset_status` varchar(50) NOT NULL DEFAULT 'in_storage',
  `asset_condition` varchar(50) NOT NULL DEFAULT 'good',
  `notes` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`asset_id`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`asset_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Asset Ledger table for tracking asset assignments
CREATE TABLE IF NOT EXISTS `asset_ledger` (
  `ledger_id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `assigned_by` int(11) NOT NULL,
  `check_out_date` date NOT NULL,
  `check_in_date` date DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ledger_id`),
  KEY `idx_asset_id` (`asset_id`),
  KEY `idx_emp_id` (`emp_id`),
  KEY `idx_assigned_by` (`assigned_by`),
  KEY `idx_check_out_date` (`check_out_date`),
  CONSTRAINT `fk_ledger_asset` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`asset_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ledger_employee` FOREIGN KEY (`emp_id`) REFERENCES `employee` (`emp_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ledger_assigned_by` FOREIGN KEY (`assigned_by`) REFERENCES `employee` (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
INSERT INTO `employee` (`username`, `first_name`, `last_name`, `email`, `phone`, `dob`, `designation`, `is_admin`, `password_hash`) 
VALUES ('admin', 'System', 'Administrator', 'admin@company.com', '9999999999', '1990-01-01', 'System Admin', 1, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi') 
ON DUPLICATE KEY UPDATE `username` = `username`;
-- Default password is 'password'
