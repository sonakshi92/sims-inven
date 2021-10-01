-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2020 at 05:42 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sims_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `type` text NOT NULL,
  `contact_person` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 2 = Active',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `type`, `contact_person`, `contact`, `status`, `date_created`, `date_updated`) VALUES
(1, 'Customer 1', 'Sample Address of Customer', 'Proprietorship', 'Customer 1 Contact Person', '78556899662', 1, '2020-09-16 22:31:20', '2020-09-16 22:35:02');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(30) NOT NULL,
  `receiving_id` int(30) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `product_id` int(30) NOT NULL,
  `qty` int(30) NOT NULL,
  `unit_price` varchar(30) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = in , 2 = out',
  `remarks` text NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `receiving_id`, `unit`, `product_id`, `qty`, `unit_price`, `type`, `remarks`, `date_updated`) VALUES
(23, 6, 'pcs', 1, 50, '600', 1, '', '2020-09-17 21:24:45'),
(24, 6, 'pcs', 3, 50, '320', 1, '', '2020-09-17 21:24:45'),
(25, 6, 'box', 4, 50, '450', 1, '', '2020-09-17 21:24:45'),
(26, 6, 'box', 5, 500, '300', 1, '', '2020-09-17 21:24:45'),
(34, 1, 'pcs', 3, 2, '700', 2, 'Sales-202009171', '2020-09-17 22:19:22'),
(35, 1, 'pcs', 1, 6, '1500', 2, 'Sales-202009171', '2020-09-17 22:19:22'),
(36, 1, 'box', 5, 8, '450', 2, 'Sales-202009171', '2020-09-17 22:19:22'),
(37, 1, 'box', 4, 5, '2500', 2, 'Sales-202009171', '2020-09-17 22:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL,
  `sales_id` int(30) NOT NULL,
  `invoice` text NOT NULL,
  `amount` varchar(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `ref_no` varchar(100) NOT NULL COMMENT 'reference number for cheque and credit card payment',
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `sales_id`, `invoice`, `amount`, `payment_method`, `ref_no`, `remarks`, `date_created`) VALUES
(9, 1, '98855467', '4000', 'Check', '123774447', '4,000 DP', '2020-09-17 22:34:45'),
(10, 1, '85542', '4000', 'Check', '', '2nd Payment', '2020-09-17 23:04:15'),
(13, 1, '47847454', '5000', 'Check', '48768777', '3rd Payment', '2020-09-17 23:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE `po_items` (
  `id` int(30) NOT NULL,
  `po_id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `qty` int(20) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `unit_price` varchar(20) NOT NULL,
  `total_amount` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `po_items`
--

INSERT INTO `po_items` (`id`, `po_id`, `product_id`, `qty`, `unit`, `unit_price`, `total_amount`, `date_created`) VALUES
(11, 8, 1, 50, 'pcs', '600', '2', '2020-09-17 21:20:34'),
(12, 8, 3, 50, 'pcs', '320', '1', '2020-09-17 21:20:34'),
(13, 8, 4, 50, 'box', '450', '8', '2020-09-17 21:20:34'),
(14, 8, 5, 500, 'box', '300', '5', '2020-09-17 21:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `price_list`
--

CREATE TABLE `price_list` (
  `id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `price` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `date_effective` date NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `price_list`
--

INSERT INTO `price_list` (`id`, `product_id`, `price`, `description`, `date_effective`, `date_created`) VALUES
(1, 5, '500', 'Sample Only', '2020-09-16', '2020-09-16 21:27:19'),
(2, 5, '450', 'Sample', '2020-09-16', '2020-09-16 21:35:17'),
(3, 1, '1500', 'Sample', '2020-09-16', '2020-09-16 21:38:38'),
(4, 3, '700', 'Sample', '2020-09-16', '2020-09-16 21:38:51'),
(5, 4, '2500', 'Sample', '2020-09-16', '2020-09-16 21:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(30) NOT NULL,
  `sku` varchar(15) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `unit` varchar(50) NOT NULL,
  `convert_qty` int(10) NOT NULL,
  `is_bulk` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = no , 1= Yes',
  `convert_unit` varchar(50) NOT NULL,
  `parent_id` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `description`, `unit`, `convert_qty`, `is_bulk`, `convert_unit`, `parent_id`, `status`, `date_created`) VALUES
(1, '377117050507\n', 'Sample Product', 'This is only a sample product.', 'pcs', 0, 0, '', 0, 1, '2020-08-24'),
(3, '694286211487\n', 'Maxis 17 90/120', '  Maxis 17 90/120', 'pcs', 0, 0, '', 0, 1, '2020-09-14'),
(4, '655492647067\n', 'Bulk', 'Sample Bulk Product', 'box', 10, 1, 'pcs', 0, 1, '2020-09-16'),
(5, '552813637886\n', 'Bulk 2', 'Sample Bulk Product 2', 'box', 6, 1, 'pcs', 0, 1, '2020-09-16');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(30) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `total_amount` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=deleted,= 1 = pending,2 = received',
  `user_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `ref_no`, `supplier_id`, `total_amount`, `status`, `user_id`, `date_created`, `date_updated`) VALUES
(8, 'PO-202009171', 3, '218500', 2, 1, '2020-09-17 21:20:34', '2020-09-17 21:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `receiving`
--

CREATE TABLE `receiving` (
  `id` int(30) NOT NULL,
  `po_id` int(30) NOT NULL,
  `po_ref` varchar(250) NOT NULL,
  `invoice` varchar(250) NOT NULL,
  `total_amount` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `receive_through` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `receiving`
--

INSERT INTO `receiving` (`id`, `po_id`, `po_ref`, `invoice`, `total_amount`, `user_id`, `receive_through`, `date_created`, `date_updated`) VALUES
(4, 1, 'PO-2020090901', '20140623', '2500', 1, 'fax', '2020-09-16 21:48:09', '2020-09-16 21:48:09'),
(5, 7, 'PO-202009161', '78752285222', '92500', 1, 'fax', '2020-09-16 22:12:20', '2020-09-16 22:12:20'),
(6, 8, 'PO-202009171', '20140623', '218500', 1, 'fax', '2020-09-17 21:23:20', '2020-09-17 21:23:20');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(30) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `customer_id` int(30) NOT NULL,
  `inventory_ids` text NOT NULL,
  `discount_json` text NOT NULL,
  `payment_mode` text NOT NULL,
  `c_po_no` text NOT NULL,
  `delivery_receipt_no` text NOT NULL,
  `delivery_address` text NOT NULL,
  `delivery_schedule` date NOT NULL,
  `total_amount` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `ref_no`, `customer_id`, `inventory_ids`, `discount_json`, `payment_mode`, `c_po_no`, `delivery_receipt_no`, `delivery_address`, `delivery_schedule`, `total_amount`, `status`, `user_id`, `date_created`, `date_updated`) VALUES
(1, 'Sales-202009171', 1, '[34,35,36,37]', '[{\"34\":\"0\"},{\"35\":\"2\"},{\"36\":\"10\"},{\"37\":\"0\"}]', 'Installment', 'PO-87545697', '', '', '2020-09-22', '25960', 1, 1, '2020-09-17 21:26:01', '2020-09-17 22:30:48');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `contact_person` text NOT NULL,
  `contact_number` text NOT NULL,
  `status` tinyint(2) DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `name`, `address`, `contact_person`, `contact_number`, `status`, `date_created`) VALUES
(1, 'Sample Supplier 1', 'Sample Supplier 1 Address', 'Supplier 1 Contact Person', 'Supplier 1 Contact Person', 1, '2020-09-06 17:22:32'),
(2, 'Sample Supplier 2', 'Supplier 2 Address', '', '756622254', 1, '2020-09-06 12:36:24'),
(3, 'Sample Supplier 3', 'Supplier address 3', '', '882325444545', 1, '2020-09-06 12:51:54'),
(4, 'Sample Supplier 4', 'Sample Supplier 4 Address', 'Supplier 4 Contact Person', '78212655200', 1, '2020-09-06 13:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` text NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = inactive ,1 = active',
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `status`, `date_updated`) VALUES
(1, 'Admin', '', 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1, '2020-09-06 15:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `validation`
--

CREATE TABLE `validation` (
  `id` int(30) NOT NULL,
  `form_type` varchar(100) NOT NULL,
  `form_id` int(30) NOT NULL,
  `type` varchar(100) NOT NULL,
  `user_id` int(30) NOT NULL,
  `entered_name` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `validation`
--

INSERT INTO `validation` (`id`, `form_type`, `form_id`, `type`, `user_id`, `entered_name`, `date_created`) VALUES
(1, 'po', 8, 'checked', 1, '', '2020-09-17 21:22:28'),
(2, 'po', 8, 'checked', 1, '', '2020-09-17 21:22:37'),
(3, 'po', 8, 'approved', 1, '', '2020-09-17 21:22:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `po_items`
--
ALTER TABLE `po_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_list`
--
ALTER TABLE `price_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receiving`
--
ALTER TABLE `receiving`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `validation`
--
ALTER TABLE `validation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `po_items`
--
ALTER TABLE `po_items`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `price_list`
--
ALTER TABLE `price_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `receiving`
--
ALTER TABLE `receiving`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `validation`
--
ALTER TABLE `validation`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
