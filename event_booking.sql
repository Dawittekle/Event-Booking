-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 06:34 AM
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
-- Database: `event_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availability`
--

INSERT INTO `availability` (`id`, `venue_id`, `date`, `is_available`) VALUES
(2, 2, '2025-06-06', 0),
(3, 2, '2025-06-08', 0),
(4, 2, '2025-06-09', 0),
(5, 2, '2025-06-10', 0),
(9, 3, '2025-06-12', 0),
(10, 3, '2025-06-13', 0),
(11, 3, '2025-06-14', 0),
(12, 4, '2025-06-19', 0),
(13, 4, '2025-06-20', 0),
(14, 4, '2025-06-21', 0),
(15, 4, '2025-06-22', 0),
(16, 4, '2025-06-23', 0),
(17, 4, '2025-06-24', 0),
(18, 3, '2025-06-10', 0),
(19, 3, '2025-06-11', 0),
(20, 4, '2025-06-17', 0),
(21, 4, '2025-06-18', 0),
(22, 5, '2025-06-18', 0),
(23, 5, '2025-06-19', 0),
(24, 5, '2025-06-20', 0),
(25, 5, '2025-06-21', 0),
(26, 6, '2025-06-10', 0),
(27, 6, '2025-06-11', 0),
(28, 6, '2025-06-12', 0),
(29, 6, '2025-06-13', 0),
(30, 6, '2025-06-15', 0),
(31, 6, '2025-06-16', 0),
(32, 6, '2025-06-17', 0),
(33, 6, '2025-06-18', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `venue_id`, `user_id`, `start_date`, `end_date`, `total_price`, `status`, `created_at`) VALUES
(15, 2, 5, '2025-06-08', '2025-06-10', 720000.00, 'confirmed', '2025-06-06 08:52:10'),
(23, 3, 5, '2025-06-12', '2025-06-14', 1008000.00, 'cancelled', '2025-06-09 17:57:54'),
(24, 4, 7, '2025-06-19', '2025-06-24', 2736000.00, 'confirmed', '2025-06-09 21:33:19'),
(25, 3, 7, '2025-06-10', '2025-06-11', 672000.00, 'confirmed', '2025-06-10 05:04:33'),
(26, 4, 5, '2025-06-17', '2025-06-18', 912000.00, 'confirmed', '2025-06-10 05:52:20'),
(27, 5, 5, '2025-06-18', '2025-06-21', 1152000.00, 'confirmed', '2025-06-10 06:02:20'),
(39, 6, 5, '2025-06-10', '2025-06-13', 1440000.00, 'confirmed', '2025-06-10 07:49:57'),
(40, 6, 7, '2025-06-15', '2025-06-18', 1440000.00, 'confirmed', '2025-06-10 07:53:22'),
(41, 6, 5, '2025-06-15', '2025-06-18', 1440000.00, 'cancelled', '2025-06-10 07:53:51');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `venue_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 5, 4, 'This venue was great!', '2025-06-07 06:43:13'),
(2, 4, 7, 5, 'Absolutely loved my stay! The hotel was immaculate, the staff were incredibly attentive, and the location was perfect. Highly recommend!\\r\\n', '2025-06-09 21:44:41'),
(3, 4, 5, 4, 'BEST VENUE EVER!!!', '2025-06-10 05:55:24'),
(4, 5, 5, 5, 'This is a fantastic place for everyone.', '2025-06-10 06:14:37');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'Weddings'),
(2, 'Corporate Events'),
(3, 'Trade Shows'),
(4, 'Product Launches'),
(5, 'Graduations'),
(6, 'Concerts'),
(7, 'Parties'),
(8, 'Conferences'),
(9, 'Seminars'),
(10, 'Workshops');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('customer','owner') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `type`, `created_at`) VALUES
(4, 'Hossaena Berhan', 'h@gmail.com', '$2y$10$KFlj0zKR94TFBPpdXvdPLeSTPtVLyAaE8wWZluS/mJ1c6DnwRYU.2', 'owner', '2025-06-06 08:02:47'),
(5, 'Chris Tedla', 'c@gmail.com', '$2y$10$mdv2/vBVbRZmtkWp85fXJOYK1WKH17DpDCnBxum6OLUBN0auIGPq6', 'customer', '2025-06-06 08:20:32'),
(6, 'Kaleab Yohannes', 'k@gmail.com', '$2y$10$hxgEnCjMrvUCliecx1Rm1OvyHeNzJsEXsvVXoGlI.A8t94CMg.J8e', 'owner', '2025-06-08 17:33:19'),
(7, 'Kena Birhanu', 'kena@gmail.com', '$2y$10$apoxxuTZ8tqRUXChDmj6VenMsUQ9tcfwcbJKgXZ9iGns/b2YFNdU2', 'customer', '2025-06-09 21:23:14'),
(8, 'Dawit Tekle', 'd@gmail.com', '$2y$10$AZn2no8ceZdwbztZuN4GK.5oWJA0RImtzF2jGh9YoUuMQP/44biG6', 'owner', '2025-06-10 06:17:30'),
(9, 'abebe', 'abebe@gmail.com', '$2y$10$/cFgjOdXE/StF7QvQAuwWOrS8g.7aLcrsHqFkbj9lUcTbCXqdwuOG', 'owner', '2025-06-10 07:28:54'),
(10, 'richard john', 'r@gmail.com', '$2y$10$7OTbYehm2KOpptbcddJD5e2O2iR4r7uVn9iZQv7Ib2GUy2Pxgmp62', 'owner', '2025-06-10 07:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `owner_id`, `name`, `description`, `address`, `city`, `capacity`, `price_per_hour`, `image`, `amenities`, `created_at`) VALUES
(2, 4, 'Skylight International Hotel', 'Skylight International Hotel is a luxurious venue located in the heart of Addis Ababa. With a capacity of 500+, it is perfect for hosting large events, conferences, and weddings.', 'Bole, Next to Millenium Hall', 'Addis Ababa', 500, 10000.00, 'uploads/6842a1d748203-sky-pic.jpeg', 'Air Conditioning, WiFi, Parking', '2025-06-06 08:07:51'),
(3, 6, 'Kuriftu Entoto Resort and Spa', 'Entoto Kuriftu Resort, perched on Entoto Mountain with sweeping views of Addis Ababa, offers a luxurious glamping and adventure experience. Guests can enjoy thrilling activities like ziplining, go-karting, and horseback riding, or unwind at the forest spa. ', 'Entoto Mountians', 'Addis Ababa', 700, 14000.00, 'uploads/684711f9071f8-unnamed.webp', 'WiFi, Projector, Catering Kitchen', '2025-06-09 16:55:21'),
(4, 6, 'Sheraton Addis Hotel', 'The Sheraton Addis, a Marriott Luxury Collection hotel, is a five-star hilltop retreat in Addis Ababa with city views. It boasts glittering pools with underwater music, a full-service spa, gourmet restaurants, and a lively nightclub.', 'Sheraton', 'Addis Ababa', 1000, 19000.00, 'uploads/684741f7120d4-unnamed (1).webp', 'Air Conditioning, WiFi, Parking, Projector', '2025-06-09 20:20:07'),
(5, 6, 'Millennium Hall', 'Millennium Hall is a large, multi-purpose event venue located in Addis Ababa, Ethiopia. Built in 2006, it serves as a prominent hub for a wide array of gatherings, including international trade shows, conferences, exhibitions, concerts, cultural performances, and religious events. ', 'Bole', 'Addis Ababa', 5000, 12000.00, 'uploads/6847c9a1616fb-unnamed (2).webp', 'Air Conditioning,WiFi,Parking,Projector', '2025-06-10 05:58:57'),
(6, 10, 'Hilton Hotel', 'The Hilton Hotels & Resorts chain is a global leader in hospitality, offering a wide range of upscale accommodations and services. Guests can expect comfortable rooms, excellent dining options, and various amenities like pools and fitness centers.', 'hilton', 'Addis Ababa', 500, 15000.00, 'uploads/6847e33d4df78-hilton.jpg', 'Air Conditioning,WiFi,Projector', '2025-06-10 07:48:13');

-- --------------------------------------------------------

--
-- Table structure for table `venue_tags`
--

CREATE TABLE `venue_tags` (
  `venue_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_tags`
--

INSERT INTO `venue_tags` (`venue_id`, `tag_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(3, 1),
(3, 2),
(3, 3),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(4, 8),
(4, 9),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 8),
(5, 9),
(6, 1),
(6, 2),
(6, 3),
(6, 5),
(6, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `venue_tags`
--
ALTER TABLE `venue_tags`
  ADD PRIMARY KEY (`venue_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `availability_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `venues`
--
ALTER TABLE `venues`
  ADD CONSTRAINT `venues_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `venue_tags`
--
ALTER TABLE `venue_tags`
  ADD CONSTRAINT `venue_tags_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`),
  ADD CONSTRAINT `venue_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
