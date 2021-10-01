-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2020 at 03:52 PM
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
(1, 'Customer 1', 'Sample Address of Customer', 'Single-Proprietorship', 'Customer 1 Contact Person', '78556899662', 1, '2020-09-16 22:31:20', '2020-09-27 23:00:39'),
(2, 'Customer 2', 'sample address', 'Company', 'Sample', '45654055', 1, '2020-09-23 22:31:30', '2020-09-27 23:00:50'),
(3, 'Sample Customer 1', 'Sample address ', 'Single', 'Sample ', '645645152', 0, '2020-09-27 15:53:17', '2020-09-27 16:01:12'),
(4, 'sadasdasdasd', 'asdasd', 'Proprietorship', 'Supplier 4 Contact Person', '1213123', 0, '2020-09-27 17:15:46', '2020-09-27 17:16:08');

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
(80, 11, 'pcs', 1, 50, '350', 1, 'Received from PO-202010031', '2020-10-03 20:32:43'),
(81, 11, 'pcs', 3, 50, '450', 1, 'Received from PO-202010031', '2020-10-03 20:32:43'),
(84, 14, 'pcs', 1, 5, '1500', 2, 'Sales-202010031', '2020-10-03 21:01:06'),
(85, 14, 'pcs', 3, 5, '700', 2, 'Sales-202010031', '2020-10-03 21:01:06');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(30) NOT NULL,
  `log_msg` text NOT NULL,
  `user_id` int(30) NOT NULL,
  `action_made` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `log_msg`, `user_id`, `action_made`, `date_created`) VALUES
(1, 'added Sample Customer 1 into customer list. ', 1, 'Create', '2020-09-27 15:53:17'),
(2, 'update Sample Customer 1 into customer list. ', 1, 'Update', '2020-09-27 15:53:33'),
(3, 'update Customer 1 customer data. ', 1, 'Update', '2020-09-27 16:00:45'),
(4, 'deleted Sample Customer 1 into customer list. ', 1, 'Delete', '2020-09-27 16:01:12'),
(5, 'update Sample Supplier 3 supplier data. ', 1, 'Update', '2020-09-27 16:03:18'),
(6, 'deleted PO-Return-202009231 into purchases return data. ', 1, 'Deleted', '2020-09-27 16:45:31'),
(7, 'updated  payments data. ', 1, 'Updated', '2020-09-27 16:59:37'),
(8, 'added  payments. ', 1, 'Create', '2020-09-27 17:03:15'),
(9, 'deleted  PO-202009231 from po_payments. ', 1, 'Delete', '2020-09-27 17:05:56'),
(10, 'added Sample Supplier 5 into supplier list. ', 1, 'Create', '2020-09-27 17:12:04'),
(11, 'update Sample Supplier 5 supplier data. ', 1, 'Update', '2020-09-27 17:12:11'),
(12, 'deleted Sample Supplier 5 into supplier list. ', 1, 'Deleted', '2020-09-27 17:12:16'),
(13, 'added sadasdasdasd into customer list. ', 1, 'Create', '2020-09-27 17:15:46'),
(14, 'update sadasdasdasd customer data. ', 1, 'Update', '2020-09-27 17:15:56'),
(15, 'deleted sadasdasdasd into customer list. ', 1, 'Delete', '2020-09-27 17:16:08'),
(16, 'added  product price into the product pirce list. ', 1, 'Added', '2020-09-27 17:21:26'),
(17, 'added 845196496256\n product price into the product pirce list. ', 1, 'Added', '2020-09-27 17:22:15'),
(18, 'updated  product data. ', 1, 'Update', '2020-09-27 17:25:29'),
(19, 'updated 845196496256\n product data. ', 1, 'Update', '2020-09-27 17:27:51'),
(20, 'updated 845196496256\n product data. ', 1, 'Update', '2020-09-27 17:29:43'),
(21, 'deleted  product from the product list. ', 1, 'Deleted', '2020-09-27 17:29:56'),
(22, 'created   into sales list. ', 1, 'Create', '2020-09-27 17:33:13'),
(23, 'deleted  Sales-202009271 from sales list. ', 1, 'Delete', '2020-09-27 17:34:38'),
(24, 'created   into sales list. ', 1, 'Create', '2020-09-27 17:35:20'),
(25, 'deleted  Sales-202009271 from sales list. ', 1, 'Delete', '2020-09-27 17:36:14'),
(26, 'created  Sales-202009271 into sales list. ', 1, 'Create', '2020-09-27 17:36:48'),
(27, 'added  123123123 payment. ', 1, 'Create', '2020-09-27 17:37:18'),
(28, 'updated  Sales-202009271 payment. ', 1, 'Update', '2020-09-27 17:39:10'),
(29, 'added  Sales-Return-202009271 from sales return list. ', 1, 'Create', '2020-09-27 17:40:05'),
(30, 'updated  Sales-Return-202009271 sales return data. ', 1, 'Update', '2020-09-27 17:40:17'),
(31, 'deleted  Sales-Return-202009271 from sales return list. ', 1, 'Deleted', '2020-09-27 17:40:38'),
(32, 'added  purchase order into the purcase order list. ', 1, 'Added', '2020-09-27 17:41:05'),
(33, 'deleted PO-202009271 from purchase order list. ', 1, 'deleted', '2020-09-27 17:42:07'),
(34, 'added PO-202009272 purchase order into the purcase order list. ', 1, 'Added', '2020-09-27 17:42:20'),
(35, 'validated  into purchases order list. ', 1, 'Added', '2020-09-27 17:43:31'),
(36, 'validated PO-202009272 into purchases order list. ', 1, 'Added', '2020-09-27 17:44:23'),
(37, 'validated PO-202009272 into purchases order list. ', 1, 'Added', '2020-09-27 17:44:45'),
(38, 'added 123123123 payments. ', 1, 'Create', '2020-09-27 17:45:45'),
(39, 'added PO-202009272 payments. ', 1, 'Create', '2020-09-27 17:47:20'),
(40, 'updated PO-202009272 payments data. ', 1, 'Updated', '2020-09-27 17:47:34'),
(41, 'received PO-202009272. ', 1, 'Create', '2020-09-27 17:48:09'),
(42, 'updated PO-202009272 receiving data. ', 1, 'Update', '2020-09-27 17:48:23'),
(43, 'deleted PO-202009171 from purchases receiving list. ', 1, 'deleted', '2020-09-27 17:48:36'),
(44, 'deleted PO-202009272 from purchase order list. ', 1, 'deleted', '2020-09-27 17:48:56'),
(45, 'added PO-Return-202009271 into purchases return list. ', 1, 'Added', '2020-09-27 17:49:22'),
(46, 'updated PO-Return-202009271 into purchases return data. ', 1, 'Update', '2020-09-27 17:49:35'),
(47, 'deleted PO-Return-202009271 from purchases return data. ', 1, 'Deleted', '2020-09-27 17:49:51'),
(48, 'update Customer 1 customer data. ', 1, 'Update', '2020-09-27 23:00:39'),
(49, 'update Customer 2 customer data. ', 1, 'Update', '2020-09-27 23:00:50'),
(50, 'added  into salesman list. ', 1, 'Create', '2020-09-29 22:16:39'),
(51, 'update Salesman, Sample S salesman data. ', 1, 'Update', '2020-09-29 22:20:04'),
(52, 'deleted Salesmann, Sample S from salesman list. ', 1, 'Delete', '2020-09-29 22:20:12'),
(53, 'added Salesman, Sample S into salesman list. ', 1, 'Create', '2020-09-29 22:21:25'),
(54, 'update Salesman, Sample S salesman data. ', 1, 'Update', '2020-09-29 22:21:32'),
(55, 'deleted Salesman, Sample S from salesman list. ', 1, 'Delete', '2020-09-29 22:21:38'),
(56, 'updated  Sales-202009271 into sales list. ', 1, 'Update', '2020-09-29 22:28:27'),
(57, 'updated  Sales-202009271 into sales list. ', 1, 'Update', '2020-09-29 22:32:38'),
(58, 'deleted  Sales-202009271 from sales list. ', 1, 'Delete', '2020-09-30 00:29:04'),
(59, 'deleted  Sales-202009231 from sales list. ', 1, 'Delete', '2020-09-30 00:29:07'),
(60, 'deleted  Sales-202009171 from sales list. ', 1, 'Delete', '2020-09-30 00:29:09'),
(61, 'created  Sales-202009291 into sales list. ', 1, 'Create', '2020-09-30 00:31:46'),
(62, 'created  Sales-202010011 into sales list. ', 1, 'Create', '2020-10-01 22:57:09'),
(63, 'added  Sales-202009291 payment. ', 1, 'Create', '2020-10-01 23:21:33'),
(64, 'added PO-202010011 purchase order into the purcase order list. ', 1, 'Added', '2020-10-01 23:54:37'),
(65, 'added PO-202010012 purchase order into the purcase order list. ', 1, 'Added', '2020-10-02 00:00:03'),
(66, 'deleted  Sales-202010011 from sales list. ', 1, 'Delete', '2020-10-03 20:21:51'),
(67, 'deleted  Sales-202009291 from sales list. ', 1, 'Delete', '2020-10-03 20:21:54'),
(68, 'deleted PO-202009171 from purchases receiving list. ', 1, 'deleted', '2020-10-03 20:24:28'),
(69, 'deleted PO-202009221 from purchases receiving list. ', 1, 'deleted', '2020-10-03 20:24:30'),
(70, 'deleted PO-202009231 from purchases receiving list. ', 1, 'deleted', '2020-10-03 20:24:32'),
(71, 'deleted PO-202009171 from purchase order list. ', 1, 'deleted', '2020-10-03 20:24:36'),
(72, 'deleted PO-202009221 from purchase order list. ', 1, 'deleted', '2020-10-03 20:24:38'),
(73, 'deleted PO-202009231 from purchase order list. ', 1, 'deleted', '2020-10-03 20:24:39'),
(74, 'deleted PO-202010011 from purchase order list. ', 1, 'deleted', '2020-10-03 20:24:41'),
(75, 'deleted PO-202010012 from purchase order list. ', 1, 'deleted', '2020-10-03 20:24:43'),
(76, 'deleted  Sales-Return-202009221 from sales return list. ', 1, 'Deleted', '2020-10-03 20:24:57'),
(77, 'added PO-202010031 purchase order into the purcase order list. ', 1, 'Added', '2020-10-03 20:28:35'),
(78, 'validated PO-202010031 into purchases order list. ', 1, 'Added', '2020-10-03 20:29:31'),
(79, 'validated PO-202010031 into purchases order list. ', 1, 'Added', '2020-10-03 20:29:39'),
(80, 'received PO-202010031. ', 1, 'Create', '2020-10-03 20:32:43'),
(81, 'created  Sales-202010031 into sales list. ', 1, 'Create', '2020-10-03 20:51:20'),
(82, 'updated   payment. ', 1, 'Update', '2020-10-03 20:56:56'),
(83, 'deleted  Sales-202010031 from sales list. ', 1, 'Delete', '2020-10-03 20:57:44'),
(84, 'created  Sales-202010031 into sales list. ', 1, 'Create', '2020-10-03 20:58:10'),
(85, 'updated  Sales-202010031 into sales list. ', 1, 'Update', '2020-10-03 21:01:06'),
(86, 'updated  Sales-202010031 payment. ', 1, 'Update', '2020-10-03 21:02:01'),
(87, 'added  Sales-202010031 payment. ', 1, 'Create', '2020-10-03 21:02:17'),
(88, 'added 0001\n purchase order into the purcase order list. ', 1, 'Added', '2020-10-03 21:16:13'),
(89, 'added 0002\n purchase order into the purcase order list. ', 1, 'Added', '2020-10-03 21:16:51'),
(90, 'deleted 0001\n from purchase order list. ', 1, 'deleted', '2020-10-03 21:40:50'),
(91, 'updated  purchase order data. ', 1, 'Update', '2020-10-03 21:41:50'),
(92, 'updated  purchase order data. ', 1, 'Update', '2020-10-03 21:43:00'),
(93, 'updated  purchase order data. ', 1, 'Update', '2020-10-03 21:43:12'),
(94, 'updated  purchase order data. ', 1, 'Update', '2020-10-03 21:45:27'),
(95, 'updated 0002\n purchase order data. ', 1, 'Update', '2020-10-03 21:47:00'),
(96, 'updated 0002\n purchase order data. ', 1, 'Update', '2020-10-03 21:47:21');

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
(22, 0, '1', '3000', 'Cash', '', 'DP', '2020-10-03 20:56:56'),
(23, 14, '2', '3000', 'Cash', '', 'DP', '2020-10-03 21:02:01'),
(24, 14, '3', '2000', 'Cash', '', '2nd payment', '2020-10-03 21:02:17');

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
(15, 9, 6, 100, 'pcs', '1800', '1', '2020-09-22 18:06:42'),
(28, 8, 1, 50, 'pcs', '600', '2', '2020-09-22 19:03:22'),
(29, 8, 3, 50, 'pcs', '320', '1', '2020-09-22 19:03:22'),
(30, 8, 4, 50, 'box', '450', '8', '2020-09-22 19:03:22'),
(31, 8, 5, 500, 'box', '300', '5', '2020-09-22 19:03:22'),
(32, 10, 4, 50, 'box', '350', '2', '2020-09-23 21:29:54'),
(33, 10, 1, 10, 'pcs', '750', '5', '2020-09-23 21:57:00'),
(34, 11, 1, 20, 'pcs', '1500', '3', '2020-09-27 17:41:05'),
(35, 12, 1, 50, 'pcs', '2500', '1', '2020-09-27 17:42:20'),
(36, 13, 1, 10, 'pcs', '450', '4', '2020-10-01 23:54:37'),
(37, 14, 1, 10, 'pcs', '450', '4', '2020-10-02 00:00:03'),
(38, 15, 1, 50, 'pcs', '350', '4', '2020-10-03 20:28:34'),
(39, 15, 3, 50, 'pcs', '450', '0', '2020-10-03 20:28:34'),
(40, 16, 4, 50, 'box', '400', '2', '2020-10-03 21:16:13'),
(41, 17, 4, 50, 'box', '400', '2', '2020-10-03 21:16:51');

-- --------------------------------------------------------

--
-- Table structure for table `po_payments`
--

CREATE TABLE `po_payments` (
  `id` int(30) NOT NULL,
  `po_id` int(30) NOT NULL,
  `invoice` text NOT NULL,
  `amount` varchar(20) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `ref_no` varchar(100) NOT NULL COMMENT 'reference number for cheque and credit card payment',
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `po_payments`
--

INSERT INTO `po_payments` (`id`, `po_id`, `invoice`, `amount`, `payment_method`, `ref_no`, `remarks`, `date_created`) VALUES
(1, 8, '98855467', '50000', 'Check', '8754487722', '50,000 DP', '2020-09-22 18:57:46'),
(2, 8, '12332433', '20000', 'Cash', '', '2nd Payment', '2020-09-22 19:06:55'),
(5, 12, '98855467', '5000', 'Check', '123123123', 'asdasd', '2020-09-27 17:45:45'),
(6, 12, '4234234', '500', 'Credit Card', '465456', 'sdasd', '2020-09-27 17:47:20');

-- --------------------------------------------------------

--
-- Table structure for table `po_returns`
--

CREATE TABLE `po_returns` (
  `id` int(30) NOT NULL,
  `supplier_id` int(30) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `action_type` tinyint(1) NOT NULL COMMENT '1= replace ,2 = refund',
  `data_json` text NOT NULL,
  `inv_ids` text NOT NULL,
  `total_amount` double NOT NULL,
  `user_id` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT ' 0 = Inactive ,2 = active',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `po_returns`
--

INSERT INTO `po_returns` (`id`, `supplier_id`, `ref_no`, `action_type`, `data_json`, `inv_ids`, `total_amount`, `user_id`, `status`, `date_created`, `date_updated`) VALUES
(4, 3, 'PO-Return-202009231', 2, '[{\"product_id\":\"5\",\"qty\":\"10\",\"unit\":\"box\",\"unit_price\":\"300\",\"type\":2,\"remarks\":\"Return from PO-Return-202009231\",\"inv_id\":\"56\",\"issue\":\"Defect\"}]', '56', 3000, 1, 0, '2020-09-23 21:14:39', '2020-09-27 16:45:31'),
(5, 2, 'PO-Return-202009271', 1, '[{\"product_id\":\"3\",\"unit\":\"pcs\",\"qty\":\"5\",\"unit_price\":\"1500\",\"issue\":\"sadsad\"}]', '', 7500, 1, 0, '2020-09-27 17:49:22', '2020-09-27 17:49:51');

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
(5, 4, '2500', 'Sample', '2020-09-16', '2020-09-16 21:39:07'),
(6, 6, '2500', 'sample', '2020-09-22', '2020-09-22 18:06:20'),
(7, 7, '5000', 'asdasda', '2020-09-27', '2020-09-27 17:21:26'),
(8, 7, '1500', 'qweqwe', '2020-09-27', '2020-09-27 17:22:15');

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
(5, '552813637886\n', 'Bulk 2', 'Sample Bulk Product 2', 'box', 6, 1, 'pcs', 0, 1, '2020-09-16'),
(6, '764887592907\n', 'Sample 1', 'Sample', 'pcs', 0, 0, '', 0, 1, '2020-09-22'),
(7, '845196496256\n', 'Sample Prod', 'asdaasdasd', 'pcs', 0, 0, '', 0, 0, '2020-09-27');

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
  `payment_mode` varchar(100) NOT NULL,
  `user_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `ref_no`, `supplier_id`, `total_amount`, `status`, `payment_mode`, `user_id`, `date_created`, `date_updated`) VALUES
(15, '0001', 1, '40000', 2, '', 1, '2020-10-03 20:28:34', '2020-10-03 21:15:48'),
(16, '0001\n', 2, '20000', 0, '', 1, '2020-10-03 21:16:12', '2020-10-03 21:40:50'),
(17, '0002\n', 2, '20000', 1, '', 1, '2020-10-03 21:16:51', '2020-10-03 21:16:51');

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
(11, 15, 'PO-202010031', '98855467', '40000', 1, 'fax', '2020-10-03 20:32:43', '2020-10-03 20:32:43');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(30) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `customer_id` int(30) NOT NULL,
  `salesman_id` int(30) NOT NULL,
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
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dr_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `ref_no`, `customer_id`, `salesman_id`, `inventory_ids`, `discount_json`, `payment_mode`, `c_po_no`, `delivery_receipt_no`, `delivery_address`, `delivery_schedule`, `total_amount`, `status`, `user_id`, `date_created`, `date_updated`, `dr_no`) VALUES
(14, 'Sales-202010031', 1, 2, '[84,85]', '[{\"84\":\"0\"},{\"85\":\"0\"}]', 'Full', '', '', '', '2020-10-05', '11000', 1, 1, '2020-10-03 20:58:09', '2020-10-03 20:58:09', '1');

-- --------------------------------------------------------

--
-- Table structure for table `salesman`
--

CREATE TABLE `salesman` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `salesman`
--

INSERT INTO `salesman` (`id`, `firstname`, `middlename`, `lastname`, `status`, `date_created`, `date_updated`) VALUES
(2, 'Sample', 'S', 'Salesman', 1, '2020-09-29 22:21:24', '2020-09-29 22:22:05');

-- --------------------------------------------------------

--
-- Table structure for table `sales_returns`
--

CREATE TABLE `sales_returns` (
  `id` int(30) NOT NULL,
  `customer_id` int(30) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `action_type` tinyint(1) NOT NULL COMMENT '1= replace ,2 = refund',
  `data_json` text NOT NULL,
  `inv_ids` text NOT NULL,
  `total_amount` double NOT NULL,
  `user_id` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT ' 0 = Inactive ,2 = active',
  `salesman_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales_returns`
--

INSERT INTO `sales_returns` (`id`, `customer_id`, `ref_no`, `action_type`, `data_json`, `inv_ids`, `total_amount`, `user_id`, `status`, `salesman_id`, `date_created`, `date_updated`) VALUES
(3, 1, 'Sales-Return-202009221', 2, '[{\"product_id\":\"3\",\"qty\":\"2\",\"unit\":\"pcs\",\"unit_price\":\"2000\",\"type\":1,\"remarks\":\"Return from Sales-Return-202009221\",\"inv_id\":\"50\",\"issue\":\"Sample\"}]', '50', 4000, 1, 0, 2, '2020-09-22 22:50:39', '2020-10-03 20:24:57'),
(4, 2, 'Sales-Return-202009271', 1, '[{\"product_id\":\"3\",\"unit\":\"pcs\",\"qty\":\"10\",\"unit_price\":\"750\",\"issue\":\"asdasdasd\"}]', '', 7500, 1, 0, 2, '2020-09-27 17:40:05', '2020-09-29 23:01:58');

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
(4, 'Sample Supplier 4', 'Sample Supplier 4 Address', 'Supplier 4 Contact Person', '78212655200', 1, '2020-09-06 13:13:36'),
(5, 'Sample Supplier 5', 'sample', 'asdasd ', '654654', 0, '2020-09-27 17:12:16');

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
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=admin,2=staff',
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `status`, `type`, `date_updated`) VALUES
(1, 'Admin', '', 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1, 1, '2020-09-06 15:25:36'),
(2, 'Sample', '', 'Staff', 'staff', 'de9bf5643eabf80f4a56fda3bbb84483', 1, 2, '2020-09-27 15:30:46');

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
(3, 'po', 8, 'approved', 1, '', '2020-09-17 21:22:48'),
(4, 'po', 9, 'checked', 1, '', '2020-09-22 18:06:48'),
(5, 'po', 9, 'approved', 1, '', '2020-09-22 18:06:58'),
(6, 'po', 10, 'checked', 0, '', '2020-09-23 21:35:00'),
(7, 'po', 10, 'checked', 0, 'Sample Checker', '2020-09-23 21:35:22'),
(8, 'po', 10, 'approved', 1, '', '2020-09-23 21:35:33'),
(9, 'po', 12, 'checked', 2, '', '2020-09-27 17:43:31'),
(10, 'po', 12, 'checked', 1, '', '2020-09-27 17:44:23'),
(11, 'po', 12, 'approved', 2, '', '2020-09-27 17:44:45'),
(12, 'po', 15, 'checked', 1, '', '2020-10-03 20:29:31'),
(13, 'po', 15, 'approved', 1, '', '2020-10-03 20:29:39');

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
-- Indexes for table `logs`
--
ALTER TABLE `logs`
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
-- Indexes for table `po_payments`
--
ALTER TABLE `po_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `po_returns`
--
ALTER TABLE `po_returns`
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
-- Indexes for table `salesman`
--
ALTER TABLE `salesman`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_returns`
--
ALTER TABLE `sales_returns`
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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `po_items`
--
ALTER TABLE `po_items`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `po_payments`
--
ALTER TABLE `po_payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `po_returns`
--
ALTER TABLE `po_returns`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `price_list`
--
ALTER TABLE `price_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `receiving`
--
ALTER TABLE `receiving`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `salesman`
--
ALTER TABLE `salesman`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales_returns`
--
ALTER TABLE `sales_returns`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `validation`
--
ALTER TABLE `validation`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
