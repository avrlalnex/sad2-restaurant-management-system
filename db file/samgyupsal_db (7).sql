-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2024 at 04:26 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `samgyupsal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_account`
--

CREATE TABLE `admin_account` (
  `acc_id` int(11) NOT NULL,
  `acc_access` text NOT NULL,
  `acc_user` text NOT NULL,
  `acc_pass` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_account`
--

INSERT INTO `admin_account` (`acc_id`, `acc_access`, `acc_user`, `acc_pass`) VALUES
(1, 'Admin', '$2a$12$m9ayDoqhxXV4jYEHaRebaeEMWMuAcAWHLrHgGnIJGH065cA.YV0.u', '$2a$12$AqR1IuBdowjRLGLmfMb6derIsoXwMrfXVO8/PwuxW21.Abw5x.NYK'),
(2, 'Regular', '$2y$10$26FZFupcKEE6ro4SFLIq9uKA2s1k0lm15r1dOcDBm3vhFGRRZDepi', '$2y$10$rK8BCcLfJu17/Gavp1iDtul7EHzg2Qr2YjJjiWfVaQqFzczst36D6');

-- --------------------------------------------------------

--
-- Table structure for table `deductions`
--

CREATE TABLE `deductions` (
  `payroll_id` int(11) NOT NULL,
  `Philhealth` float NOT NULL,
  `SSS` float NOT NULL,
  `PAGIBIG` float NOT NULL,
  `taxes` float NOT NULL,
  `other_deduc` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deductions`
--

INSERT INTO `deductions` (`payroll_id`, `Philhealth`, `SSS`, `PAGIBIG`, `taxes`, `other_deduc`) VALUES
(73, 38706.1, 125795, 19353, 11224.8, 100),
(74, 8920, 28990, 4460, 2586.8, 9),
(75, 657.8, 2137.85, 328.9, 190.762, 0),
(76, 622.6, 2023.45, 311.3, 180.554, 0),
(77, 656, 2132, 328, 190.24, 0),
(78, 572, 1859, 286, 165.88, 0),
(79, 618, 2008.5, 309, 179.22, 0),
(80, 582.2, 1892.15, 291.1, 168.838, 0),
(81, 1, 2, 3, 4, 5),
(82, 8920, 28990, 4460, 2586.8, 222),
(83, 657.8, 2137.85, 328.9, 190.762, 33),
(84, 622.6, 2023.45, 311.3, 180.554, 444),
(85, 656, 2132, 328, 190.24, 555),
(86, 572, 1859, 286, 165.88, 66),
(87, 618, 2008.5, 309, 179.22, 7),
(88, 582.2, 1892.15, 291.1, 168.838, 88),
(89, 38706.1, 125795, 19353, 11224.8, 0),
(90, 8920, 28990, 4460, 2586.8, 222),
(91, 657.8, 2137.85, 328.9, 190.762, 33),
(92, 622.6, 2023.45, 311.3, 180.554, 444),
(93, 656, 2132, 328, 190.24, 555),
(94, 572, 1859, 286, 165.88, 66),
(95, 618, 2008.5, 309, 179.22, 7),
(96, 582.2, 1892.15, 291.1, 168.838, 88),
(97, 8920, 28990, 4460, 2586.8, 222),
(98, 657.8, 2137.85, 328.9, 190.762, 33),
(99, 622.6, 2023.45, 311.3, 180.554, 444),
(100, 656, 2132, 328, 190.24, 555),
(101, 572, 1859, 286, 165.88, 66),
(102, 618, 2008.5, 309, 179.22, 7),
(103, 582.2, 1892.15, 291.1, 168.838, 88),
(104, 1, 1, 1, 1, 1),
(105, 11, 11, 11, 11, 11),
(106, 38706.1, 125795, 19353, 11224.8, 0),
(107, 8920, 28990, 4460, 2586.8, 222),
(108, 657.8, 2137.85, 328.9, 190.762, 33),
(109, 622.6, 2023.45, 311.3, 180.554, 444),
(110, 656, 2132, 328, 190.24, 555),
(111, 572, 1859, 286, 165.88, 66),
(112, 618, 2008.5, 309, 179.22, 7),
(113, 582.2, 1892.15, 291.1, 168.838, 88),
(114, 8920, 28990, 4460, 2586.8, 222),
(115, 657.8, 2137.85, 328.9, 190.762, 33),
(116, 622.6, 2023.45, 311.3, 180.554, 444),
(117, 656, 2132, 328, 190.24, 555),
(118, 572, 1859, 286, 165.88, 66),
(119, 618, 2008.5, 309, 179.22, 7),
(120, 582.2, 1892.15, 291.1, 168.838, 88),
(121, 8920, 28990, 4460, 2586.8, 222),
(122, 657.8, 2137.85, 328.9, 190.762, 33),
(123, 622.6, 2023.45, 311.3, 180.554, 444),
(124, 656, 2132, 328, 190.24, 555),
(125, 572, 1859, 286, 165.88, 66),
(126, 618, 2008.5, 309, 179.22, 7),
(127, 582.2, 1892.15, 291.1, 168.838, 88),
(128, 38706.1, 125795, 19353, 11224.8, 100),
(129, 8920, 28990, 4460, 2586.8, 222),
(130, 657.8, 2137.85, 328.9, 190.762, 33),
(131, 622.6, 2023.45, 311.3, 180.554, 444),
(132, 656, 2132, 328, 190.24, 555),
(133, 572, 1859, 286, 165.88, 66),
(134, 618, 2008.5, 309, 179.22, 7),
(135, 582.2, 1892.15, 291.1, 168.838, 88);

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `lastname` text NOT NULL,
  `firstname` text NOT NULL,
  `position` text NOT NULL,
  `emp_status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `lastname`, `firstname`, `position`, `emp_status`) VALUES
(1, 'Admini', 'John', 'Administrator', 'Active'),
(18, 'Garcia', 'Jose', 'Manager', 'Active'),
(34, 'de la Cruz', 'Diana', 'Cook', 'Active'),
(35, 'Dagiling', 'Juan', 'Waiter', 'Active'),
(36, 'Calermo', 'Daryll', 'Cook', 'Active'),
(37, 'Fernandez', 'Guyver', 'Waiter', 'Active'),
(38, 'Quezon', 'Alfredo', 'Cashier', 'Active'),
(39, 'Toron', 'Camilla', 'Cashier', 'Active'),
(40, 'Dorgezo', 'Matil', 'Waiter', 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `employee_archive`
--

CREATE TABLE `employee_archive` (
  `archive_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date_archived` date NOT NULL,
  `date_reactivated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_archive`
--

INSERT INTO `employee_archive` (`archive_id`, `employee_id`, `date_archived`, `date_reactivated`) VALUES
(1, 38, '2023-12-03', '2023-12-03'),
(2, 37, '2023-11-27', '2023-11-27'),
(3, 39, '2023-12-05', '2023-12-05'),
(4, 1, '2023-12-05', '2023-12-05'),
(5, 40, '2023-12-07', '2023-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `employee_emergency_contact`
--

CREATE TABLE `employee_emergency_contact` (
  `employee_id` int(11) NOT NULL,
  `emrgncy_name` text NOT NULL,
  `emrgncy_cnum` bigint(20) NOT NULL,
  `emrgncy_address` text NOT NULL,
  `emrgncy_relation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_emergency_contact`
--

INSERT INTO `employee_emergency_contact` (`employee_id`, `emrgncy_name`, `emrgncy_cnum`, `emrgncy_address`, `emrgncy_relation`) VALUES
(1, 'Jane Admini', 9226784527, 'Room 213 Tower 1, Avida Towers, Roxas, DC', 'Spouse'),
(18, 'Marie Garcia', 9285553281, 'Colby Street B4 L2, Colby Subdv. Toril, DC', 'Mother'),
(34, 'Montoya de la Cruz', 9284139253, 'Block Lot, Barangay, District, DC', 'Spouse'),
(35, 'Tess Dagiling', 9221289437, 'Block Lot, Barangay, District, DC', 'Mother'),
(36, 'Karina Calermo', 9284335366, 'Block Lot, Barangay, District, DC', 'Spouse'),
(37, 'Karina Fernandez', 9286328463, 'Block Lot, Barangay, District, DC', 'Mother'),
(38, 'Maya Quezon', 9226245347, 'Block Lot, Barangay, District, DC', 'Spouse'),
(39, 'Matilda Toron', 9223613323, 'Block Lot, Barangay, District, DC', 'Mother'),
(40, 'Marie Dorgezo', 9277776632, 'Block Lot, Barangay, District, DC', 'Mother');

-- --------------------------------------------------------

--
-- Table structure for table `employee_profile`
--

CREATE TABLE `employee_profile` (
  `employee_id` int(11) NOT NULL,
  `profile_type` text NOT NULL,
  `profile_fullname` text NOT NULL,
  `profile_cnum` bigint(20) NOT NULL,
  `profile_fulladdress` text NOT NULL,
  `profile_bday` date NOT NULL,
  `profile_gender` text NOT NULL,
  `date_added` date NOT NULL,
  `last_updated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_profile`
--

INSERT INTO `employee_profile` (`employee_id`, `profile_type`, `profile_fullname`, `profile_cnum`, `profile_fulladdress`, `profile_bday`, `profile_gender`, `date_added`, `last_updated`) VALUES
(1, 'Admin', 'John Admini', 9223786597, 'Room 213 Tower 1, Avida Towers, Roxas, DC', '1992-07-31', 'Male', '2023-09-24', '2023-12-06'),
(18, 'Admin', 'Jose Garcia', 9284553388, 'Colby Street B4 L2, Colby Subdv. Toril, DC', '1993-02-25', 'Male', '2023-09-24', '2023-12-05'),
(34, 'Regular', 'Diana de la Cruz', 9286739873, 'Block Lot, Barangay, District, DC', '2002-09-25', 'Female', '2023-11-15', '2023-12-06'),
(35, 'Regular', 'Juan Dagiling', 9226489932, 'Block Lot, Barangay, District, DC', '2004-03-26', 'Male', '2023-11-27', '2023-12-06'),
(36, 'Regular', 'Daryll Calermo', 9284355364, 'Block Lot, Barangay, District, DC', '1997-02-19', 'Male', '2023-11-27', '2023-12-06'),
(37, 'Regular', 'Guyver Fernandez', 9286478201, 'Block Lot, Barangay, District, DC', '2003-07-02', 'Male', '2023-11-27', '2023-12-06'),
(38, 'Regular', 'Alfredo Quezon', 9226748392, 'Block Lot, Barangay, District, DC', '1998-01-30', 'Male', '2023-11-27', '2023-12-07'),
(39, 'Regular', 'Camilla Toron', 92283411641, 'Block Lot, Barangay, District, DC', '2000-06-21', 'Female', '2023-12-03', '2023-12-06'),
(40, 'Regular', 'Matil Dorgezo', 9274846632, 'Block Lot, Barangay, District, DC', '2003-11-04', 'Male', '2023-12-07', '2023-12-07');

-- --------------------------------------------------------

--
-- Table structure for table `goods_invoice`
--

CREATE TABLE `goods_invoice` (
  `goods_invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `invoice_no` text NOT NULL,
  `invoice_date` date NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goods_invoice`
--

INSERT INTO `goods_invoice` (`goods_invoice_id`, `order_id`, `invoice_no`, `invoice_date`, `is_archived`) VALUES
(44, 13, '953468', '2024-05-09', 0),
(45, 14, '953360', '2024-05-10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `goods_stockout_forms`
--

CREATE TABLE `goods_stockout_forms` (
  `goods_stockout_form_id` int(11) NOT NULL,
  `stock_reporter` text NOT NULL,
  `stock_desc` text NOT NULL,
  `stock_date` date NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goods_stockout_forms`
--

INSERT INTO `goods_stockout_forms` (`goods_stockout_form_id`, `stock_reporter`, `stock_desc`, `stock_date`, `is_archived`) VALUES
(32, 'Kristian D. Ragonton', 'Daily Stock-Out', '2024-05-09', 0),
(33, 'Kristian Ragonton', 'Test', '2024-05-09', 0),
(34, 'Guyver Fernandez', 'Daily Stock-Out', '2024-05-10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `goods_stock_in`
--

CREATE TABLE `goods_stock_in` (
  `goods_stockin_id` int(11) NOT NULL,
  `goods_invoice_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `stock_quantity` float NOT NULL,
  `active_quantity` float NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goods_stock_in`
--

INSERT INTO `goods_stock_in` (`goods_stockin_id`, `goods_invoice_id`, `item_id`, `stock_quantity`, `active_quantity`, `expiry_date`, `is_archived`) VALUES
(79, 44, 1, 500, 410, '2024-05-27', 0),
(80, 44, 3, 350, 320, '2024-05-27', 0),
(81, 44, 7, 400, 390, '2024-05-27', 0),
(82, 44, 9, 450, 440, '2024-05-27', 0),
(83, 45, 1, 100, 100, '2024-05-27', 0);

-- --------------------------------------------------------

--
-- Table structure for table `goods_stock_out`
--

CREATE TABLE `goods_stock_out` (
  `goods_stockout_id` int(11) NOT NULL,
  `goods_stockout_form_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `stock_quantity` float NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goods_stock_out`
--

INSERT INTO `goods_stock_out` (`goods_stockout_id`, `goods_stockout_form_id`, `item_id`, `stock_quantity`, `is_archived`) VALUES
(75, 32, 1, 20, 0),
(76, 32, 3, 30, 0),
(77, 32, 7, 10, 0),
(78, 32, 9, 10, 0),
(79, 33, 1, 40, 0),
(80, 34, 1, 30, 0);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` int(11) NOT NULL,
  `item_name` text NOT NULL,
  `item_type` text NOT NULL,
  `item_quantity` float NOT NULL,
  `item_unit` text NOT NULL,
  `item_restock` float NOT NULL,
  `is_archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `item_name`, `item_type`, `item_quantity`, `item_unit`, `item_restock`, `is_archived`) VALUES
(1, 'Pork', 'Goods', 510, 'kilogram/s', 100, 0),
(2, 'Plates', 'Supply', 0, 'piece/s', 34, 0),
(3, 'Beef', 'Goods', 320, 'kilogram/s', 100, 0),
(4, 'Spoon', 'Supply', 0, 'piece/s', 34, 0),
(5, 'Fork', 'Supply', 0, 'piece/s', 34, 0),
(7, 'Lean Pork', 'Goods', 390, 'kilogram/s', 100, 0),
(9, 'Chicken', 'Goods', 440, 'kilogram/s', 100, 0),
(10, 'Cabbage', 'Goods', 0, 'head/s', 20, 0),
(11, 'Soy Sauce', 'Supply', 0, 'pack/s', 20, 0),
(13, 'Metal Chopsticks', 'Supply', 0, 'pair/s', 44, 0),
(14, 'Lettuce', 'Goods', 0, 'piece/s', 30, 0),
(15, 'Mini Potato', 'Goods', 0, 'gram/s', 50, 0),
(16, 'Rice', 'Goods', 0, 'gram/s', 500, 0),
(17, 'Potato', 'Goods', 0, 'gram/s', 30, 0),
(18, 'Egg', 'Goods', 0, 'piece/s', 40, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_archive`
--

CREATE TABLE `item_archive` (
  `archive_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `date_archived` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_archive`
--

INSERT INTO `item_archive` (`archive_id`, `item_id`, `date_archived`) VALUES
(2, 4, '2023-10-15'),
(3, 2, '2023-10-17'),
(4, 3, '2023-10-17'),
(5, 7, '2023-12-05'),
(7, 9, '2023-12-05'),
(9, 10, '2023-12-05'),
(10, 15, '2023-12-07'),
(11, 17, '2024-05-07'),
(12, 18, '2024-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` float NOT NULL,
  `item_total_price` float NOT NULL,
  `is_archived` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `item_id`, `quantity`, `item_total_price`, `is_archived`) VALUES
(29, 13, 1, 500, 199500, 0),
(30, 13, 3, 350, 209650, 0),
(31, 13, 7, 400, 119600, 0),
(32, 13, 9, 450, 121500, 0),
(33, 14, 1, 100, 39900, 0),
(34, 15, 1, 100, 49900, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_slip`
--

CREATE TABLE `order_slip` (
  `order_id` int(11) NOT NULL,
  `order_no` text NOT NULL,
  `order_desc` text NOT NULL,
  `order_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `total_price` float NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_slip`
--

INSERT INTO `order_slip` (`order_id`, `order_no`, `order_desc`, `order_date`, `delivery_date`, `total_price`, `is_archived`) VALUES
(13, '953468', 'Mrs. Garcias Meats', '2024-05-09', '2024-05-09', 657750, 1),
(14, '953369', 'Mrs. Garcias Meats', '2024-05-09', '2024-05-10', 40900, 1),
(15, '953228', 'Mrs. Garcias Meats', '2024-05-09', '2024-05-20', 53900, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payroll_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `payroll_start_date` date NOT NULL,
  `payroll_end_date` date NOT NULL,
  `is_archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`payroll_id`, `employee_id`, `payroll_start_date`, `payroll_end_date`, `is_archived`) VALUES
(73, 1, '2024-05-01', '2024-05-31', 0),
(74, 18, '2024-05-01', '2024-05-31', 0),
(75, 34, '2024-05-01', '2024-05-31', 0),
(76, 35, '2024-05-01', '2024-05-31', 0),
(77, 36, '2024-05-01', '2024-05-31', 0),
(78, 37, '2024-05-01', '2024-05-31', 0),
(79, 38, '2024-05-01', '2024-05-31', 0),
(80, 39, '2024-05-01', '2024-05-31', 0),
(81, 1, '2024-06-01', '2024-06-30', 1),
(82, 18, '2024-04-01', '2024-04-30', 0),
(83, 34, '2024-04-01', '2024-04-30', 0),
(84, 35, '2024-04-01', '2024-04-30', 0),
(85, 36, '2024-04-01', '2024-04-30', 0),
(86, 37, '2024-04-01', '2024-04-30', 0),
(87, 38, '2024-04-01', '2024-04-30', 0),
(88, 39, '2024-04-01', '2024-04-30', 0),
(89, 1, '2024-06-01', '2024-06-30', 0),
(90, 18, '2024-06-01', '2024-06-30', 0),
(91, 34, '2024-06-01', '2024-06-30', 0),
(92, 35, '2024-06-01', '2024-06-30', 0),
(93, 36, '2024-06-01', '2024-06-30', 0),
(94, 37, '2024-06-01', '2024-06-30', 0),
(95, 38, '2024-06-01', '2024-06-30', 0),
(96, 39, '2024-06-01', '2024-06-30', 0),
(97, 18, '2024-07-01', '2024-07-30', 0),
(98, 34, '2024-07-01', '2024-07-30', 0),
(99, 35, '2024-07-01', '2024-07-30', 0),
(100, 36, '2024-07-01', '2024-07-30', 0),
(101, 37, '2024-07-01', '2024-07-30', 0),
(102, 38, '2024-07-01', '2024-07-30', 0),
(103, 39, '2024-07-01', '2024-07-30', 0),
(104, 1, '2024-05-01', '2024-05-31', 0),
(105, 1, '2024-05-01', '2024-05-31', 1),
(106, 1, '2024-03-01', '2024-03-30', 0),
(107, 18, '2024-03-01', '2024-03-30', 0),
(108, 34, '2024-03-01', '2024-03-30', 0),
(109, 35, '2024-03-01', '2024-03-30', 0),
(110, 36, '2024-03-01', '2024-03-30', 0),
(111, 37, '2024-03-01', '2024-03-30', 0),
(112, 38, '2024-03-01', '2024-03-30', 0),
(113, 39, '2024-03-01', '2024-03-30', 0),
(114, 18, '2024-06-01', '2024-04-30', 0),
(115, 34, '2024-06-01', '2024-04-30', 0),
(116, 35, '2024-06-01', '2024-04-30', 0),
(117, 36, '2024-06-01', '2024-04-30', 0),
(118, 37, '2024-06-01', '2024-04-30', 0),
(119, 38, '2024-06-01', '2024-04-30', 0),
(120, 39, '2024-06-01', '2024-04-30', 0),
(121, 18, '2024-02-01', '2024-02-29', 0),
(122, 34, '2024-02-01', '2024-02-29', 0),
(123, 35, '2024-02-01', '2024-02-29', 0),
(124, 36, '2024-02-01', '2024-02-29', 0),
(125, 37, '2024-02-01', '2024-02-29', 0),
(126, 38, '2024-02-01', '2024-02-29', 0),
(127, 39, '2024-02-01', '2024-02-29', 0),
(128, 1, '2024-01-01', '2024-01-30', 0),
(129, 18, '2024-01-01', '2024-01-30', 0),
(130, 34, '2024-01-01', '2024-01-30', 0),
(131, 35, '2024-01-01', '2024-01-30', 0),
(132, 36, '2024-01-01', '2024-01-30', 0),
(133, 37, '2024-01-01', '2024-01-30', 0),
(134, 38, '2024-01-01', '2024-01-30', 0),
(135, 39, '2024-01-01', '2024-01-30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_deduction_rates`
--

CREATE TABLE `payroll_deduction_rates` (
  `deduc_rate_id` int(11) NOT NULL,
  `deduc_type` text NOT NULL,
  `deduc_rate` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_deduction_rates`
--

INSERT INTO `payroll_deduction_rates` (`deduc_rate_id`, `deduc_type`, `deduc_rate`) VALUES
(1, 'Philhealth', 0.04),
(2, 'SSS', 0.13),
(3, 'PAGIBIG', 0.02),
(4, 'tax', 0.0116);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_details`
--

CREATE TABLE `payroll_details` (
  `payroll_id` int(11) NOT NULL,
  `payroll_salary` float NOT NULL,
  `regular_hours` int(11) NOT NULL,
  `payroll_bonus` float NOT NULL,
  `overtime_hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_details`
--

INSERT INTO `payroll_details` (`payroll_id`, `payroll_salary`, `regular_hours`, `payroll_bonus`, `overtime_hours`) VALUES
(73, 967652, 420, 0, 0),
(74, 223000, 480, 0, 0),
(75, 16445, 360, 0, 0),
(76, 15565, 300, 0, 0),
(77, 16400, 420, 0, 0),
(78, 14300, 240, 0, 0),
(79, 15450, 420, 0, 0),
(80, 14555, 360, 0, 0),
(81, 9, 9, 9, 0),
(82, 223000, 464, 8888, 8),
(83, 16445, 348, 7777, 7),
(84, 15565, 290, 6666, 6),
(85, 16400, 406, 5555, 5),
(86, 14300, 232, 4444, 4),
(87, 15450, 406, 3333, 3),
(88, 14555, 348, 2222, 2),
(89, 967652, 406, 0, 0),
(90, 223000, 464, 0, 0),
(91, 16445, 348, 0, 0),
(92, 15565, 290, 0, 0),
(93, 16400, 406, 0, 0),
(94, 14300, 232, 0, 0),
(95, 15450, 406, 0, 0),
(96, 14555, 348, 0, 0),
(97, 223000, 464, 0, 0),
(98, 16445, 348, 0, 0),
(99, 15565, 290, 0, 0),
(100, 16400, 406, 0, 0),
(101, 14300, 232, 0, 0),
(102, 15450, 406, 0, 0),
(103, 14555, 348, 0, 0),
(104, 111, 1, 1, 1),
(105, 1111, 11, 11, 11),
(106, 967652, 406, 0, 0),
(107, 223000, 464, 0, 0),
(108, 16445, 348, 0, 0),
(109, 15565, 290, 0, 0),
(110, 16400, 406, 0, 0),
(111, 14300, 232, 0, 0),
(112, 15450, 406, 0, 0),
(113, 14555, 348, 0, 0),
(114, 223000, 512, 0, 0),
(115, 16445, 384, 0, 0),
(116, 15565, 320, 0, 0),
(117, 16400, 448, 0, 0),
(118, 14300, 256, 0, 0),
(119, 15450, 448, 0, 0),
(120, 14555, 384, 0, 0),
(121, 223000, 448, 0, 0),
(122, 16445, 336, 0, 0),
(123, 15565, 280, 0, 0),
(124, 16400, 392, 0, 0),
(125, 14300, 224, 0, 0),
(126, 15450, 392, 0, 0),
(127, 14555, 336, 0, 0),
(128, 967652, 406, 3534.32, 5),
(129, 223000, 464, 0, 0),
(130, 16445, 348, 0, 0),
(131, 15565, 290, 0, 0),
(132, 16400, 406, 0, 0),
(133, 14300, 232, 0, 0),
(134, 15450, 406, 0, 0),
(135, 14555, 348, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_reports`
--

CREATE TABLE `payroll_reports` (
  `payroll_report_id` int(11) NOT NULL,
  `payroll_report_title` text NOT NULL,
  `report_total_amount` float NOT NULL,
  `report_floor_date` date NOT NULL,
  `report_ceiling_date` date NOT NULL,
  `is_archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_reports`
--

INSERT INTO `payroll_reports` (`payroll_report_id`, `payroll_report_title`, `report_total_amount`, `report_floor_date`, `report_ceiling_date`, `is_archived`) VALUES
(10, 'May 2024 Sales', 1024640, '2024-05-01', '2024-05-31', 0),
(13, 'April 2024 Payroll', 1064520, '2024-04-01', '2024-04-30', 1),
(14, 'June 2024 Report', 1024650, '2024-06-01', '2024-06-30', 0),
(15, 'July 2024 Report', 252067, '2024-07-01', '2024-07-30', 0),
(16, 'March 2024 Report', 1024640, '2024-03-01', '2024-03-30', 0),
(17, 'April 2024 Report', 252067, '2024-06-01', '2024-04-30', 0),
(18, 'January 2024 Report (Empty)', 0, '2024-01-01', '2024-01-31', 1),
(19, 'February 2024 Payrolls', 252067, '2024-02-01', '2024-02-29', 0),
(20, 'First Quarter Report', 1276710, '2024-01-01', '2024-03-31', 0),
(21, 'January 2024 Reports', 1028170, '2024-01-01', '2024-01-30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_report_salaries`
--

CREATE TABLE `payroll_report_salaries` (
  `report_details_id` int(11) NOT NULL,
  `payroll_report_id` int(11) NOT NULL,
  `payroll_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_report_salaries`
--

INSERT INTO `payroll_report_salaries` (`report_details_id`, `payroll_report_id`, `payroll_id`) VALUES
(104, 10, 73),
(105, 10, 74),
(106, 10, 75),
(107, 10, 76),
(108, 10, 77),
(109, 10, 78),
(110, 10, 79),
(111, 10, 80),
(128, 13, 81),
(129, 13, 82),
(130, 13, 83),
(131, 13, 84),
(132, 13, 85),
(133, 13, 86),
(134, 13, 87),
(135, 13, 88),
(136, 14, 81),
(137, 14, 89),
(138, 14, 90),
(139, 14, 91),
(140, 14, 92),
(141, 14, 93),
(142, 14, 94),
(143, 14, 95),
(144, 14, 96),
(145, 15, 97),
(146, 15, 98),
(147, 15, 99),
(148, 15, 100),
(149, 15, 101),
(150, 15, 102),
(151, 15, 103),
(152, 16, 106),
(153, 16, 107),
(154, 16, 108),
(155, 16, 109),
(156, 16, 110),
(157, 16, 111),
(158, 16, 112),
(159, 16, 113),
(160, 17, 114),
(161, 17, 115),
(162, 17, 116),
(163, 17, 117),
(164, 17, 118),
(165, 17, 119),
(166, 17, 120),
(205, 19, 121),
(206, 19, 122),
(207, 19, 123),
(208, 19, 124),
(209, 19, 125),
(210, 19, 126),
(211, 19, 127),
(212, 20, 106),
(213, 20, 107),
(214, 20, 108),
(215, 20, 109),
(216, 20, 110),
(217, 20, 111),
(218, 20, 112),
(219, 20, 113),
(220, 20, 121),
(221, 20, 122),
(222, 20, 123),
(223, 20, 124),
(224, 20, 125),
(225, 20, 126),
(226, 20, 127),
(227, 21, 128),
(228, 21, 129),
(229, 21, 130),
(230, 21, 131),
(231, 21, 132),
(232, 21, 133),
(233, 21, 134),
(234, 21, 135);

-- --------------------------------------------------------

--
-- Table structure for table `salary_details`
--

CREATE TABLE `salary_details` (
  `employee_id` int(11) NOT NULL,
  `salary` float NOT NULL,
  `regular_hours` int(11) NOT NULL,
  `severance` float NOT NULL,
  `total_deduction` float NOT NULL,
  `deduction_per_sal` float NOT NULL,
  `bonus_pay` float NOT NULL,
  `bonus_hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salary_details`
--

INSERT INTO `salary_details` (`employee_id`, `salary`, `regular_hours`, `severance`, `total_deduction`, `deduction_per_sal`, `bonus_pay`, `bonus_hours`) VALUES
(1, 967652, 14, 967652, 900, 100, 0, 0),
(18, 223000, 16, 2230000, 668, 222, 0, 0),
(34, 16445, 12, 164450, 3102, 33, 0, 0),
(35, 15565, 10, 155650, 1336, 444, 0, 0),
(36, 16400, 14, 164000, 1670, 555, 0, 0),
(37, 14300, 8, 143000, 6204, 66, 0, 0),
(38, 15450, 14, 154500, 7728, 7, 0, 0),
(39, 14555, 12, 145550, 8272, 88, 0, 0),
(40, 20000, 16, 200000, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_customers`
--

CREATE TABLE `sales_customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` text NOT NULL,
  `customer_type` text NOT NULL,
  `customer_loyalty` int(11) NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_customers`
--

INSERT INTO `sales_customers` (`customer_id`, `customer_name`, `customer_type`, `customer_loyalty`, `is_archived`) VALUES
(1, 'Fredrico R. Rodrigue', 'Regular', 5, 0),
(10, 'Juanito T. Carmen', 'Not Regular', 2, 0),
(11, 'Natalie G. Fernandez', 'Not Regular', 1, 0),
(12, 'Ken E. Fatima', 'Not Regular', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_customer_loyalty_upgrade`
--

CREATE TABLE `sales_customer_loyalty_upgrade` (
  `loyalty_id` int(11) NOT NULL,
  `threshold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_customer_loyalty_upgrade`
--

INSERT INTO `sales_customer_loyalty_upgrade` (`loyalty_id`, `threshold`) VALUES
(1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sales_products`
--

CREATE TABLE `sales_products` (
  `product_id` int(11) NOT NULL,
  `is_addon` tinyint(1) NOT NULL,
  `product_name` text NOT NULL,
  `product_price` float NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `is_archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_products`
--

INSERT INTO `sales_products` (`product_id`, `is_addon`, `product_name`, `product_price`, `is_default`, `is_archived`) VALUES
(1, 0, 'Unlimited Regular', 699, 0, 0),
(2, 1, 'Gyeran Ramyun', 150, 0, 0),
(3, 0, 'Unlimited Promo', 599, 1, 0),
(4, 1, 'Rabokki', 250, 0, 0),
(5, 1, 'Gyeran Mari', 230, 0, 0),
(6, 1, 'Soft Drinks', 85, 0, 0),
(7, 1, 'Beer', 95, 0, 0),
(8, 1, 'Soju', 220, 0, 0),
(9, 1, 'Bottled Water', 60, 0, 0),
(10, 1, 'Tanduay Rum', 190, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_receipts`
--

CREATE TABLE `sales_receipts` (
  `receipt_id` int(11) NOT NULL,
  `receipt_no` text NOT NULL,
  `customer_id` int(11) NOT NULL,
  `receipt_pax` int(11) NOT NULL,
  `total_amount` float NOT NULL,
  `discount_type` text NOT NULL,
  `receipt_date` date NOT NULL,
  `senior_pax` int(11) NOT NULL,
  `price_per_pax` float NOT NULL,
  `table_no` int(11) NOT NULL,
  `is_archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_receipts`
--

INSERT INTO `sales_receipts` (`receipt_id`, `receipt_no`, `customer_id`, `receipt_pax`, `total_amount`, `discount_type`, `receipt_date`, `senior_pax`, `price_per_pax`, `table_no`, `is_archived`) VALUES
(28, '05222', 10, 2, 2222, 'Senior', '2024-05-02', 2, 599, 2, 0),
(29, '05334', 10, 4, 3357.71, 'Senior', '2024-05-05', 1, 599, 3, 0),
(30, '05335', 11, 4, 3696, 'Not Discounted', '2024-05-05', 0, 599, 2, 0),
(31, '05336', 12, 3, 2897, 'Not', '2024-05-09', 0, 599, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_receipt_products`
--

CREATE TABLE `sales_receipt_products` (
  `receipt_product_id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_total_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_receipt_products`
--

INSERT INTO `sales_receipt_products` (`receipt_product_id`, `receipt_id`, `product_id`, `product_quantity`, `product_total_price`) VALUES
(5, 28, 3, 3, 1797),
(6, 28, 2, 2, 150),
(7, 28, 7, 2, 222),
(8, 28, 5, 1, 230),
(9, 28, 8, 3, 660),
(10, 29, 3, 4, 2396),
(11, 29, 7, 4, 380),
(12, 29, 8, 2, 440),
(13, 29, 9, 1, 60),
(14, 29, 6, 4, 340),
(15, 30, 3, 4, 2396),
(16, 30, 4, 1, 250),
(17, 30, 8, 4, 880),
(18, 30, 6, 2, 170),
(20, 31, 3, 3, 1797),
(21, 31, 7, 5, 1100);

-- --------------------------------------------------------

--
-- Table structure for table `sales_reports`
--

CREATE TABLE `sales_reports` (
  `sales_report_id` int(11) NOT NULL,
  `sales_report_title` text NOT NULL,
  `sales_report_total` float NOT NULL,
  `sales_report_date` date NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_reports`
--

INSERT INTO `sales_reports` (`sales_report_id`, `sales_report_title`, `sales_report_total`, `sales_report_date`, `is_archived`) VALUES
(18, 'May 5 2025 Sales', 10140.7, '2024-05-05', 0),
(19, 'May 8 2024 Sales', 0, '2024-05-08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_report_receipts`
--

CREATE TABLE `sales_report_receipts` (
  `rep_rec_id` int(11) NOT NULL,
  `sales_report_id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_report_receipts`
--

INSERT INTO `sales_report_receipts` (`rep_rec_id`, `sales_report_id`, `receipt_id`) VALUES
(41, 18, 28),
(42, 18, 29),
(43, 18, 30);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_account`
--
ALTER TABLE `admin_account`
  ADD PRIMARY KEY (`acc_id`);

--
-- Indexes for table `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`payroll_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `employee_archive`
--
ALTER TABLE `employee_archive`
  ADD PRIMARY KEY (`archive_id`),
  ADD KEY `FOREIGN_employee_archive_id` (`employee_id`);

--
-- Indexes for table `employee_emergency_contact`
--
ALTER TABLE `employee_emergency_contact`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `employee_profile`
--
ALTER TABLE `employee_profile`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `FOREIGN_employee_id` (`employee_id`) USING BTREE;

--
-- Indexes for table `goods_invoice`
--
ALTER TABLE `goods_invoice`
  ADD PRIMARY KEY (`goods_invoice_id`),
  ADD KEY `FOREIGN_invoice_order_id` (`order_id`);

--
-- Indexes for table `goods_stockout_forms`
--
ALTER TABLE `goods_stockout_forms`
  ADD PRIMARY KEY (`goods_stockout_form_id`);

--
-- Indexes for table `goods_stock_in`
--
ALTER TABLE `goods_stock_in`
  ADD PRIMARY KEY (`goods_stockin_id`),
  ADD KEY `FOREIGN_itemid_goods_stockin` (`item_id`),
  ADD KEY `FOREIGN_invoiceid_goods` (`goods_invoice_id`);

--
-- Indexes for table `goods_stock_out`
--
ALTER TABLE `goods_stock_out`
  ADD PRIMARY KEY (`goods_stockout_id`),
  ADD KEY `FOREIGN_stockoutform_id_goods` (`goods_stockout_form_id`),
  ADD KEY `FOREIGN_item_id_goodsstockout` (`item_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_archive`
--
ALTER TABLE `item_archive`
  ADD PRIMARY KEY (`archive_id`),
  ADD KEY `FOREIGN_KEY_item_id` (`item_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `FOREIGN_order_item_order_slip_id` (`order_id`),
  ADD KEY `FOREIGN_item_order_item_item_id` (`item_id`);

--
-- Indexes for table `order_slip`
--
ALTER TABLE `order_slip`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`),
  ADD KEY `FOREIGN_employee_id_payroll` (`employee_id`);

--
-- Indexes for table `payroll_deduction_rates`
--
ALTER TABLE `payroll_deduction_rates`
  ADD PRIMARY KEY (`deduc_rate_id`);

--
-- Indexes for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD PRIMARY KEY (`payroll_id`);

--
-- Indexes for table `payroll_reports`
--
ALTER TABLE `payroll_reports`
  ADD PRIMARY KEY (`payroll_report_id`);

--
-- Indexes for table `payroll_report_salaries`
--
ALTER TABLE `payroll_report_salaries`
  ADD PRIMARY KEY (`report_details_id`),
  ADD KEY `FOREIGN_payroll_report_rep_id` (`payroll_report_id`),
  ADD KEY `FOREIGN_payroll_report_payroll_id` (`payroll_id`);

--
-- Indexes for table `salary_details`
--
ALTER TABLE `salary_details`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `sales_customers`
--
ALTER TABLE `sales_customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `sales_customer_loyalty_upgrade`
--
ALTER TABLE `sales_customer_loyalty_upgrade`
  ADD PRIMARY KEY (`loyalty_id`);

--
-- Indexes for table `sales_products`
--
ALTER TABLE `sales_products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `sales_receipts`
--
ALTER TABLE `sales_receipts`
  ADD PRIMARY KEY (`receipt_id`);

--
-- Indexes for table `sales_receipt_products`
--
ALTER TABLE `sales_receipt_products`
  ADD PRIMARY KEY (`receipt_product_id`),
  ADD KEY `FOREIGN_receipt_id_products` (`receipt_id`),
  ADD KEY `FOREIGN_product_id_products` (`product_id`);

--
-- Indexes for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD PRIMARY KEY (`sales_report_id`);

--
-- Indexes for table `sales_report_receipts`
--
ALTER TABLE `sales_report_receipts`
  ADD PRIMARY KEY (`rep_rec_id`),
  ADD KEY `FOREIGN_receipt_id_reprec` (`receipt_id`),
  ADD KEY `FOREIGN_report_id_reprec` (`sales_report_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_account`
--
ALTER TABLE `admin_account`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `employee_archive`
--
ALTER TABLE `employee_archive`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `goods_invoice`
--
ALTER TABLE `goods_invoice`
  MODIFY `goods_invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `goods_stockout_forms`
--
ALTER TABLE `goods_stockout_forms`
  MODIFY `goods_stockout_form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `goods_stock_in`
--
ALTER TABLE `goods_stock_in`
  MODIFY `goods_stockin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `goods_stock_out`
--
ALTER TABLE `goods_stock_out`
  MODIFY `goods_stockout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `item_archive`
--
ALTER TABLE `item_archive`
  MODIFY `archive_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_slip`
--
ALTER TABLE `order_slip`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `payroll_deduction_rates`
--
ALTER TABLE `payroll_deduction_rates`
  MODIFY `deduc_rate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_reports`
--
ALTER TABLE `payroll_reports`
  MODIFY `payroll_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `payroll_report_salaries`
--
ALTER TABLE `payroll_report_salaries`
  MODIFY `report_details_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT for table `sales_customers`
--
ALTER TABLE `sales_customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales_customer_loyalty_upgrade`
--
ALTER TABLE `sales_customer_loyalty_upgrade`
  MODIFY `loyalty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales_products`
--
ALTER TABLE `sales_products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales_receipts`
--
ALTER TABLE `sales_receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sales_receipt_products`
--
ALTER TABLE `sales_receipt_products`
  MODIFY `receipt_product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sales_reports`
--
ALTER TABLE `sales_reports`
  MODIFY `sales_report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sales_report_receipts`
--
ALTER TABLE `sales_report_receipts`
  MODIFY `rep_rec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `deductions`
--
ALTER TABLE `deductions`
  ADD CONSTRAINT `FOREIGN_payroll_id_deduc` FOREIGN KEY (`payroll_id`) REFERENCES `payroll` (`payroll_id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_archive`
--
ALTER TABLE `employee_archive`
  ADD CONSTRAINT `FOREIGN_employee_archive_id` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `employee_emergency_contact`
--
ALTER TABLE `employee_emergency_contact`
  ADD CONSTRAINT `FOREIGN_employee_id_emergencycontact` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `employee_profile`
--
ALTER TABLE `employee_profile`
  ADD CONSTRAINT `FOREIGN_employee_id` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `goods_invoice`
--
ALTER TABLE `goods_invoice`
  ADD CONSTRAINT `FOREIGN_invoice_order_id` FOREIGN KEY (`order_id`) REFERENCES `order_slip` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_stock_in`
--
ALTER TABLE `goods_stock_in`
  ADD CONSTRAINT `FOREIGN_invoiceid_goods` FOREIGN KEY (`goods_invoice_id`) REFERENCES `goods_invoice` (`goods_invoice_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FOREIGN_itemid_goods_stockin` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_stock_out`
--
ALTER TABLE `goods_stock_out`
  ADD CONSTRAINT `FOREIGN_item_id_goodsstockout` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FOREIGN_stockoutform_id_goods` FOREIGN KEY (`goods_stockout_form_id`) REFERENCES `goods_stockout_forms` (`goods_stockout_form_id`) ON DELETE CASCADE;

--
-- Constraints for table `item_archive`
--
ALTER TABLE `item_archive`
  ADD CONSTRAINT `FOREIGN_KEY_item_id` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `FOREIGN_item_order_item_item_id` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FOREIGN_order_item_order_slip_id` FOREIGN KEY (`order_id`) REFERENCES `order_slip` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `FOREIGN_employee_id_payroll` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD CONSTRAINT `FOREIGN_payroll_id_details` FOREIGN KEY (`payroll_id`) REFERENCES `payroll` (`payroll_id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_report_salaries`
--
ALTER TABLE `payroll_report_salaries`
  ADD CONSTRAINT `FOREIGN_payroll_report_payroll_id` FOREIGN KEY (`payroll_id`) REFERENCES `payroll` (`payroll_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FOREIGN_payroll_report_rep_id` FOREIGN KEY (`payroll_report_id`) REFERENCES `payroll_reports` (`payroll_report_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `salary_details`
--
ALTER TABLE `salary_details`
  ADD CONSTRAINT `FOREIGN_employee_id_salary` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);

--
-- Constraints for table `sales_receipt_products`
--
ALTER TABLE `sales_receipt_products`
  ADD CONSTRAINT `FOREIGN_product_id_products` FOREIGN KEY (`product_id`) REFERENCES `sales_products` (`product_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FOREIGN_receipt_id_products` FOREIGN KEY (`receipt_id`) REFERENCES `sales_receipts` (`receipt_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `sales_report_receipts`
--
ALTER TABLE `sales_report_receipts`
  ADD CONSTRAINT `FOREIGN_receipt_id_reprec` FOREIGN KEY (`receipt_id`) REFERENCES `sales_receipts` (`receipt_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FOREIGN_report_id_reprec` FOREIGN KEY (`sales_report_id`) REFERENCES `sales_reports` (`sales_report_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
