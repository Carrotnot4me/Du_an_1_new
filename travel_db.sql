-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 08:31 PM
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
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_type` enum('khach_san','nha_hang','xe','may_bay','khac') NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `service_type`, `address`, `email`, `phone`, `website`, `description`, `logo`) VALUES
(1, 'Khách sạn Sunrise Đà Nẵng', 'khach_san', 'Sơn Trà, Đà Nẵng', 'contact@sunrisedanang.vn', '0901234567', 'https://sunrisedanang.vn', 'Khách sạn 4 sao gần biển, buffet sáng, hồ bơi, phòng hội nghị, phù hợp khách đoàn và gia đình.', NULL),
(2, 'Khách sạn Royal Lotus Hạ Long', 'khach_san', 'Bãi Cháy, TP. Hạ Long, Quảng Ninh', 'info@royallotushalong.vn', '0902345678', 'https://royallotushalong.vn', 'Khách sạn cao cấp gần vịnh Hạ Long, phòng rộng, nhà hàng sang trọng, tiện nghi hiện đại.', NULL),
(3, 'Resort Ocean View Phú Quốc', 'khach_san', 'Bãi Trường, Phú Quốc, Kiên Giang', 'booking@oceanviewphuquoc.vn', '0903456789', 'https://oceanviewphuquoc.vn', 'Resort 5 sao, bãi biển riêng, spa cao cấp, phù hợp tour nghỉ dưỡng và honeymoon.', NULL),
(4, 'Nhà hàng Biển Xanh', 'nha_hang', 'Trần Phú, TP. Nha Trang, Khánh Hòa', 'bienxanhrestaurant@gmail.com', '0904567890', 'https://bienxanhnhatrang.vn', 'Nhà hàng hải sản tươi sống, phục vụ đoàn khách lớn, thực đơn phong phú.', NULL),
(5, 'Nhà hàng Quê Việt', 'nha_hang', 'Quận 1, TP. Hồ Chí Minh', 'quevietrestaurant@gmail.com', '0905678901', 'https://queviet.vn', 'Ẩm thực truyền thống Việt Nam, không gian cổ điển, phù hợp khách quốc tế.', NULL),
(6, 'Nhà hàng Đồng Quê Hội An', 'nha_hang', 'Cẩm Châu, TP. Hội An, Quảng Nam', 'dongquehoian@gmail.com', '0906789012', 'https://dongquehoian.vn', 'Ẩm thực địa phương Hội An, phục vụ tour trải nghiệm văn hóa.', NULL),
(7, 'Xe du lịch Thành Công', 'xe', 'Quận 7, TP. Hồ Chí Minh', 'thanhcongcar@gmail.com', '0907890123', 'https://thanhcongtravelcar.vn', 'Cung cấp xe du lịch 16–45 chỗ, tài xế chuyên nghiệp, phục vụ tour dài ngày.', NULL),
(8, 'Xe du lịch Hoàng Gia', 'xe', 'Cầu Giấy, Hà Nội', 'hoanggiacar@gmail.com', '0908901234', 'https://hoanggiacar.vn', 'Dịch vụ xe cao cấp, xe đời mới, phục vụ tour miền Bắc và liên tỉnh.', NULL),
(9, 'Vietnam Airlines', 'may_bay', 'Long Biên, Hà Nội', 'support@vietnamairlines.com', '19001100', 'https://www.vietnamairlines.com', 'Hãng hàng không quốc gia Việt Nam, nhiều đường bay nội địa và quốc tế.', NULL),
(10, 'VietJet Air', 'may_bay', 'Tân Bình, TP. Hồ Chí Minh', 'customercare@vietjetair.com', '19001886', 'https://www.vietjetair.com', 'Hãng hàng không giá rẻ, tần suất bay cao, phù hợp tour tiết kiệm.', NULL),
(11, 'Bamboo Airways', 'may_bay', 'Quận Hoàn Kiếm, Hà Nội', 'support@bambooairways.com', '19001166', 'https://www.bambooairways.com', 'Hãng hàng không định hướng dịch vụ, chất lượng cao.', NULL),
(12, 'Khách sạn Mường Thanh Luxury', 'khach_san', 'Ngũ Hành Sơn, Đà Nẵng', 'muongthanh@muongthanh.vn', '02363666666', 'https://luxurydanang.muongthanh.com', 'Chuỗi khách sạn lớn, vị trí đẹp, phục vụ nhiều đoàn tour lớn.', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
