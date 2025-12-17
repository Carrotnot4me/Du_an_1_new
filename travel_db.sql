-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 07:27 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` int NOT NULL,
  `customer_id` int NOT NULL,
  `checkin_time` datetime DEFAULT NULL,
  `status` enum('Đã checkin','Chưa checkin') DEFAULT 'Chưa checkin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_checkins_customer` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `fk_checkins_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 07:27 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `registrants_id` int DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `date`, `gender`, `registrants_id`, `note`) VALUES
(1, 'Tạ Vũ Trí', '2006-08-20', 'Nam', 1, 'Khách muốn thưởng thức đặc sản địa phương, hạn chế món công nghiệp.'),
(2, 'Nguyễn Mạnh Hùng', '2006-08-20', 'Nam', 1, 'Khách dị ứng hải sản, vui lòng không sắp xếp món liên quan'),
(3, 'Tạ Văn Trường', '2000-02-26', 'Nam', 2, NULL),
(4, 'Nguyễn Huy Hoàng', '2016-02-17', 'Nam', 2, NULL),
(5, 'Nguyễn Văn An', '1999-11-10', 'Nữ', 3, 'Tôi bị nguu'),
(6, 'Phạm Quốc Huy', '2004-05-18', 'Nam', 3, 'ưqfewfffffffasdFE'),
(7, 'Phan Ngọc Ánh', '2008-07-19', 'Nữ', 4, 'Khách sạn phải sạch sẽ'),
(8, 'Nguyễn Thu Trang', '2009-09-21', 'Nữ', 4, 'Muốn ngủ'),
(9, 'Nguyễn Thị Hồng', '2007-06-30', 'Nữ', 4, 'Không thích đi chơi'),
(10, 'Lưu Hoàng Phúc', '2006-06-16', 'Nam', 5, 'Không ở phòng gần thang máy vì khách nhạy cảm tiếng ồn.'),
(11, 'Phạm Nhật Minh', '1992-06-30', 'Nam', 5, 'Ưu tiên các điểm tham quan nhẹ nhàng, không leo núi nhiều'),
(12, 'Nguyễn Quang Vinh', '2004-02-19', 'Nam', 5, 'Kỷ niệm ngày đặc biệt của khách, mong có trải nghiệm bất ngờ nhỏ (nếu có thể).');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registrants_id` (`registrants_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_registrants` FOREIGN KEY (`registrants_id`) REFERENCES `booking_registrants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 07:28 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel`
--

-- --------------------------------------------------------

--
-- Table structure for table `departures`
--

CREATE TABLE `departures` (
  `id` varchar(10) NOT NULL,
  `tourId` varchar(10) DEFAULT NULL,
  `dateStart` date DEFAULT NULL,
  `dateEnd` date DEFAULT NULL,
  `meetingPoint` varchar(255) DEFAULT NULL,
  `guideId` varchar(10) DEFAULT NULL,
  `driverId` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departures`
--

INSERT INTO `departures` (`id`, `tourId`, `dateStart`, `dateEnd`, `meetingPoint`, `guideId`, `driverId`) VALUES
('1', '17', '2025-12-27', '2026-01-07', 'Sân Bay Tân Sơn Nhất', 'WBeUkHX', '9'),
('2', '11', '2025-12-10', '2025-12-20', 'Sân Bay Tân Sơn Nhất', '2', '10'),
('3', '14', '2025-12-19', '2025-12-24', 'Sân Bay Tân Sơn Nhất', '1', '10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departures`
--
ALTER TABLE `departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourId` (`tourId`),
  ADD KEY `guideId` (`guideId`),
  ADD KEY `departures_ibfk_3` (`driverId`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departures`
--
ALTER TABLE `departures`
  ADD CONSTRAINT `departures_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `departures_ibfk_2` FOREIGN KEY (`guideId`) REFERENCES `staffs` (`id`),
  ADD CONSTRAINT `departures_ibfk_3` FOREIGN KEY (`driverId`) REFERENCES `drivers` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 07:29 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` varchar(10) NOT NULL,
  `tourId` varchar(10) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `driverId` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `staffId` varchar(10) DEFAULT NULL,
  `departuresId` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tourId`, `status`, `driverId`, `staffId`, `departuresId`) VALUES
('1', '17', 'Sắp đi', '9', 'WBeUkHX', '1'),
('2', '11', 'Sắp đi', '10', '1', '2'),
('3', '14', 'Sắp đi', '10', '1', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourId` (`tourId`),
  ADD KEY `fk_bookings_driver` (`driverId`),
  ADD KEY `fk_bookings_staff` (`staffId`),
  ADD KEY `fk_bookings_departures` (`departuresId`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `fk_bookings_departures` FOREIGN KEY (`departuresId`) REFERENCES `departures` (`id`),
  ADD CONSTRAINT `fk_bookings_driver` FOREIGN KEY (`driverId`) REFERENCES `drivers` (`id`),
  ADD CONSTRAINT `fk_bookings_staff` FOREIGN KEY (`staffId`) REFERENCES `staffs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
