-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2024 at 02:29 AM
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
(18, 'A visual Dictionary ', 'Frank Ching', 'Wiley; 1st Edition ', 500, 9, 15, '\r\nBooks are a valuable source of information for any profession – even more so for architects. With innumerable books available to download legally, there is no excuse for not reading architecture books. Whether you are an architect, a current or future architecture student, or just someone with a passion for architecture, and if you checked our previous list of architecture books you should read, here are more books (in no particular order) that will be a welcome addition to your library.', 'Architecturebook1.jfif', '2023-11-26'),
(19, 'American Cheese', 'Joe Berkowitz', 'New American Company', 550, 9, 17, 'Through this odyssey of cheese, an unexpected culture of passionate cheesemakers is revealed, along with the extraordinary impact of one delicious dairy product. From the author of Away with Words, a deeply hilarious and unexpectedly insightful deep-dive into a cultural and culinary phenomenon: cheese.', 'americancheese.jfif', '2023-11-26'),
(20, 'GunFire', 'Ryan Busse ', 'DC Comics', 100, 8, 18, 'The book is based on extensive archival research, interviews with veterans and contemporary historians, and previously unpublished personal accounts. Profusely illustrated throughout with photographs, maps, plans, graphs, charts and diagrams, it demonstrates precisely how British Artillery was used on the battlefields around the world', 'Actionbook1.jfif', '2023-11-26');

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
(72, 8, 'C Programming', 300, 1, 'CProgrammingjfif.jfif'),
(93, 4, 'test1', 100, 1, 'kkk.jpg'),
(128, 4, 'DSA', 500, 1, 'DSA.png'),
(130, 4, 'DSA', 500, 1, 'DSA.png'),
(143, 11, 'C Programming', 300, 1, 'CProgrammingjfif.jfif'),
(144, 11, 'DSA', 500, 1, 'DSA.png'),
(149, 6, 'DSA', 500, 1, 'DSA.png');

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
(17, 'Comdey'),
(18, 'Action'),
(19, 'Horror');

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

--
-- Dumping data for table `contact_form`
--

INSERT INTO `contact_form` (`id`, `name`, `email`, `message`, `date`) VALUES
(3, 'kailash maharjan', 'kailash@gmail.com', 'i dont find comdey book ', '2023-08-26'),
(4, 'kailash maharjan', 'kailash@gmail.com', 'i dont find good book in', '2023-08-26'),
(5, 'kailash maharjan', 'kailash.mhz@gmail.com', 'OKAY', '2023-08-26');

-- --------------------------------------------------------

--
-- Table structure for table `item_rating`
--

CREATE TABLE `item_rating` (
  `ratingId` int(11) NOT NULL,
  `books` int(11) NOT NULL,
  `users` int(11) NOT NULL,
  `ratingNumber` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Block, 0 = Unblock'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item_rating`
--

INSERT INTO `item_rating` (`ratingId`, `books`, `users`, `ratingNumber`, `title`, `comments`, `created`, `modified`, `status`) VALUES
(95, 9, 6, 4, 'wow', 'wow', '2023-11-27 01:24:48', '2023-11-27 01:24:48', 1),
(96, 20, 7, 4, 'review', 'one of the best book', '2023-11-27 03:19:57', '2023-11-27 03:19:57', 1),
(97, 9, 6, 1, 'good', 'nice', '2023-11-27 06:45:35', '2023-11-27 06:45:35', 1),
(98, 18, 6, 4, 'good', 'good', '2023-11-27 06:46:16', '2023-11-27 06:46:16', 1),
(99, 10, 7, 5, 'c++', 'nice book', '2024-06-13 05:00:02', '2024-06-13 05:00:02', 1);

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
(33, 7, 'kailash maharjan', '9818607327', 'kailash@gmail.com', 'Bank Deposit', 'pyangoun,lalitpur', ', DSA (1), A visual Dictionary  (1), A visual Dictionary  (1)', 3000, '2023-11-27', 'pending'),
(34, 6, 'Samjhana Silwal', '9818647760', 'samjhana@gmail.com', 'Bank Deposit', 'Lele', ', GunFire (1)', 200, '2023-11-27', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `ratingNumber` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `search_queries`
--

CREATE TABLE `search_queries` (
  `id` int(11) NOT NULL,
  `query` varchar(255) NOT NULL,
  `search_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `search_queries`
--

INSERT INTO `search_queries` (`id`, `query`, `search_count`) VALUES
(51, 'a', 1),
(52, 'A visual Dictionary ', 4),
(53, 'DSA', 3),
(54, 'DS', 1),
(55, 'D', 1);

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
  `password` varchar(300) NOT NULL,
  `date_time` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type`, `firstName`, `lastName`, `email`, `phone`, `address`, `password`, `date_time`) VALUES
(4, 'admin', 'Shyam', 'KC', 'testing@gmail.com', '0773308505', '34/G, Wathurugama road, Buthpitiya.', '202cb962ac59075b964b07152d234b70', '2022-05-20'),
(6, 'user', 'Samjhana', 'Silwal', 'samjhana@gmail.com', '9818647760', 'Lele', '08e59e46ce75ed0ab0c85e16f98a28db', '2023-07-09'),
(7, 'user', 'kailash', 'maharjan', 'kailash@gmail.com', '9818607327', 'chapagoun', '202cb962ac59075b964b07152d234b70', '2023-08-25'),
(8, 'user', 'kailash', 'maharjan', 'kailash.mhz@gmail.com', '9818607327', 'chapagoun', '202cb962ac59075b964b07152d234b70', '2023-08-26'),
(11, 'user', 'Kavya', 'Shivalkar', 'kavya@gmail.com', '9852647815', 'Bhaktapur', '8ec167538961546fc93cd21c41989106', '2023-11-26');

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
-- Indexes for table `item_rating`
--
ALTER TABLE `item_rating`
  ADD PRIMARY KEY (`ratingId`);

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
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `search_queries`
--
ALTER TABLE `search_queries`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `contact_form`
--
ALTER TABLE `contact_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item_rating`
--
ALTER TABLE `item_rating`
  MODIFY `ratingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `newsletter_emails`
--
ALTER TABLE `newsletter_emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `search_queries`
--
ALTER TABLE `search_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
