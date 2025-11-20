-- Restaurant database schema
CREATE DATABASE IF NOT EXISTS `restaurant_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `restaurant_db`;

-- Admin users
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(191) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menu items
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `image` VARCHAR(255),
  PRIMARY KEY (`id`),
  INDEX (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Gallery
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image_path` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `number_of_people` INT UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `notes` TEXT,
  `status` ENUM('pending','approved','declined') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  INDEX (`status`),
  INDEX (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;