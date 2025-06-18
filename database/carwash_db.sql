-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 11:25 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `carwash_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'dan', 'uwumen20@gmail.com', '$2y$10$uYdlvJePlliQ1o86Yf.BJuu1H9r8lX5Fqu/.rxTL9YDEKRqqoF.uW', '2025-06-10 14:24:34'),
(6, 'alip', 'twint11@gmail.com', '$2y$10$13ab7uRha1FVnCE.89oeYOFOBpt9N2u4BBaSybhRvviBMEj8AcSY.', '2025-06-10 15:02:10'),
(9, 'udin', 'lipluv@gmail.com', '$2y$10$iNgoyYuMiOJeJp0RIsxDgOLsx/z5o4DX4fWW7OFeyRhvT0gR3MNnW', '2025-06-11 01:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `vehicle_type` enum('motor','mobil') NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `wash_type_id` int(11) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('cash','debit','qris') NOT NULL DEFAULT 'cash'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `employee_id`, `vehicle_type`, `license_plate`, `wash_type_id`, `transaction_date`, `payment_method`) VALUES
(2, 1, 'mobil', 'b 4567 eus', 6, '2025-06-10 14:40:37', 'cash'),
(3, 1, 'motor', 'b 2317 suv', 4, '2025-06-10 14:43:54', 'qris'),
(4, 6, 'motor', 'b 2317 suv', 4, '2025-06-10 15:02:52', 'cash'),
(5, 1, 'motor', 'b 2317 suv', 4, '2025-06-11 01:24:19', 'cash'),
(6, 1, 'mobil', 'a j 9', 8, '2025-06-11 01:25:06', 'debit');

-- --------------------------------------------------------

--
-- Table structure for table `wash_types`
--

CREATE TABLE `wash_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wash_types`
--

INSERT INTO `wash_types` (`id`, `name`, `description`, `price`) VALUES
(4, 'Cuci Motor Biasa', 'Pencucian standar untuk motor', '15000.00'),
(6, 'Cuci Biasa', 'Pencucian standar untuk kendaraan', '20000.00'),
(7, 'Cuci Premium', 'Pencucian premium dengan wax', '35000.00'),
(8, 'Cuci Detailing', 'Pencucian detail dengan interior', '50000.00'),
(10, 'Cuci Motor Premium', 'Pencucian premium untuk motor dengan wax', '25000.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `wash_type_id` (`wash_type_id`);

--
-- Indexes for table `wash_types`
--
ALTER TABLE `wash_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wash_types`
--
ALTER TABLE `wash_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`wash_type_id`) REFERENCES `wash_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
