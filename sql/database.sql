-- ========================================================
-- Photography Portfolio Database Schema (XAMPP / MySQL)
-- Database Name: photography_db
-- ========================================================

CREATE DATABASE IF NOT EXISTS `photography_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `photography_db`;

-- --------------------------------------------------------
-- Table structure for `admins`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `categories`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `slug` VARCHAR(50) NOT NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `photos`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `photos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `category_id` INT NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `camera` VARCHAR(100) DEFAULT 'Canon EOS R5',
  `lens` VARCHAR(100) DEFAULT 'RF 24-70mm f/2.8L',
  `iso` VARCHAR(50) DEFAULT '100',
  `shutter_speed` VARCHAR(50) DEFAULT '1/250s',
  `aperture` VARCHAR(50) DEFAULT 'f/2.8',
  `location` VARCHAR(150) DEFAULT 'Sylhet, Bangladesh',
  `is_featured` TINYINT(1) DEFAULT 0,
  `views_count` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `messages`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(150) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Seed Data: Default Admin User (Username: admin | Password: admin123)
-- --------------------------------------------------------
INSERT INTO `admins` (`username`, `password_hash`, `email`) 
VALUES ('admin', '$2y$10$6uus.fOsQXxqDcjWH.YodOomuVFsUPxaCtsnD9lsJYyQyvZmmxPry', 'admin@photography.local')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- --------------------------------------------------------
-- Seed Data: Categories
-- --------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `slug`) VALUES
(1, 'Nature', 'nature'),
(2, 'Portrait', 'portrait'),
(3, 'Street', 'street'),
(4, 'Landscape', 'landscape'),
(5, 'Architecture', 'architecture'),
(6, 'Wildlife', 'wildlife')
ON DUPLICATE KEY UPDATE `name`=`name`;

-- --------------------------------------------------------
-- Seed Data: Initial Sample Photographs
-- --------------------------------------------------------
INSERT INTO `photos` (`id`, `title`, `description`, `category_id`, `image_path`, `camera`, `lens`, `iso`, `shutter_speed`, `aperture`, `location`, `is_featured`, `views_count`) VALUES
(1, 'Golden Hour Mountain Peak', 'A breathtaking view of sunrise over misty mountain ridges.', 4, 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=1200&auto=format&fit=crop', 'Sony A7 IV', 'FE 16-35mm f/2.8 GM', '100', '1/500s', 'f/8.0', 'Bandarban, Bangladesh', 1, 142),
(2, 'Serene Forest Path', 'Morning light filtering through dense green canopy leaves.', 1, 'https://images.unsplash.com/photo-1448375240586-882707db888b?q=80&w=1200&auto=format&fit=crop', 'Canon EOS R6', 'RF 50mm f/1.2L', '200', '1/160s', 'f/2.0', 'Sreemangal, Sylhet', 0, 98),
(3, 'Urban Night Life', 'Vibrant light trails of city traffic under neon lights.', 3, 'https://images.unsplash.com/photo-1519501025264-65ba15a82390?q=80&w=1200&auto=format&fit=crop', 'Fujifilm X-T4', 'XF 23mm f/1.4', '800', '2s', 'f/5.6', 'Dhaka City, Bangladesh', 1, 215),
(4, 'Expressive Soul Portrait', 'A deep mood portrait capturing natural shadows and emotion.', 2, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1200&auto=format&fit=crop', 'Sony A7R V', 'FE 85mm f/1.4 GM', '100', '1/320s', 'f/1.8', 'Studio Art, Chittagong', 1, 310),
(5, 'Modern Glass Facade', 'Geometric symmetry in modern skyscraper architecture.', 5, 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1200&auto=format&fit=crop', 'Nikon Z7 II', 'NIKKOR Z 14-30mm f/4', '100', '1/200s', 'f/7.1', 'Gulshan, Dhaka', 0, 75),
(6, 'Wild Kingfisher Focus', 'A colorful kingfisher waiting patiently on a wooden twig.', 6, 'https://images.unsplash.com/photo-1552728089-57bdde30beb3?q=80&w=1200&auto=format&fit=crop', 'Canon EOS R5', 'RF 100-500mm f/4.5-7.1', '1600', '1/2000s', 'f/5.6', 'Sundarbans, Khulna', 0, 189)
ON DUPLICATE KEY UPDATE `title`=`title`;
