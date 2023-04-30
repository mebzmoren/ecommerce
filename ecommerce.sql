-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2023 at 03:24 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(255) NOT NULL,
  `shop_id` int(155) NOT NULL,
  `category` varchar(155) NOT NULL,
  `active` int(15) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `shop_id`, `category`, `active`) VALUES
(1, 1, 'Men', 1),
(2, 2, 'Basketball', 1),
(3, 2, 'Volleyball', 1);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(255) NOT NULL,
  `user_type` int(155) NOT NULL,
  `full_name` varchar(155) NOT NULL,
  `address` varchar(155) NOT NULL,
  `contact_number` varchar(155) NOT NULL,
  `username` varchar(155) NOT NULL,
  `upassword` varchar(155) NOT NULL,
  `active` int(155) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `user_type`, `full_name`, `address`, `contact_number`, `username`, `upassword`, `active`) VALUES
(1, 2, 'seller', 'manila', '09999999999', 'seller', '202cb962ac59075b964b07152d234b70', 1),
(2, 1, 'member', 'manila', '09991256162', 'member', '202cb962ac59075b964b07152d234b70', 1),
(3, 2, 'seller', 'bacolod', '09991258672', 'seller1', '202cb962ac59075b964b07152d234b70', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(255) NOT NULL,
  `ref_num` varchar(155) NOT NULL,
  `member_id` int(155) NOT NULL,
  `shop_id` int(155) NOT NULL,
  `payment_option` varchar(155) NOT NULL,
  `code` varchar(155) NOT NULL,
  `quantity` int(155) NOT NULL,
  `price` varchar(155) NOT NULL,
  `shipping_fee` int(155) NOT NULL,
  `total_price` varchar(155) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `ref_num`, `member_id`, `shop_id`, `payment_option`, `code`, `quantity`, `price`, `shipping_fee`, `total_price`, `order_date`) VALUES
(1, '16824285541454', 2, 2, 'GCASH', 'p6', 1, '600', 99, '5298', '2023-04-25 13:15:54'),
(2, '16824285541454', 2, 2, 'GCASH', 'p5', 3, '4500', 99, '5298', '2023-04-25 13:15:54'),
(3, '16824286294423', 2, 2, 'BDO', 'p6', 49, '29400', 99, '29499', '2023-04-25 13:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(255) NOT NULL,
  `shop_id` int(155) NOT NULL,
  `category_id` int(155) NOT NULL,
  `product_image` varchar(155) NOT NULL,
  `product_name` varchar(155) NOT NULL,
  `description` varchar(155) NOT NULL,
  `price` int(155) NOT NULL,
  `quantity` int(155) NOT NULL,
  `code` varchar(155) NOT NULL,
  `active` int(15) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `shop_id`, `category_id`, `product_image`, `product_name`, `description`, `price`, `quantity`, `code`, `active`) VALUES
(1, 1, 1, 'images (1).jpg', 'Outdoor Sunglasses', 'testing', 670, 99, 'p1', 1),
(2, 1, 1, 'images.jpg', 'Driving Sunglasses', 'test', 500, 100, 'p2', 1),
(3, 1, 1, '8706bcc8683e46e9397b6321100bbcb0.jpg', 'Yellow Sunglasses', 'test123', 580, 150, 'p3', 1),
(4, 1, 1, 's-l400.jpg', 'Testing Sunglasses', 'test', 1600, 160, 'p4', 1),
(5, 2, 2, 'download (1).jpg', 'Molten', 'test', 1500, 97, 'p5', 1),
(6, 2, 3, 'IV5F-3_AD__76232.jpg', 'volleyball test', 'test', 600, 0, 'p6', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `rating_id` int(255) NOT NULL,
  `member_id` int(155) NOT NULL,
  `product_id` int(155) NOT NULL,
  `rating` int(155) NOT NULL,
  `comment` varchar(155) NOT NULL,
  `rating_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`rating_id`, `member_id`, `product_id`, `rating`, `comment`, `rating_date`) VALUES
(1, 2, 5, 4, 'ganda', '2023-04-25 13:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `shop_id` int(255) NOT NULL,
  `seller_id` int(155) NOT NULL,
  `shop_name` varchar(155) NOT NULL,
  `shop_icon` varchar(155) NOT NULL,
  `shipping_fee` int(155) NOT NULL,
  `active` int(155) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`shop_id`, `seller_id`, `shop_name`, `shop_icon`, `shipping_fee`, `active`) VALUES
(1, 1, 'Sunny Day', 'sunglassesshop.png', 60, 1),
(2, 3, 'Ball Shop', 'ballshop.png', 99, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`shop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `rating_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `shop_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
