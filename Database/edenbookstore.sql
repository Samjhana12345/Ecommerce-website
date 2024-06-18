-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2023 at 07:46 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edenbookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(50) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `price` int(20) NOT NULL,
  `qty` int(20) NOT NULL,
  `category_id` int(20) NOT NULL,
  `description` varchar(800) NOT NULL,
  `image` varchar(100) NOT NULL,
  `date_time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `publisher`, `price`, `qty`, `category_id`, `description`, `image`, `date_time`) VALUES
(9, 'DSA', 'Bhim Thapa', 'Maharjan publication', 500, 50, 13, 'DSA stands for Data Structures and Algorithms. It is also sometimes referred to as PSDS - Problem Solving Data Structures. DSA is applied in problem-solving and enables developers to learn to write efficient code. ', 'DSA.png', '2023-07-10'),
(10, 'C Programming', 'Robert Hoffman', 'Bishnu Books', 300, 30, 13, 'C is a general-purpose programming language created by Dennis Ritchie at the Bell Laboratories in 1972. It is a very popular language, despite being old.\r\n\r\nC is strongly associated with UNIX, as it was developed to write the UNIX operating system.', 'CProgrammingjfif.jfif', '2023-07-10'),
(11, 'Dot net', 'Adam Freeman', 'Bishnu Books', 450, 10, 13, 'Dot Net contains both frontend and backend languages. For example, ASP.NET is used for backend and C# & VB.NET are used as frontend development', 'Dotnet.jfif', '2023-07-10'),
(12, 'Good Night Moon', 'Stephen Hawking', 'Geeta Publication', 300, 5, 16, 'Goodnight Moon is an American children\'s book written by Margaret Wise Brown and illustrated by Clement Hurd. It was published on September 3, 1947, and is a highly acclaimed bedtime story. This book is the second in Brown and Hurd\'s \"classic series\", which also includes The Runaway Bunny and My World', 'goodnightmoonjfif.jfif', '2023-07-10'),
(13, 'Kaaphal Paakyo', 'Hari Khanal', 'Geeta Publication', 450, 5, 16, 'Children\'s Story Book: Nepali Language', 'Kafalpaakyo.jfif', '2023-07-10'),
(14, 'Architecture A World History', 'Daniel Borden, Jerzy Elzanowski, Joni Taylor, Step', 'Universal Publication', 560, 60, 15, 'Beautifully illustrated, this architecture book is a gift for an enthusiast. This pocket-sized book is filled with significant movements in architecture as well as crisp biographies of great architects. It also explores the evolution of the industry and of architectural masterpieces.', 'Architecture.jpg', '2023-07-10'),
(15, 'Ten Books on Architecture by Vitruvius', 'Morris Hicky Morgan', 'Universal publication', 800, 5, 15, 'Vitruvius has been of great influence in the world of architecture. For hundreds of years, the instructions in his Ten Books on Architecture were followed to the dot. His influence can be seen in numerous buildings and this book has been important in the creation of many architectural masterpieces.\r\n\r\n', 'architecture3.jpg', '2023-07-10');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` int(20) NOT NULL,
  `quantity` int(20) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `title`, `price`, `quantity`, `image`) VALUES
(44, 4, 'The Elf Tangent', 15, 1, 'elf.jpg'),
(45, 4, 'The Elf Tangent', 15, 1, 'elf.jpg'),
(70, 6, 'Dot net', 450, 1, 'Dotnet.jfif');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(13, 'Computer Programming'),
(15, 'Architecture'),
(16, 'Stories');

-- --------------------------------------------------------

--
-- Table structure for table `contact_form`
--

CREATE TABLE `contact_form` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` varchar(800) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_emails`
--

CREATE TABLE `newsletter_emails` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter_emails`
--

INSERT INTO `newsletter_emails` (`id`, `email`, `date_time`) VALUES
(1, 'shamal@gmail.com', '2022-05-17'),
(2, 'shamal98@gmail.com', '2022-05-17'),
(3, '', '2023-07-09');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_books` varchar(500) NOT NULL,
  `total_price` int(20) NOT NULL,
  `placed_date` date NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(30) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `email`, `payment_method`, `address`, `total_books`, `total_price`, `placed_date`, `order_status`) VALUES
(10, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', 'Bank Deposit', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', Dot net (1), C Programming (1)', 1500, '2023-07-12', 'pending'),
(11, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', 'Bank Deposit', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', C Programming (1)', 600, '2023-07-12', 'pending'),
(12, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', 'Bank Deposit', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', Dot net (1)', 900, '2023-07-12', 'pending'),
(13, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', ' ', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', C Programming (1)', 600, '2023-07-12', 'pending'),
(14, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', ' ', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', Dot net (1), DSA (1), C Programming (1)', 2500, '2023-07-12', 'pending'),
(15, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', ' ', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', Dot net (1)', 900, '2023-07-12', 'pending'),
(16, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', 'Cash on delivery', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', C Programming (11), Dot net (2)', 4950, '2023-07-12', 'pending'),
(17, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', 'Cash on delivery', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', C Programming (1)', 600, '2023-07-12', 'pending'),
(18, 6, 'Shamal Chathuranga', '0773308505', 'testing@gmail.com', ' ', '34/G, Wathurugama road, Buthpitiya., Gampaha, Western, australia, 11720', ', C Programming (1), C Programming (1)', 1200, '2023-07-13', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type` varchar(10) NOT NULL DEFAULT 'user',
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `zipCode` varchar(20) NOT NULL,
  `country` varchar(50) NOT NULL,
  `password` varchar(300) NOT NULL,
  `date_time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `firstName`, `lastName`, `email`, `phone`, `address`, `city`, `state`, `zipCode`, `country`, `password`, `date_time`) VALUES
(4, 'admin', 'Shamal', 'Chathuranga', 'testing@gmail.com', '0773308505', '34/G, Wathurugama road, Buthpitiya.', 'Gampaha', 'Western', '11720', 'australia', '202cb962ac59075b964b07152d234b70', '2022-05-20'),
(6, 'user', 'Samjhana', 'Silwal', 'samjhana@gmail.com', '9818647760', 'jjjjjjjj', 'kkkkkkk', 'kkkkkk', '1222', 'canada', '08e59e46ce75ed0ab0c85e16f98a28db', '2023-07-09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ED_Category` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ED_Checkout` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_form`
--
ALTER TABLE `contact_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_emails`
--
ALTER TABLE `newsletter_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contact_form`
--
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `newsletter_emails`
--
ALTER TABLE `newsletter_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `ED_Category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `ED_Checkout` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
