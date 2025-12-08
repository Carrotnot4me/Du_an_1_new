-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2025 at 02:47 PM
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
-- Database: `travel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` varchar(10) NOT NULL,
  `tourId` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `departureDate` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `travelType` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `total_amount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `tourId`, `email`, `phone`, `quantity`, `departureDate`, `status`, `travelType`, `type`, `total_amount`) VALUES
('1', '1', 'khachhang@gmail.com', '0909123456', 2, '2025-11-20', 'Đã cọc', 'Gia đình', 'Khách lẻ', 11000000),
('2', '1', 'nguyen@gmail.com', '0987654321', 3, '2025-11-20', 'Hoàn thành', 'Cá nhân', 'Khách lẻ', 16500000);

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
  `driver` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departures`
--

INSERT INTO `departures` (`id`, `tourId`, `dateStart`, `dateEnd`, `meetingPoint`, `guideId`, `driver`) VALUES
('1', '1', '2025-11-20', '2025-11-25', 'Sân bay Tân Sơn Nhất', '1', 'Lê Văn Lộc');

-- --------------------------------------------------------

--
-- Table structure for table `departure_notifications`
--

CREATE TABLE `departure_notifications` (
  `departure_id` varchar(10) NOT NULL,
  `notification_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departure_notifications`
--

INSERT INTO `departure_notifications` (`departure_id`, `notification_content`) VALUES
('1', 'Chưa xác nhận nhà hàng'),
('1', 'Đã xác nhận khách sạn');

-- --------------------------------------------------------

--
-- Table structure for table `departure_services`
--

CREATE TABLE `departure_services` (
  `departure_id` varchar(10) NOT NULL,
  `hotel` varchar(255) DEFAULT NULL,
  `restaurant` varchar(255) DEFAULT NULL,
  `transport` varchar(255) DEFAULT NULL,
  `flight` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `departure_services`
--

INSERT INTO `departure_services` (`departure_id`, `hotel`, `restaurant`, `transport`, `flight`) VALUES
('1', 'Khách sạn 4 sao Grand', 'Nhà hàng Biển Xanh', 'Xe 45 chỗ', 'VN012');

-- --------------------------------------------------------

--
-- Table structure for table `guideslogs`
--

CREATE TABLE `guideslogs` (
  `id` varchar(10) NOT NULL,
  `guideId` varchar(10) DEFAULT NULL,
  `tourId` varchar(10) DEFAULT NULL,
  `day` int DEFAULT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guideslogs`
--

INSERT INTO `guideslogs` (`id`, `guideId`, `tourId`, `day`, `content`) VALUES
('1', '1', '1', 1, 'Đón khách tại sân bay, di chuyển an toàn, thời tiết đẹp.');

-- --------------------------------------------------------

--
-- Table structure for table `guideslog_images`
--

CREATE TABLE `guideslog_images` (
  `log_id` varchar(10) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guideslog_images`
--

INSERT INTO `guideslog_images` (`log_id`, `image_url`) VALUES
('1', 'img/log_day1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` varchar(10) NOT NULL,
  `customerId` int DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `content` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `customerId`, `type`, `content`) VALUES
('1', 1, 'an_chay', 'Khách ăn chay trường – tránh món thịt, hải sản');

-- --------------------------------------------------------

--
-- Table structure for table `revenues`
--

CREATE TABLE `revenues` (
  `id` varchar(10) NOT NULL,
  `tourId` varchar(10) DEFAULT NULL,
  `revenue` int DEFAULT NULL,
  `expense` int DEFAULT NULL,
  `profit` int DEFAULT NULL,
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `revenues`
--

INSERT INTO `revenues` (`id`, `tourId`, `revenue`, `expense`, `profit`, `month`, `year`) VALUES
('1', '1', 25000000, 18000000, 7000000, 11, 2025);

-- --------------------------------------------------------

--
-- Table structure for table `staffs`
--

CREATE TABLE `staffs` (
  `id` varchar(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sex` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `certificate` varchar(255) DEFAULT NULL,
  `experience` varchar(50) DEFAULT NULL,
  `health` varchar(50) DEFAULT NULL,
  `toursLed` int DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staffs`
--

INSERT INTO `staffs` (`id`, `name`, `sex`, `email`, `phone`, `avatar`, `type`, `certificate`, `experience`, `health`, `toursLed`, `rating`) VALUES
('1', 'Trần Minh Hùng', 'Nam', 'tranminh354@gmail.com', '0901234567', 'https://i.pinimg.com/736x/14/23/86/1423864abc57f67290992f6c0d69c416.jpg', 'quoc_te', NULL, NULL, NULL, NULL, NULL),
('2', 'Nguyễn Thị Lan', 'Nữ', 'thilan344@gmail.com', '0902345678', 'https://i.pinimg.com/1200x/d6/86/13/d68613bbfa13dc302727bb7403325f78.jpg', 'noi_dia', 'HDV Nội địa', '5 năm', 'Tốt', 50, '4.6'),
('7c9wlnl', 'Trần Tuấn A', 'Nữ', 'admin@gmail.com', '0901234567', 'https://jbagy.me/wp-content/uploads/2025/03/Hinh-anh-avatar-nam-cute-2.jpg', 'noi_dia', NULL, NULL, NULL, NULL, NULL),
('WBeUkHX', 'Nguyễn Văn Đức Anh', 'Nam', 'nvducanh@gmail.com', '0987654321', 'https://jbagy.me/wp-content/uploads/2025/03/Hinh-anh-avatar-nam-cute-2.jpg', 'quoc_te', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff_languages`
--

CREATE TABLE `staff_languages` (
  `staff_id` varchar(10) NOT NULL,
  `language` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff_languages`
--

INSERT INTO `staff_languages` (`staff_id`, `language`) VALUES
('1', 'Anh'),
('1', 'Nhật'),
('2', 'Anh'),
('2', 'Pháp'),
('7c9wlnl', 'Đức'),
('7c9wlnl', 'Hàn'),
('WBeUkHX', 'Đức'),
('WBeUkHX', 'Hàn');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` varchar(10) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `tour_code` varchar(10) DEFAULT NULL,
  `main_destination` varchar(255) DEFAULT NULL,
  `short_description` text,
  `max_people` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `type`, `name`, `tour_code`, `main_destination`, `short_description`, `max_people`) VALUES
('1', 'Nội địa', 'Tour Miền Trung', 'ZK9T4MJD', 'Hồ Chí Minh', 'Hành trình 5 ngày khám phá trọn vẹn di sản miền Trung từ Đà Nẵng, Hội An, Huế đến Quảng Bình.', 30),
('2', 'Nội địa', 'Du lịch Nhật Bản', 'Q8R2LSXA', 'Osaka', 'Osaka, Nhật Bản, là thành phố sôi động nổi tiếng với ẩm thực đường phố, lâu đài lịch sử và trung tâm mua sắm nhộn nhịp.', 30),
('3', 'Quốc tế', 'Pháp', 'HN5C0BVE', 'Tháp Eiffel', 'Tháp Eiffel là biểu tượng nổi tiếng của Paris, Pháp, với kiến trúc thép cao vút và tầm nhìn toàn thành phố.', 30);

-- --------------------------------------------------------

--
-- Table structure for table `tour_images`
--

CREATE TABLE `tour_images` (
  `tour_id` varchar(10) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_images`
--

INSERT INTO `tour_images` (`tour_id`, `image_url`) VALUES
('1', 'https://statictuoitre.mediacdn.vn/thumb_w/640/2017/7-1512755474943.jpg'),
('2', 'https://upload.wikimedia.org/wikipedia/commons/9/9f/2018_Osaka_Castle_02.jpg'),
('3', 'https://ik.imagekit.io/tvlk/blog/2023/07/thap-eiffel-1-1024x678.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2');

-- --------------------------------------------------------

--
-- Table structure for table `tour_policies`
--

CREATE TABLE `tour_policies` (
  `tour_id` varchar(10) NOT NULL,
  `cancel_policy` text,
  `refund_policy` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_policies`
--

INSERT INTO `tour_policies` (`tour_id`, `cancel_policy`, `refund_policy`) VALUES
('1', 'Hủy trước 5 ngày hoàn 80%', 'Hoàn tiền trong 7 ngày làm việc'),
('2', 'Hoàn tiền 100% cho những ai mặc bệnh lý', 'Hoàn trả sớm nhất trong 7 ngày đầu '),
('3', 'Không hoàn trả', 'Không hoàn trả');

-- --------------------------------------------------------

--
-- Table structure for table `tour_prices`
--

CREATE TABLE `tour_prices` (
  `tour_id` varchar(10) NOT NULL,
  `adult_price` int DEFAULT NULL,
  `child_price` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_prices`
--

INSERT INTO `tour_prices` (`tour_id`, `adult_price`, `child_price`) VALUES
('1', 5500000, 3000000),
('2', 7500000, 4200000),
('3', 12000000, 8700000);

-- --------------------------------------------------------

--
-- Table structure for table `tour_schedules`
--

CREATE TABLE `tour_schedules` (
  `id` int NOT NULL,
  `tour_id` varchar(10) DEFAULT NULL,
  `day` int DEFAULT NULL,
  `activity` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_schedules`
--

INSERT INTO `tour_schedules` (`id`, `tour_id`, `day`, `activity`) VALUES
(1, '1', 1, 'Đà Nẵng – Ngũ Hành Sơn – Hội An: Đến Đà Nẵng, tham quan Ngũ Hành Sơn, tối khám phá Phố cổ Hội An.'),
(2, '1', 2, 'Bà Nà Hills: Cả ngày khám phá khu du lịch Bà Nà Hills, chiêm ngưỡng Cầu Vàng và làng Pháp.'),
(3, '1', 3, 'Huế (Đại Nội – Chùa Thiên Mụ): Di chuyển đến Huế, tham quan Đại Nội, Lăng Khải Định và Chùa Thiên Mụ. Tối đi thuyền Sông Hương.'),
(4, '1', 4, 'Động Phong Nha/Thiên Đường (Quảng Bình): Di chuyển đến Quảng Bình, khám phá Động Phong Nha hoặc Động Thiên Đường. Chiều về lại Đà Nẵng.'),
(5, '1', 5, 'Cù Lao Chàm – Tạm biệt: Buổi sáng đi Cù Lao Chàm, lặn ngắm san hô (hoặc tự do mua sắm). Chiều ra sân bay.'),
(6, '2', 1, 'Khởi hành đến Osaka – Nhận phòng khách sạn'),
(7, '2', 2, 'Tham quan Lâu đài Osaka (Osaka-jo) và Khu Dotonbori'),
(8, '2', 3, 'Tham quan Universal Studios Japan (USJ)'),
(9, '2', 4, 'Mua sắm tại Shinsaibashi-suji và khám phá Tháp Tsutenkaku'),
(10, '2', 5, 'Tự do và trở về Việt Nam'),
(11, '3', 1, 'Khởi hành đến London – Tham quan Cung điện Buckingham'),
(12, '3', 2, 'Khám phá Tháp London và Cầu Tháp (Tower Bridge)'),
(13, '3', 3, 'Tham quan Bảo tàng Anh (British Museum) và Quảng trường Trafalgar'),
(14, '3', 4, 'Đi tàu đến Paris – Tham quan Tháp Eiffel'),
(15, '3', 5, 'Bảo tàng Louvre và Khải Hoàn Môn'),
(16, '3', 6, 'Tự do mua sắm và trở về');

-- --------------------------------------------------------

--
-- Table structure for table `tour_suppliers`
--

CREATE TABLE `tour_suppliers` (
  `tour_id` varchar(10) NOT NULL,
  `hotel` varchar(255) DEFAULT NULL,
  `restaurant` varchar(255) DEFAULT NULL,
  `transport` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_suppliers`
--

INSERT INTO `tour_suppliers` (`tour_id`, `hotel`, `restaurant`, `transport`) VALUES
('1', 'Khách sạn 4 sao Grand', 'Nhà hàng Đà Thành', 'Xe 45 chỗ du lịch'),
('2', 'Golden Horizon Hotel', 'Lotus Garden Restaurant', 'Máy bay Boing 174'),
('3', 'Golden Horizon Hotel', 'Lotus Garden Restaurant', 'Máy bay Boing 174');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `phone`, `genre`, `role`, `avatar`) VALUES
('1', 'admin@gmail.com', '$2a$10$ldA978ZAxWW2DnGtdVv2IuIvlLFr7FXj/uJxJKI1SFa7e1pUsYnfq', 'Admin', '0912345678', 'Ẩn danh', 'admin', 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg'),
('2fdsVEe', 'tavantruog1702@gmail.com', '$2a$10$KWz2lzMxctjbgmxGT.Sj6OM8r52BK.ecDl3h16y27nwvXhi.Vtv/m', 'Tạ Văn Trường', '0362422418', 'Nam', 'user', 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg'),
('CjPp9fs', 'ddao51531@gmail.com', '$2a$10$b3qvXbmgAJ38kp1qE5BXZuNqUX5fDWAGRVtPo3ZmFIGiQf.xdrzRG', 'Đào Đức Anh', '0969778516', 'Nam', 'user', 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg'),
('G31noYO', 'tavantruog1702@gamil.com', '$2a$10$u/BNIA5OI245TtloCmVYl.KzP2L5dFk9XffxQ52TXFlEI4g7tybDa', 'Admin', '0123456789', 'Nữ', 'admin', 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourId` (`tourId`);

--
-- Indexes for table `departures`
--
ALTER TABLE `departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourId` (`tourId`),
  ADD KEY `guideId` (`guideId`);

--
-- Indexes for table `departure_notifications`
--
ALTER TABLE `departure_notifications`
  ADD PRIMARY KEY (`departure_id`,`notification_content`(255));

--
-- Indexes for table `departure_services`
--
ALTER TABLE `departure_services`
  ADD PRIMARY KEY (`departure_id`);

--
-- Indexes for table `guideslogs`
--
ALTER TABLE `guideslogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guideId` (`guideId`),
  ADD KEY `tourId` (`tourId`);

--
-- Indexes for table `guideslog_images`
--
ALTER TABLE `guideslog_images`
  ADD PRIMARY KEY (`log_id`,`image_url`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenues`
--
ALTER TABLE `revenues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourId` (`tourId`);

--
-- Indexes for table `staffs`
--
ALTER TABLE `staffs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `staff_languages`
--
ALTER TABLE `staff_languages`
  ADD PRIMARY KEY (`staff_id`,`language`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tour_code` (`tour_code`);

--
-- Indexes for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD PRIMARY KEY (`tour_id`,`image_url`);

--
-- Indexes for table `tour_policies`
--
ALTER TABLE `tour_policies`
  ADD PRIMARY KEY (`tour_id`);

--
-- Indexes for table `tour_prices`
--
ALTER TABLE `tour_prices`
  ADD PRIMARY KEY (`tour_id`);

--
-- Indexes for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `tour_suppliers`
--
ALTER TABLE `tour_suppliers`
  ADD PRIMARY KEY (`tour_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tours` (`id`);

--
-- Constraints for table `departures`
--
ALTER TABLE `departures`
  ADD CONSTRAINT `departures_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `departures_ibfk_2` FOREIGN KEY (`guideId`) REFERENCES `staffs` (`id`);

--
-- Constraints for table `departure_notifications`
--
ALTER TABLE `departure_notifications`
  ADD CONSTRAINT `departure_notifications_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`);

--
-- Constraints for table `departure_services`
--
ALTER TABLE `departure_services`
  ADD CONSTRAINT `departure_services_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`);

--
-- Constraints for table `guideslogs`
--
ALTER TABLE `guideslogs`
  ADD CONSTRAINT `guideslogs_ibfk_1` FOREIGN KEY (`guideId`) REFERENCES `staffs` (`id`),
  ADD CONSTRAINT `guideslogs_ibfk_2` FOREIGN KEY (`tourId`) REFERENCES `tours` (`id`);

--
-- Constraints for table `guideslog_images`
--
ALTER TABLE `guideslog_images`
  ADD CONSTRAINT `guideslog_images_ibfk_1` FOREIGN KEY (`log_id`) REFERENCES `guideslogs` (`id`);

--
-- Constraints for table `revenues`
--
ALTER TABLE `revenues`
  ADD CONSTRAINT `revenues_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tours` (`id`);

--
-- Constraints for table `staff_languages`
--
ALTER TABLE `staff_languages`
  ADD CONSTRAINT `staff_languages_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staffs` (`id`);

--
-- Constraints for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD CONSTRAINT `tour_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Constraints for table `tour_policies`
--
ALTER TABLE `tour_policies`
  ADD CONSTRAINT `tour_policies_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Constraints for table `tour_prices`
--
ALTER TABLE `tour_prices`
  ADD CONSTRAINT `tour_prices_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Constraints for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  ADD CONSTRAINT `tour_schedules_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Constraints for table `tour_suppliers`
--
ALTER TABLE `tour_suppliers`
  ADD CONSTRAINT `tour_suppliers_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
