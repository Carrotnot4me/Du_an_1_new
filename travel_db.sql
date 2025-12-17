-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 06:13 PM
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
('1', '17', 'Sắp đi', '9', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `booking_registrants`
--

CREATE TABLE `booking_registrants` (
  `id` int NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `quantity` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_registrants`
--

INSERT INTO `booking_registrants` (`id`, `booking_id`, `name`, `email`, `phone`, `type`, `quantity`, `status`) VALUES
(1, '1', 'Tạ Văn Trường', 'tavantruog1702@gmail.com', '0362422418', 'Cá nhân', '2', 'Đã hoàn thành'),
(2, '1', 'Đào Đức Anh', 'ddao20@gmail.com', '0', 'Gia đình', '2', 'Đã cọc'),
(3, '1', 'Nguyen Van A', 'tathithuhoai2000@gmail.com', '0912345678', 'Cá nhân', '2', 'Chờ xác nhận');

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
(6, 'Phạm Quốc Huy', '2004-05-18', 'Nam', 3, 'ưqfewfffffffasdFE');

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
('1', '17', '2025-12-27', '2026-01-07', 'Sân Bay Tân Sơn Nhất', NULL, '9');

-- --------------------------------------------------------

--
-- Table structure for table `departure_notifications`
--

CREATE TABLE `departure_notifications` (
  `departure_id` varchar(10) NOT NULL,
  `notification_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`) VALUES
('1', 'Nguyễn Văn Tài'),
('10', 'Hoàng Đình Khoa'),
('2', 'Trần Quốc Lộ'),
('3', 'Phạm Hữu Lái'),
('4', 'Phan Quang Hậu'),
('5', 'Nguyễn Đức Tâm'),
('6', 'Trần Minh Thành'),
('7', 'Bùi Văn Khải'),
('8', 'Mai Quốc Hưng'),
('9', 'Lương Tấn Phát');

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

-- --------------------------------------------------------

--
-- Table structure for table `guideslog_images`
--

CREATE TABLE `guideslog_images` (
  `log_id` varchar(10) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `id` int NOT NULL,
  `registrant_id` int NOT NULL,
  `amount` int NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`id`, `registrant_id`, `amount`, `note`, `created_at`) VALUES
(7, 1, 5994000, NULL, '2025-12-17 12:33:05'),
(8, 1, 13986000, NULL, '2025-11-10 14:50:46'),
(9, 2, 5994000, NULL, '2025-12-17 15:29:32');

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
('10', 'Quốc tế', 'Châu Âu 4 nước: Pháp - Bỉ - Hà Lan - Đức', 'QT-1005', 'Paris - Brussels - Amsterdam - Cologne', 'Paris – Brussels – Amsterdam – Cologne là hành trình khám phá Châu Âu đầy ấn tượng, khởi đầu với Paris lãng mạn, tháp Eiffel biểu tượng; tiếp đến Brussels với quảng trường Grand Place và hương vị socola trứ danh; Amsterdam mang nét tự do, kênh đào thơ mộng và xe đạp đặc trưng; cuối cùng là Cologne, thành phố cổ kính nổi bật với nhà thờ Gothic tráng lệ, tạo nên chuyến đi vừa văn hóa vừa nghệ thuật.', 40),
('11', 'Nội địa', 'Đà Lạt – Hoa Anh Đào – Langbiang', 'VN-3001', 'Đà Lạt', 'Đà Lạt là thành phố ngàn hoa nằm trên cao nguyên Lâm Viên với khí hậu mát mẻ quanh năm, bao phủ bởi rừng thông, hồ nước và những con đường dốc lãng mạn. Nơi đây hấp dẫn du khách bởi vườn hoa rực rỡ, quán cà phê view núi mờ sương, kiến trúc Pháp cổ kính cùng không gian bình yên, thích hợp nghỉ dưỡng, săn mây và trải nghiệm ẩm thực đặc trưng như bánh tráng nướng, sữa đậu nành nóng.', 80),
('12', 'Nội địa', 'Phú Quốc', 'VN-3002', 'Phú Quốc', 'Phú Quốc là đảo ngọc của Việt Nam với biển xanh trong, bãi cát trắng mịn và những khu nghỉ dưỡng sang trọng. Du khách có thể lặn ngắm san hô, ngắm hoàng hôn ở Sunset Sanato, thăm làng chài Hàm Ninh, khám phá VinWonders – Safari hay trải nghiệm cáp treo Hòn Thơm. Không khí biển trong lành, hải sản tươi ngon và cảnh quan đẹp khiến Phú Quốc trở thành điểm nghỉ dưỡng lý tưởng.', 70),
('13', 'Nội địa', 'Hà Nội – Tràng An – Bái Đính – Hạ Long', 'VN-3003', 'Ninh Bình – Hạ Long', 'Ninh Bình – Hạ Long là hành trình khám phá miền Bắc với vẻ đẹp non nước hữu tình. Ninh Bình nổi bật với Tràng An, Tam Cốc, hang động kỳ vĩ và những cánh đồng lúa ven sông thơ mộng. Tiếp đến Hạ Long gây ấn tượng với vịnh biển xanh ngọc, hàng nghìn đảo đá vôi dựng đứng và du thuyền sang trọng. Chuyến đi kết hợp văn hóa – thiên nhiên đầy thư thái và trải nghiệm đáng nhớ.', 75),
('14', 'Theo yêu cầu', 'Lào', 'L-1778', 'Viêng Chăn', 'Viêng Chăn là thủ đô yên bình của Lào, mang vẻ đẹp pha trộn giữa truyền thống Phật giáo và nét hiện đại nhẹ nhàng. Thành phố nổi tiếng với tháp vàng Pha That Luang linh thiêng, Khải Hoàn Môn Patuxay độc đáo cùng nhịp sống chậm rãi, hiền hòa bên dòng sông Mê Kông. Du khách đến đây có thể tham quan chùa chiền cổ kính, thưởng thức ẩm thực Lào đậm đà và trải nghiệm văn hóa giản dị, gần gũi.', 1),
('15', 'Nội địa', 'Huế – La Vang – Động Phong Nha', 'VN-3004', 'Huế – Quảng Bình', 'Huế – Quảng Bình là hành trình khám phá dải đất miền Trung với vẻ đẹp trầm mặc giàu giá trị lịch sử và thiên nhiên kỳ vĩ. Huế quyến rũ với Đại Nội cổ kính, lăng tẩm vua Nguyễn, sông Hương – cầu Trường Tiền thơ mộng, cùng văn hóa cung đình và ẩm thực đậm đà. Tiếp nối, Quảng Bình mang đến trải nghiệm khám phá thiên nhiên hoang sơ với Phong Nha – Kẻ Bàng, hang động hùng vĩ và bãi biển Nhật Lệ trong xanh. Chuyến đi mang lại cảm giác vừa hoài niệm vừa phiêu lưu.', 65),
('16', 'Nội địa', 'Quy Nhơn – Kỳ Co – Eo Gió – Ghềnh', 'VN-3005', 'Quy Nhơn', 'Quy Nhơn là thành phố biển miền Trung nổi tiếng với vẻ đẹp hoang sơ, nước trong xanh và đường bờ biển cong mềm thơ mộng. Nơi đây có Eo Gió hùng vĩ, Kỳ Co xanh ngọc, ghềnh đá và những đồi cát vàng trải dài. Du khách có thể tham quan tháp Chăm cổ kính, thưởng thức hải sản tươi ngon, check-in biển đảo, tận hưởng không khí bình yên và những buổi hoàng hôn đẹp mê mẩn.', 0),
('17', 'Quốc tế', 'Campuchia: Phnom Penh – Siem Reap', 'QT-4001', 'Phnom Penh – Siem Reap', 'Phnom Penh – Siem Reap là hành trình khám phá Campuchia đầy màu sắc văn hóa và lịch sử. Phnom Penh nổi bật với Hoàng Cung, Chùa Bạc, Bảo tàng Diệt Chủng ghi dấu quá khứ đau thương nhưng giàu giá trị. Siem Reap lại cuốn hút bởi quần thể Angkor Wat kỳ vĩ, đền Bayon mặt người huyền bí và chợ đêm sôi động. Chuyến đi mang đến sự hòa trộn giữa hoài cổ, tâm linh và đời sống bản địa đặc trưng.', 70),
('18', 'Quốc tế', 'Singapore', 'QT-4003', 'Singapore', 'Singapore là đảo quốc hiện đại, sạch đẹp và năng động bậc nhất châu Á, nổi tiếng với Marina Bay Sands, Gardens by the Bay cùng những khu phố văn hóa như Chinatown, Little India, Kampong Glam. Thành phố mang đến trải nghiệm đa dạng từ vui chơi ở Sentosa, mua sắm Orchard Road, tham quan sở thú đêm đến thưởng thức ẩm thực phong phú tại hawker center. Với giao thông thuận tiện và an toàn, Singapore là điểm đến lý tưởng cho gia đình, bạn bè và khám phá công nghệ – kiến trúc.', 60),
('19', 'Quốc tế', 'Hong Kong – Macau', 'QT-4004', 'Hong Kong – Macau', 'ChatGPT đã nói:\r\n\r\nHong Kong – Macau là hành trình khám phá hai vùng lãnh thổ sôi động và giàu bản sắc. Hong Kong cuốn hút với những tòa nhà chọc trời, Victoria Harbour lung linh về đêm, mua sắm sầm uất và ẩm thực đường phố đa dạng. Trong khi đó, Macau mang hơi hướng châu Âu pha Á Đông với các di tích Bồ Đào Nha, quảng trường Senado, pháo đài cổ và những casino xa hoa. Chuyến đi đem lại trải nghiệm từ văn hóa, mua sắm đến giải trí hiện đ', 40),
('2', 'Nội địa', 'Du lịch Osaka Nhật Bản', 'Q8R2LSXA', 'Osaka', 'Osaka, Nhật Bản, là thành phố sôi động nổi tiếng với ẩm thực đường phố, lâu đài lịch sử và trung tâm mua sắm nhộn nhịp.', 30),
('3', 'Quốc tế', 'Pháp', 'HN5C0BVE', 'Tháp Eiffel', 'Tháp Eiffel là biểu tượng nổi tiếng của Paris, Pháp, với kiến trúc thép cao vút và tầm nhìn toàn thành phố.', 30),
('4', 'Nội địa', 'Tour Đà Lạt 3N2Đ – Thành phố ngàn hoa', 'Q8L2K5RA', 'Đà Lạt', 'Đà Lạt mang vẻ đẹp mộng mơ với khí hậu se lạnh quanh năm và những cảnh quan thiên nhiên thơ mộng. Du khách sẽ được chiêm ngưỡng đồi chè xanh mướt, hồ Xuân Hương yên bình và vô số điểm check-in nổi tiếng. Không khí trong lành cùng rừng thông xanh ngát tạo nên cảm giác thư giãn tuyệt đối cho du khách. Những khu vườn hoa rực rỡ sắc màu và kiến trúc cổ kính của Đà Lạt luôn để lại ấn tượng sâu sắc. Hành trình phù hợp cho gia đình, cặp đôi và nhóm bạn muốn “trốn” khỏi sự ồn ào đô thị. Đà Lạt luôn mang đến cảm giác bình yên và thơ mộng không thể tìm thấy ở nơi khác.', 25),
('5', 'Nội địa', 'Mỹ Tho - Bến Tre - Cần Thơ 2N1Đ', 'MT-9702', 'Bến Tre - Cần Thơ', 'Bến Tre còn được biết đến với các đặc sản nổi tiếng như kẹo dừa, rượu dừa, và các món ăn chế biến từ dừa. Ngoài ra, Bến Tre còn có các món ăn đậm đà hương vị miền Tây như cháo gà ta, cá lóc nướng trui, và bún riêu cua.', 70),
('6', 'Quốc tế', 'Thái Lan: Bangkok - Pattaya 5N4Đ', 'QT-1001', 'Bangkok - Pattaya', 'Bangkok – Pattaya là hành trình du lịch nổi bật ở Thái Lan, kết hợp giữa nét hiện đại sầm uất của thủ đô Bangkok với không khí biển sôi động, náo nhiệt ở Pattaya, mang đến trải nghiệm mua sắm, ẩm thực và giải trí đa dạng.', 0),
('7', 'Quốc tế', 'Singapore ', 'QT-1002', 'Singapore - Kuala Lumpur', 'Singapore – Kuala Lumpur – Genting là hành trình khám phá Đông Nam Á hiện đại, kết hợp sự sạch đẹp và sôi động của Singapore, kiến trúc đa văn hóa của Kuala Lumpur cùng không khí mát mẻ, giải trí hấp dẫn tại cao nguyên Genting.', 55),
('8', 'Quốc tế', 'Hàn Quốc: Seoul - Nami - Everland', 'QT-1003', 'Seoul - Nami - Suwon', 'Seoul – Nami – Suwon là hành trình khám phá Hàn Quốc kết hợp giữa nét hiện đại sôi động của thủ đô Seoul, vẻ đẹp thơ mộng bốn mùa trên đảo Nami và không khí cổ kính tại pháo đài Suwon, mang đến trải nghiệm văn hóa, cảnh quan và ẩm thực đa dạng.', 50),
('9', 'Quốc tế', 'Nhật Bản: Tokyo - Núi Phú Sĩ - Kyoto', 'QT-1004', 'Tokyo - Hakone - Kyoto', 'Tokyo – Hakone – Kyoto là hành trình trải nghiệm Nhật Bản từ sự hiện đại sầm uất của Tokyo, khung cảnh thiên nhiên và suối nước nóng Hakone đến nét cổ kính, đền chùa truyền thống của Kyoto, tạo nên một chuyến đi hài hòa giữa văn hóa, cảnh quan và nhịp sống.', 45);

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
('10', 'https://i.pinimg.com/736x/88/b3/43/88b343b6caaa9444dc008f716e0c76a1.jpg'),
('11', 'https://i.pinimg.com/1200x/f0/6b/41/f06b41cf4b00d85c82d7d37a03cce39d.jpg'),
('12', 'https://i.pinimg.com/736x/ff/8d/c1/ff8dc1ecb5269399e00033fbc92e934c.jpg'),
('13', 'https://i.pinimg.com/1200x/16/25/1f/16251f1e73ddc15c5e08da7c2a40a92f.jpg'),
('14', 'https://i.pinimg.com/1200x/7b/13/21/7b1321f84cdc682a104a7ce892168113.jpg'),
('15', 'https://i.pinimg.com/736x/60/3b/e7/603be7f8478db8eef7b4cac2b79b7596.jpg'),
('16', 'https://i.pinimg.com/736x/98/6d/f3/986df3c6ded9b5bee33b8308b9dc82ed.jpg'),
('17', 'https://i.pinimg.com/1200x/58/53/9f/58539f1318bbcca892589287765f32a0.jpg'),
('18', 'https://i.pinimg.com/736x/a5/a6/fd/a5a6fd46eaf0ec19e41dbe71d4832ea3.jpg'),
('19', 'https://i.pinimg.com/1200x/c7/9a/34/c79a347f457c68f40e99e1cff885ce8c.jpg'),
('2', 'https://upload.wikimedia.org/wikipedia/commons/9/9f/2018_Osaka_Castle_02.jpg'),
('3', 'https://ik.imagekit.io/tvlk/blog/2023/07/thap-eiffel-1-1024x678.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2'),
('4', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcStLiqqd2ziXVfmxPGPywfTYGpd2P9p5ZtF5g&s'),
('4', 'https://i.pinimg.com/1200x/5b/55/53/5b5553b83a8dcc8c67841c7f2f1e1984.jpg'),
('5', 'https://i.pinimg.com/736x/4d/a2/8f/4da28f462a980325db0b2031cc7ff64b.jpg'),
('6', 'https://i.pinimg.com/736x/4f/fc/36/4ffc3610fc77d1ad9e92faf74bdf0c4b.jpg'),
('7', 'https://i.pinimg.com/1200x/f8/b1/83/f8b183c67c39a78d78200348ff281069.jpg'),
('8', 'https://i.pinimg.com/736x/c1/72/82/c172826d2b822073c9d4be0be390d200.jpg'),
('9', 'https://i.pinimg.com/1200x/da/37/ec/da37ec27327a984e0cc6ee8fd77a1816.jpg');

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
('10', 'Hủy trước 90 ngày hoàn 70%', 'Hoàn tiền trong 45 ngày làm việc'),
('11', 'Hủy trước 7 ngày hoàn 90%', 'Hoàn trong 7 ngày làm việc'),
('12', 'Hủy trước 15 ngày hoàn 90%', 'Hoàn trong 10 ngày làm việc'),
('13', 'Hủy trước 10 ngày hoàn 85', 'Hoàn trong 10 ngày làm việc'),
('14', 'Không hủy', 'Không hoàn'),
('15', 'Hủy trước 10 ngày hoàn 80%', 'Hoàn trong 7 ngày làm việc'),
('16', 'Hủy trước 7 ngày hoàn 90%', 'Hoàn trong 7 ngày làm việc'),
('17', 'Hủy trước 20 ngày hoàn 90%', 'Hoàn trong 14 ngày làm việc'),
('18', 'Hủy trước 30 ngày hoàn 85%', 'Hoàn trong 14 ngày làm việc'),
('19', 'Hủy trước 30 ngày hoàn 80%', 'Hoàn trong 14 ngày làm việc'),
('2', 'Hoàn tiền 100% cho những ai mặc bệnh lý', 'Hoàn trả sớm nhất trong 7 ngày đầu '),
('3', 'Không hoàn trả', 'Không hoàn trả'),
('4', 'Huỷ trước 3 ngày được hoàn 70%', 'Hoàn tiền 100% nếu thời tiết xấu'),
('5', 'Hủy trước 7 ngày hoàn 80', 'Hoàn 100% nếu hủy trước 15 ngày'),
('6', 'Hủy trước 30 ngày hoàn 90', 'Hoàn tiền trong 14 ngày làm việc'),
('7', 'Hủy trước 45 ngày hoàn 85', 'Hoàn 100% nếu hủy trước 60 ngày'),
('8', 'Hủy trước 45 ngày hoàn 80%', 'Hoàn tiền trong 21 ngày làm việc'),
('9', 'Hủy trước 60 ngày hoàn 80%', 'Hoàn tiền trong 30 ngày làm việc');

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
('10', 79900000, 73900000),
('11', 2990000, 2290000),
('12', 6890000, 5490000),
('13', 4750000, 3650000),
('14', 4500000, 3600000),
('15', 4250000, 3450000),
('16', 3890000, 2990000),
('17', 9990000, 8490000),
('18', 14900000, 12900000),
('19', 16900000, 14400000),
('2', 7500000, 5200000),
('3', 12000000, 8700000),
('4', 5200000, 3500000),
('5', 1990000, 1490000),
('6', 11990000, 9490000),
('7', 18890000, 15890000),
('8', 23900000, 20900000),
('9', 42900000, 39900000);

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
(11, '3', 1, 'Khởi hành đến London – Tham quan Cung điện Buckingham'),
(12, '3', 2, 'Khám phá Tháp London và Cầu Tháp (Tower Bridge)'),
(13, '3', 3, 'Tham quan Bảo tàng Anh (British Museum) và Quảng trường Trafalgar'),
(14, '3', 4, 'Đi tàu đến Paris – Tham quan Tháp Eiffel'),
(15, '3', 5, 'Bảo tàng Louvre và Khải Hoàn Môn'),
(16, '3', 6, 'Tự do mua sắm và trở về'),
(20, '4', 1, 'Tham quan chợ nổi Cái Răng, ăn sáng trên sông, vườn trái cây'),
(21, '5', 1, 'Tham quan làng nghề, đi xuồng chèo, thưởng thức trái cây'),
(22, '5', 2, 'Đi tham quan trại sản xuất kẹo dừa, thăm nhà Jack'),
(23, '6', 1, 'TP.HCM/Bangkok → Trại hổ Sri Racha → Vườn thú mở Safari World (xem show cá heo) → Nhận phòng khách sạn Pattaya'),
(24, '6', 2, 'Đảo Coral (Coral Island) bằng cano → Tắm biển, lặn ngắm san hô → Chợ nổi 4 miền → Show Alcazar'),
(25, '6', 3, 'Pattaya → Bangkok → Chùa Thuyền Wat Yannawa → Chùa Phật Vàng → Trung tâm đá quý → Asiatique The Riverfront'),
(26, '6', 4, 'Bangkok → Ayutthaya (di sản UNESCO) → Cố đô Ayutthaya bằng xe tuk-tuk → Chùa Chaiwatthanaram → Chùa Mahathat (đầu Phật trong gốc cây) → Về Bangkok, tự do mua sắm Platinum hoặc Big C'),
(27, '6', 1, 'Ăn sáng → Tự do đến giờ ra sân bay → Bangkok → TP.HCM (kết thúc chương trình)'),
(28, '7', 1, 'TP.HCM → Singapore → Gardens by the Bay (2 mái vòm) → Marina Bay Sands → Công viên Merlion → Spectra Light Show'),
(29, '7', 2, 'Universal Studios toàn ngày (7 khu trò chơi, Transformers, Jurassic Park…)'),
(30, '7', 3, 'Singapore → Malacca → Nhà thờ đỏ Christ Church → Phố cổ Jonker → Quảng trường Hà Lan → Kuala Lumpur'),
(31, '7', 4, 'Kuala Lumpur → Cao nguyên Genting bằng cáp treo → Casino → Theme Park → Động Batu (273 bậc thang)'),
(32, '7', 5, 'Putrajaya (tham quan chính phủ mới) → Tháp Đôi Petronas (chụp hình bên ngoài) → Cao ốc KL Tower → Chợ đêm'),
(33, '7', 6, 'Tự do mua sắm Suria KLCC hoặc Berjaya Times Square → Kuala Lumpur → TP.HCM'),
(34, '8', 1, 'TP.HCM/Hà Nội → Seoul (Incheon) → Đảo Nami → Cây đường tình yêu, thuê xe đạp đôi → Nhận phòng khu Hongdae/Myeongdong'),
(35, '8', 2, 'Công viên Everland toàn ngày (có xe bus đưa đón) → Khu vườn 4 mùa, Safari, T-Express'),
(36, '8', 3, 'Cung Gyeongbokgung → Làng cổ Bukchon Hanok → Tháp Namsan → Phố Myeongdong (ăn uống tự do)'),
(37, '8', 4, 'Seoul → Jeju (bay nội địa) → Vách đá Seongsan Ilchulbong → Bãi biển Hyeopjae → Vườn quýt trải nghiệm'),
(38, '8', 5, 'Hang động Manjanggul → Bờ biển Woljeongri (cà phê view biển) → Bảo tàng trà O’sulloc → Jeju → Seoul'),
(39, '8', 6, 'Seoul → Tự do mua sắm Lotte Mart hoặc Dongdaemun → Incheon → TP.HCM/Hà Nội'),
(40, '9', 1, 'TP.HCM/Hà Nội → Tokyo (Narita/Haneda) → Tháp Tokyo Skytree → Chùa Asakusa → Phố Nakamise'),
(41, '9', 2, 'Núi Phú Sĩ trạm 5 → Làng cổ Oshino Hakkai → Hồ Kawaguchi → Trải nghiệm hái trái cây theo mùa'),
(42, '9', 3, 'Tokyo → Nagoya bằng Shinkansen → Lâu đài Nagoya → Phố mua sắm Osu Kannon'),
(43, '9', 4, 'Nagoya → Kyoto → Chùa Vàng Kinkakuji → Rừng tre Arashiyama → Phố cổ Gion (có thể gặp Geisha)'),
(44, '9', 5, 'Kyoto → Nara (công viên hươu tự do) → Chùa Todai-ji (Phật lớn) → Osaka → Lâu đài Osaka'),
(45, '9', 6, 'Osaka → Universal Studios Japan toàn ngày (Super Nintendo World, Harry Potter…)'),
(46, '9', 7, 'Tự do mua sắm Shinsaibashi hoặc chợ Kuromon → Kansai → TP.HCM/Hà Nội'),
(47, '10', 1, 'TP.HCM → Paris (bay đêm)'),
(48, '10', 2, 'Đến Paris → Tháp Eiffel → Du thuyền sông Seine → Nhà thờ Đức Bà → Quảng trường Concorde'),
(49, '10', 3, 'Cung điện Versailles (vườn + cung điện) → Bảo tàng Louvre (Mona Lisa, Venus) → Khải hoàn môn'),
(50, '10', 4, 'Paris → Lucerne (Thụy Sĩ) → Cầu Chapel → Hồ Lucerne → Tượng sư tử đá'),
(51, '10', 5, 'Lucerne → Núi Titlis (cáp treo + cầu treo cao nhất châu Âu) → Engelberg'),
(52, '10', 6, 'Thụy Sĩ → Milan (Ý) → Nhà thờ Duomo → Quảng trường Galleria Vittorio Emanuele II'),
(53, '10', 7, 'Milan → Venice → Quảng trường San Marco → Thuyền Gondola → Cầu Than Thở → Nhà máy thủy tinh Murano'),
(54, '10', 8, 'Venice → Rome → Đấu trường Colosseum → Đài phun nước Trevi → Quảng trường Tây Ban Nha → Vatican (Nhà thờ Thánh Peter)'),
(55, '10', 9, 'Rome → Tự do mua sắm → Sân bay Rome → TP.HCM (bay đêm, về đến nơi ngày 10)'),
(56, '11', 1, 'TP.HCM → Đà Lạt → Thác Datanla (máng trượt) → Đồi chè Cầu Đất → Nhận phòng'),
(57, '11', 2, 'Săn mây Hoàng Su Phì hoặc đồi Robin → Quảng trường Lâm Viên → Chợ đêm'),
(58, '11', 3, 'Ga Đà Lạt → Langbiang → TP.HCM'),
(59, '12', 1, 'Bay TP.HCM → Phú Quốc → Cáp treo + công viên nước Hòn Thơm'),
(60, '12', 2, 'Safari + VinWonders → Tắm biển Bãi Sao → Sunset Sanato'),
(61, '12', 3, 'Grand World → Bay về'),
(62, '13', 1, 'Hà Nội → Bái Đính → Tràng An → Hang Múa → Hạ Long'),
(63, '13', 2, 'Du thuyền 5 sao ngủ đêm vịnh → Kayak → Hang Sửng Sốt → Ti Tốp'),
(64, '13', 3, 'Ngắm bình minh → Hang Động → Hà Nội'),
(65, '14', 1, 'Nhập cảnh - khám phá Viêng Chăn'),
(66, '14', 2, 'Tham quan  văn hóa - mua sắm - trải nghiệm địa phương'),
(67, '15', 1, 'Bay → Huế → Đại Nội → Chùa Thiên Mụ → Lăng Khải Định'),
(68, '15', 2, 'Huế → Quảng Bình → Động Phong Nha + Thiên Đường'),
(69, '15', 3, 'La Vang → Sân bay về'),
(70, '16', 1, 'Bay → Quy Nhơn → Eo Gió → Ghềnh Ráng'),
(71, '16', 2, 'Cano Kỳ Co + tắm biển + hải sản'),
(72, '16', 3, 'Tháp Chăm → Mua sắm → Bay về'),
(73, '17', 1, 'TP.HCM → Phnom Penh → Cung điện + Chùa Bạc'),
(74, '17', 2, 'Phnom Penh → Siem Reap → Angkor Thom + Ta Prohm'),
(75, '17', 3, 'Angkor Wat ngắm bình minh → Banteay Srei → Làng nổi Tonle Sap'),
(76, '17', 4, 'Chợ cũ → Bay về'),
(77, '18', 1, 'Bay → Singapore → Gardens by the Bay + Marina Bay Sands'),
(78, '18', 2, 'Universal Studios toàn ngày'),
(79, '18', 3, 'Sentosa (S.E.A Aquarium + cánh sóng) → Jewel Changi'),
(80, '18', 4, 'Merlion + mua sắm → Bay về'),
(81, '19', 1, 'Bay → Hong Kong → Avenue of Stars + Symphony of Lights'),
(82, '19', 2, 'Disneyland toàn ngày'),
(83, '19', 3, 'Macau (phà cao tốc) → The Venetian → Ruins of St.Paul'),
(84, '19', 4, 'Ngong Ping 360 → Bay về'),
(89, '2', 1, 'Khởi hành đến Osaka – Nhận phòng khách sạn'),
(90, '2', 2, 'Tham quan Lâu đài Osaka (Osaka-jo) và Khu Dotonbori'),
(91, '2', 3, 'Tham quan Universal Studios Japan (USJ)'),
(92, '2', 4, 'Mua sắm tại Shinsaibashi-suji và khám phá Tháp Tsutenkaku'),
(93, '2', 5, 'Tự do và trở về Việt Nam');

-- --------------------------------------------------------

--
-- Table structure for table `tour_schedule_details`
--

CREATE TABLE `tour_schedule_details` (
  `id` int NOT NULL,
  `schedule_id` int NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `content` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_schedule_details`
--

INSERT INTO `tour_schedule_details` (`id`, `schedule_id`, `start_time`, `end_time`, `content`) VALUES
(1, 47, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(2, 48, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(3, 49, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(4, 50, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(5, 51, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(6, 52, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(7, 53, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(8, 54, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(9, 55, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(10, 56, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(11, 57, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(12, 58, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(13, 59, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(14, 60, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(15, 61, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(16, 62, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(17, 63, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(18, 64, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(19, 65, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(20, 66, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(21, 67, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(22, 68, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(23, 69, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(24, 70, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(25, 71, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(26, 72, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(27, 73, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(28, 74, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(29, 75, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(30, 76, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(31, 77, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(32, 78, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(33, 79, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(34, 80, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(35, 81, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(36, 82, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(37, 83, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(38, 84, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(39, 89, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(40, 90, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(41, 91, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(42, 92, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(43, 93, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(44, 11, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(45, 12, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(46, 13, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(47, 14, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(48, 15, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(49, 16, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(50, 20, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(51, 21, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(52, 22, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(53, 23, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(54, 24, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(55, 25, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(56, 26, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(57, 27, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(58, 28, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(59, 29, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(60, 30, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(61, 31, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(62, 32, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(63, 33, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(64, 34, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(65, 35, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(66, 36, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(67, 37, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(68, 38, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(69, 39, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(70, 40, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(71, 41, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(72, 42, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(73, 43, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(74, 44, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(75, 45, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(76, 46, '06:30:00', '07:30:00', 'Thức dậy – chuẩn bị cá nhân'),
(77, 47, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(78, 48, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(79, 49, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(80, 50, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(81, 51, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(82, 52, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(83, 53, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(84, 54, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(85, 55, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(86, 56, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(87, 57, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(88, 58, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(89, 59, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(90, 60, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(91, 61, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(92, 62, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(93, 63, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(94, 64, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(95, 65, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(96, 66, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(97, 67, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(98, 68, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(99, 69, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(100, 70, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(101, 71, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(102, 72, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(103, 73, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(104, 74, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(105, 75, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(106, 76, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(107, 77, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(108, 78, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(109, 79, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(110, 80, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(111, 81, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(112, 82, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(113, 83, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(114, 84, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(115, 89, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(116, 90, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(117, 91, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(118, 92, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(119, 93, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(120, 11, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(121, 12, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(122, 13, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(123, 14, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(124, 15, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(125, 16, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(126, 20, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(127, 21, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(128, 22, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(129, 23, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(130, 24, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(131, 25, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(132, 26, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(133, 27, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(134, 28, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(135, 29, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(136, 30, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(137, 31, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(138, 32, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(139, 33, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(140, 34, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(141, 35, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(142, 36, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(143, 37, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(144, 38, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(145, 39, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(146, 40, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(147, 41, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(148, 42, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(149, 43, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(150, 44, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(151, 45, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(152, 46, '07:30:00', '08:30:00', 'Ăn sáng tại khách sạn'),
(153, 11, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Khởi hành đến London – Tham quan Cung điện Buckingham'),
(154, 12, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Khám phá Tháp London và Cầu Tháp (Tower Bridge)'),
(155, 13, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tham quan Bảo tàng Anh (British Museum) và Quảng trường Trafalgar'),
(156, 14, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Đi tàu đến Paris – Tham quan Tháp Eiffel'),
(157, 15, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Bảo tàng Louvre và Khải Hoàn Môn'),
(158, 16, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tự do mua sắm và trở về'),
(159, 20, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tham quan chợ nổi Cái Răng, ăn sáng trên sông, vườn trái cây'),
(160, 21, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tham quan làng nghề, đi xuồng chèo, thưởng thức trái cây'),
(161, 22, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Đi tham quan trại sản xuất kẹo dừa, thăm nhà Jack'),
(162, 23, '08:30:00', '11:30:00', 'Tham quan buổi sáng: TP.HCM/Bangkok → Trại hổ Sri Racha → Vườn thú mở Safari World (xem show cá heo) → Nhận phòng khách sạn Pattaya'),
(163, 24, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Đảo Coral (Coral Island) bằng cano → Tắm biển, lặn ngắm san hô → Chợ nổi 4 miền → Show Alcazar'),
(164, 25, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Pattaya → Bangkok → Chùa Thuyền Wat Yannawa → Chùa Phật Vàng → Trung tâm đá quý → Asiatique The Riverfront'),
(165, 26, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Bangkok → Ayutthaya (di sản UNESCO) → Cố đô Ayutthaya bằng xe tuk-tuk → Chùa Chaiwatthanaram → Chùa Mahathat (đầu Phật trong gốc cây) → Về Bangkok, tự do mua sắm Platinum hoặc Big C'),
(166, 27, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Ăn sáng → Tự do đến giờ ra sân bay → Bangkok → TP.HCM (kết thúc chương trình)'),
(167, 28, '08:30:00', '11:30:00', 'Tham quan buổi sáng: TP.HCM → Singapore → Gardens by the Bay (2 mái vòm) → Marina Bay Sands → Công viên Merlion → Spectra Light Show'),
(168, 29, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Universal Studios toàn ngày (7 khu trò chơi, Transformers, Jurassic Park…)'),
(169, 30, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Singapore → Malacca → Nhà thờ đỏ Christ Church → Phố cổ Jonker → Quảng trường Hà Lan → Kuala Lumpur'),
(170, 31, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Kuala Lumpur → Cao nguyên Genting bằng cáp treo → Casino → Theme Park → Động Batu (273 bậc thang)'),
(171, 32, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Putrajaya (tham quan chính phủ mới) → Tháp Đôi Petronas (chụp hình bên ngoài) → Cao ốc KL Tower → Chợ đêm'),
(172, 33, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tự do mua sắm Suria KLCC hoặc Berjaya Times Square → Kuala Lumpur → TP.HCM'),
(173, 34, '08:30:00', '11:30:00', 'Tham quan buổi sáng: TP.HCM/Hà Nội → Seoul (Incheon) → Đảo Nami → Cây đường tình yêu, thuê xe đạp đôi → Nhận phòng khu Hongdae/Myeongdong'),
(174, 35, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Công viên Everland toàn ngày (có xe bus đưa đón) → Khu vườn 4 mùa, Safari, T-Express'),
(175, 36, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Cung Gyeongbokgung → Làng cổ Bukchon Hanok → Tháp Namsan → Phố Myeongdong (ăn uống tự do)'),
(176, 37, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Seoul → Jeju (bay nội địa) → Vách đá Seongsan Ilchulbong → Bãi biển Hyeopjae → Vườn quýt trải nghiệm'),
(177, 38, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Hang động Manjanggul → Bờ biển Woljeongri (cà phê view biển) → Bảo tàng trà O’sulloc → Jeju → Seoul'),
(178, 39, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Seoul → Tự do mua sắm Lotte Mart hoặc Dongdaemun → Incheon → TP.HCM/Hà Nội'),
(179, 40, '08:30:00', '11:30:00', 'Tham quan buổi sáng: TP.HCM/Hà Nội → Tokyo (Narita/Haneda) → Tháp Tokyo Skytree → Chùa Asakusa → Phố Nakamise'),
(180, 41, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Núi Phú Sĩ trạm 5 → Làng cổ Oshino Hakkai → Hồ Kawaguchi → Trải nghiệm hái trái cây theo mùa'),
(181, 42, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tokyo → Nagoya bằng Shinkansen → Lâu đài Nagoya → Phố mua sắm Osu Kannon'),
(182, 43, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Nagoya → Kyoto → Chùa Vàng Kinkakuji → Rừng tre Arashiyama → Phố cổ Gion (có thể gặp Geisha)'),
(183, 44, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Kyoto → Nara (công viên hươu tự do) → Chùa Todai-ji (Phật lớn) → Osaka → Lâu đài Osaka'),
(184, 45, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Osaka → Universal Studios Japan toàn ngày (Super Nintendo World, Harry Potter…)'),
(185, 46, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tự do mua sắm Shinsaibashi hoặc chợ Kuromon → Kansai → TP.HCM/Hà Nội'),
(186, 47, '08:30:00', '11:30:00', 'Tham quan buổi sáng: TP.HCM → Paris (bay đêm)'),
(187, 48, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Đến Paris → Tháp Eiffel → Du thuyền sông Seine → Nhà thờ Đức Bà → Quảng trường Concorde'),
(188, 49, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Cung điện Versailles (vườn + cung điện) → Bảo tàng Louvre (Mona Lisa, Venus) → Khải hoàn môn'),
(189, 50, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Paris → Lucerne (Thụy Sĩ) → Cầu Chapel → Hồ Lucerne → Tượng sư tử đá'),
(190, 51, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Lucerne → Núi Titlis (cáp treo + cầu treo cao nhất châu Âu) → Engelberg'),
(191, 52, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Thụy Sĩ → Milan (Ý) → Nhà thờ Duomo → Quảng trường Galleria Vittorio Emanuele II'),
(192, 53, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Milan → Venice → Quảng trường San Marco → Thuyền Gondola → Cầu Than Thở → Nhà máy thủy tinh Murano'),
(193, 54, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Venice → Rome → Đấu trường Colosseum → Đài phun nước Trevi → Quảng trường Tây Ban Nha → Vatican (Nhà thờ Thánh Peter)'),
(194, 55, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Rome → Tự do mua sắm → Sân bay Rome → TP.HCM (bay đêm, về đến nơi ngày 10)'),
(195, 56, '08:30:00', '11:30:00', 'Tham quan buổi sáng: TP.HCM → Đà Lạt → Thác Datanla (máng trượt) → Đồi chè Cầu Đất → Nhận phòng'),
(196, 57, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Săn mây Hoàng Su Phì hoặc đồi Robin → Quảng trường Lâm Viên → Chợ đêm'),
(197, 58, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Ga Đà Lạt → Langbiang → TP.HCM'),
(198, 59, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Bay TP.HCM → Phú Quốc → Cáp treo + công viên nước Hòn Thơm'),
(199, 60, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Safari + VinWonders → Tắm biển Bãi Sao → Sunset Sanato'),
(200, 61, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Grand World → Bay về'),
(201, 62, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Hà Nội → Bái Đính → Tràng An → Hang Múa → Hạ Long'),
(202, 63, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Du thuyền 5 sao ngủ đêm vịnh → Kayak → Hang Sửng Sốt → Ti Tốp'),
(203, 64, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Ngắm bình minh → Hang Động → Hà Nội'),
(204, 65, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Nhập cảnh - khám phá Viêng Chăn'),
(205, 66, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tham quan  văn hóa - mua sắm - trải nghiệm địa phương'),
(206, 67, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Bay → Huế → Đại Nội → Chùa Thiên Mụ → Lăng Khải Định'),
(207, 68, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Huế → Quảng Bình → Động Phong Nha + Thiên Đường'),
(208, 69, '08:30:00', '11:30:00', 'Tham quan buổi sáng: La Vang → Sân bay về'),
(209, 70, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Bay → Quy Nhơn → Eo Gió → Ghềnh Ráng'),
(210, 71, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Cano Kỳ Co + tắm biển + hải sản'),
(211, 72, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tháp Chăm → Mua sắm → Bay về'),
(212, 73, '08:30:00', '11:30:00', 'Tham quan buổi sáng: TP.HCM → Phnom Penh → Cung điện + Chùa Bạc'),
(213, 74, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Phnom Penh → Siem Reap → Angkor Thom + Ta Prohm'),
(214, 75, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Angkor Wat ngắm bình minh → Banteay Srei → Làng nổi Tonle Sap'),
(215, 76, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Chợ cũ → Bay về'),
(216, 77, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Bay → Singapore → Gardens by the Bay + Marina Bay Sands'),
(217, 78, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Universal Studios toàn ngày'),
(218, 79, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Sentosa (S.E.A Aquarium + cánh sóng) → Jewel Changi'),
(219, 80, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Merlion + mua sắm → Bay về'),
(220, 81, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Bay → Hong Kong → Avenue of Stars + Symphony of Lights'),
(221, 82, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Disneyland toàn ngày'),
(222, 83, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Macau (phà cao tốc) → The Venetian → Ruins of St.Paul'),
(223, 84, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Ngong Ping 360 → Bay về'),
(224, 89, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Khởi hành đến Osaka – Nhận phòng khách sạn'),
(225, 90, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tham quan Lâu đài Osaka (Osaka-jo) và Khu Dotonbori'),
(226, 91, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tham quan Universal Studios Japan (USJ)'),
(227, 92, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Mua sắm tại Shinsaibashi-suji và khám phá Tháp Tsutenkaku'),
(228, 93, '08:30:00', '11:30:00', 'Tham quan buổi sáng: Tự do và trở về Việt Nam'),
(229, 47, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(230, 48, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(231, 49, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(232, 50, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(233, 51, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(234, 52, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(235, 53, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(236, 54, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(237, 55, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(238, 56, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(239, 57, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(240, 58, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(241, 59, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(242, 60, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(243, 61, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(244, 62, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(245, 63, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(246, 64, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(247, 65, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(248, 66, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(249, 67, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(250, 68, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(251, 69, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(252, 70, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(253, 71, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(254, 72, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(255, 73, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(256, 74, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(257, 75, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(258, 76, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(259, 77, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(260, 78, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(261, 79, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(262, 80, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(263, 81, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(264, 82, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(265, 83, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(266, 84, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(267, 89, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(268, 90, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(269, 91, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(270, 92, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(271, 93, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(272, 11, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(273, 12, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(274, 13, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(275, 14, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(276, 15, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(277, 16, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(278, 20, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(279, 21, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(280, 22, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(281, 23, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(282, 24, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(283, 25, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(284, 26, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(285, 27, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(286, 28, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(287, 29, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(288, 30, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(289, 31, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(290, 32, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(291, 33, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(292, 34, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(293, 35, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(294, 36, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(295, 37, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(296, 38, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(297, 39, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(298, 40, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(299, 41, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(300, 42, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(301, 43, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(302, 44, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(303, 45, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(304, 46, '11:30:00', '13:00:00', 'Ăn trưa – nghỉ ngơi ngắn'),
(305, 11, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Khởi hành đến London – Tham quan Cung điện Buckingham'),
(306, 12, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Khám phá Tháp London và Cầu Tháp (Tower Bridge)'),
(307, 13, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tham quan Bảo tàng Anh (British Museum) và Quảng trường Trafalgar'),
(308, 14, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Đi tàu đến Paris – Tham quan Tháp Eiffel'),
(309, 15, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Bảo tàng Louvre và Khải Hoàn Môn'),
(310, 16, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tự do mua sắm và trở về'),
(311, 20, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tham quan chợ nổi Cái Răng, ăn sáng trên sông, vườn trái cây'),
(312, 21, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tham quan làng nghề, đi xuồng chèo, thưởng thức trái cây'),
(313, 22, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Đi tham quan trại sản xuất kẹo dừa, thăm nhà Jack'),
(314, 23, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: TP.HCM/Bangkok → Trại hổ Sri Racha → Vườn thú mở Safari World (xem show cá heo) → Nhận phòng khách sạn Pattaya'),
(315, 24, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Đảo Coral (Coral Island) bằng cano → Tắm biển, lặn ngắm san hô → Chợ nổi 4 miền → Show Alcazar'),
(316, 25, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Pattaya → Bangkok → Chùa Thuyền Wat Yannawa → Chùa Phật Vàng → Trung tâm đá quý → Asiatique The Riverfront'),
(317, 26, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Bangkok → Ayutthaya (di sản UNESCO) → Cố đô Ayutthaya bằng xe tuk-tuk → Chùa Chaiwatthanaram → Chùa Mahathat (đầu Phật trong gốc cây) → Về Bangkok, tự do mua sắm Platinum hoặc Big C'),
(318, 27, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Ăn sáng → Tự do đến giờ ra sân bay → Bangkok → TP.HCM (kết thúc chương trình)'),
(319, 28, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: TP.HCM → Singapore → Gardens by the Bay (2 mái vòm) → Marina Bay Sands → Công viên Merlion → Spectra Light Show'),
(320, 29, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Universal Studios toàn ngày (7 khu trò chơi, Transformers, Jurassic Park…)'),
(321, 30, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Singapore → Malacca → Nhà thờ đỏ Christ Church → Phố cổ Jonker → Quảng trường Hà Lan → Kuala Lumpur'),
(322, 31, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Kuala Lumpur → Cao nguyên Genting bằng cáp treo → Casino → Theme Park → Động Batu (273 bậc thang)'),
(323, 32, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Putrajaya (tham quan chính phủ mới) → Tháp Đôi Petronas (chụp hình bên ngoài) → Cao ốc KL Tower → Chợ đêm'),
(324, 33, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tự do mua sắm Suria KLCC hoặc Berjaya Times Square → Kuala Lumpur → TP.HCM'),
(325, 34, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: TP.HCM/Hà Nội → Seoul (Incheon) → Đảo Nami → Cây đường tình yêu, thuê xe đạp đôi → Nhận phòng khu Hongdae/Myeongdong'),
(326, 35, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Công viên Everland toàn ngày (có xe bus đưa đón) → Khu vườn 4 mùa, Safari, T-Express'),
(327, 36, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Cung Gyeongbokgung → Làng cổ Bukchon Hanok → Tháp Namsan → Phố Myeongdong (ăn uống tự do)'),
(328, 37, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Seoul → Jeju (bay nội địa) → Vách đá Seongsan Ilchulbong → Bãi biển Hyeopjae → Vườn quýt trải nghiệm'),
(329, 38, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Hang động Manjanggul → Bờ biển Woljeongri (cà phê view biển) → Bảo tàng trà O’sulloc → Jeju → Seoul'),
(330, 39, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Seoul → Tự do mua sắm Lotte Mart hoặc Dongdaemun → Incheon → TP.HCM/Hà Nội'),
(331, 40, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: TP.HCM/Hà Nội → Tokyo (Narita/Haneda) → Tháp Tokyo Skytree → Chùa Asakusa → Phố Nakamise'),
(332, 41, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Núi Phú Sĩ trạm 5 → Làng cổ Oshino Hakkai → Hồ Kawaguchi → Trải nghiệm hái trái cây theo mùa'),
(333, 42, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tokyo → Nagoya bằng Shinkansen → Lâu đài Nagoya → Phố mua sắm Osu Kannon'),
(334, 43, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Nagoya → Kyoto → Chùa Vàng Kinkakuji → Rừng tre Arashiyama → Phố cổ Gion (có thể gặp Geisha)'),
(335, 44, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Kyoto → Nara (công viên hươu tự do) → Chùa Todai-ji (Phật lớn) → Osaka → Lâu đài Osaka'),
(336, 45, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Osaka → Universal Studios Japan toàn ngày (Super Nintendo World, Harry Potter…)'),
(337, 46, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tự do mua sắm Shinsaibashi hoặc chợ Kuromon → Kansai → TP.HCM/Hà Nội'),
(338, 47, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: TP.HCM → Paris (bay đêm)'),
(339, 48, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Đến Paris → Tháp Eiffel → Du thuyền sông Seine → Nhà thờ Đức Bà → Quảng trường Concorde'),
(340, 49, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Cung điện Versailles (vườn + cung điện) → Bảo tàng Louvre (Mona Lisa, Venus) → Khải hoàn môn'),
(341, 50, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Paris → Lucerne (Thụy Sĩ) → Cầu Chapel → Hồ Lucerne → Tượng sư tử đá'),
(342, 51, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Lucerne → Núi Titlis (cáp treo + cầu treo cao nhất châu Âu) → Engelberg'),
(343, 52, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Thụy Sĩ → Milan (Ý) → Nhà thờ Duomo → Quảng trường Galleria Vittorio Emanuele II'),
(344, 53, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Milan → Venice → Quảng trường San Marco → Thuyền Gondola → Cầu Than Thở → Nhà máy thủy tinh Murano'),
(345, 54, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Venice → Rome → Đấu trường Colosseum → Đài phun nước Trevi → Quảng trường Tây Ban Nha → Vatican (Nhà thờ Thánh Peter)'),
(346, 55, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Rome → Tự do mua sắm → Sân bay Rome → TP.HCM (bay đêm, về đến nơi ngày 10)'),
(347, 56, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: TP.HCM → Đà Lạt → Thác Datanla (máng trượt) → Đồi chè Cầu Đất → Nhận phòng'),
(348, 57, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Săn mây Hoàng Su Phì hoặc đồi Robin → Quảng trường Lâm Viên → Chợ đêm'),
(349, 58, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Ga Đà Lạt → Langbiang → TP.HCM'),
(350, 59, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Bay TP.HCM → Phú Quốc → Cáp treo + công viên nước Hòn Thơm'),
(351, 60, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Safari + VinWonders → Tắm biển Bãi Sao → Sunset Sanato'),
(352, 61, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Grand World → Bay về'),
(353, 62, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Hà Nội → Bái Đính → Tràng An → Hang Múa → Hạ Long'),
(354, 63, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Du thuyền 5 sao ngủ đêm vịnh → Kayak → Hang Sửng Sốt → Ti Tốp'),
(355, 64, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Ngắm bình minh → Hang Động → Hà Nội'),
(356, 65, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Nhập cảnh - khám phá Viêng Chăn'),
(357, 66, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tham quan  văn hóa - mua sắm - trải nghiệm địa phương'),
(358, 67, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Bay → Huế → Đại Nội → Chùa Thiên Mụ → Lăng Khải Định'),
(359, 68, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Huế → Quảng Bình → Động Phong Nha + Thiên Đường'),
(360, 69, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: La Vang → Sân bay về'),
(361, 70, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Bay → Quy Nhơn → Eo Gió → Ghềnh Ráng'),
(362, 71, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Cano Kỳ Co + tắm biển + hải sản'),
(363, 72, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tháp Chăm → Mua sắm → Bay về'),
(364, 73, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: TP.HCM → Phnom Penh → Cung điện + Chùa Bạc'),
(365, 74, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Phnom Penh → Siem Reap → Angkor Thom + Ta Prohm'),
(366, 75, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Angkor Wat ngắm bình minh → Banteay Srei → Làng nổi Tonle Sap'),
(367, 76, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Chợ cũ → Bay về'),
(368, 77, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Bay → Singapore → Gardens by the Bay + Marina Bay Sands'),
(369, 78, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Universal Studios toàn ngày'),
(370, 79, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Sentosa (S.E.A Aquarium + cánh sóng) → Jewel Changi'),
(371, 80, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Merlion + mua sắm → Bay về'),
(372, 81, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Bay → Hong Kong → Avenue of Stars + Symphony of Lights'),
(373, 82, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Disneyland toàn ngày'),
(374, 83, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Macau (phà cao tốc) → The Venetian → Ruins of St.Paul'),
(375, 84, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Ngong Ping 360 → Bay về'),
(376, 89, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Khởi hành đến Osaka – Nhận phòng khách sạn'),
(377, 90, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tham quan Lâu đài Osaka (Osaka-jo) và Khu Dotonbori'),
(378, 91, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tham quan Universal Studios Japan (USJ)'),
(379, 92, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Mua sắm tại Shinsaibashi-suji và khám phá Tháp Tsutenkaku'),
(380, 93, '13:30:00', '17:00:00', 'Tiếp tục chương trình chiều: Tự do và trở về Việt Nam'),
(381, 47, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(382, 48, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(383, 49, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(384, 50, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(385, 51, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(386, 52, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(387, 53, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(388, 54, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(389, 55, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(390, 56, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(391, 57, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(392, 58, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(393, 59, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(394, 60, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(395, 61, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(396, 62, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(397, 63, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(398, 64, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(399, 65, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(400, 66, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(401, 67, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(402, 68, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(403, 69, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(404, 70, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(405, 71, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(406, 72, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(407, 73, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(408, 74, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(409, 75, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(410, 76, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(411, 77, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(412, 78, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(413, 79, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(414, 80, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(415, 81, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(416, 82, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(417, 83, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(418, 84, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(419, 89, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(420, 90, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(421, 91, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(422, 92, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(423, 93, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(424, 11, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(425, 12, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(426, 13, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(427, 14, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(428, 15, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(429, 16, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(430, 20, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(431, 21, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(432, 22, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(433, 23, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(434, 24, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(435, 25, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(436, 26, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(437, 27, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(438, 28, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(439, 29, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(440, 30, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(441, 31, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(442, 32, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(443, 33, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(444, 34, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(445, 35, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(446, 36, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(447, 37, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(448, 38, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(449, 39, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(450, 40, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(451, 41, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(452, 42, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(453, 43, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(454, 44, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(455, 45, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(456, 46, '18:00:00', '19:30:00', 'Ăn tối – thưởng thức ẩm thực địa phương'),
(457, 47, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(458, 48, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(459, 49, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(460, 50, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(461, 51, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(462, 52, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(463, 53, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(464, 54, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(465, 55, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(466, 56, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(467, 57, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(468, 58, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(469, 59, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(470, 60, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(471, 61, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(472, 62, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(473, 63, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(474, 64, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(475, 65, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(476, 66, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(477, 67, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(478, 68, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(479, 69, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(480, 70, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(481, 71, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(482, 72, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(483, 73, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(484, 74, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(485, 75, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(486, 76, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(487, 77, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(488, 78, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(489, 79, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(490, 80, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(491, 81, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(492, 82, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(493, 83, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(494, 84, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(495, 89, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(496, 90, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(497, 91, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(498, 92, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(499, 93, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(500, 11, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(501, 12, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(502, 13, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(503, 14, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(504, 15, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(505, 16, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(506, 20, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(507, 21, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(508, 22, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(509, 23, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(510, 24, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(511, 25, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(512, 26, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(513, 27, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(514, 28, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(515, 29, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(516, 30, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(517, 31, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(518, 32, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(519, 33, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(520, 34, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(521, 35, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(522, 36, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(523, 37, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(524, 38, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(525, 39, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(526, 40, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(527, 41, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(528, 42, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(529, 43, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(530, 44, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(531, 45, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn'),
(532, 46, '20:00:00', NULL, 'Tự do khám phá, nghỉ đêm tại khách sạn');

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
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'user',
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `phone`, `genre`, `role`, `avatar`) VALUES
('1', 'admin@gmail.com', '123456', 'Admin', '0912345678', 'Ẩn danh', 'admin', 'https://staticg.sportskeeda.com/editor/2025/10/3e2c3-17608635444244-1920.jpg?w=640'),
('2', 'tavantruog1702@gmail.com', '123456', 'Tạ Văn Trường', '0362422418', 'Nam', 'user', 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg'),
('3', 'ddao51531@gmail.com', '123456', 'Đào Đức Anh', '0969778516', 'Nam', 'user', 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg'),
('4', 'Phuongnamdanhnguyen@gmail.com', '123456', 'Nguyễn Danh Phương Nam', '0675458232', 'Nam', 'user', 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg'),
('5', 'abc@gmail.com', '123456', 'Nguyễn Danh Phương ', '0874567887654', 'Nam', 'user', 'https://ui-avatars.com/api/?name=User&background=random');

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
-- Indexes for table `booking_registrants`
--
ALTER TABLE `booking_registrants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_checkins_customer` (`customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registrants_id` (`registrants_id`);

--
-- Indexes for table `departures`
--
ALTER TABLE `departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tourId` (`tourId`),
  ADD KEY `guideId` (`guideId`),
  ADD KEY `departures_ibfk_3` (`driverId`);

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
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_registrant` (`registrant_id`);

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
-- Indexes for table `tour_schedule_details`
--
ALTER TABLE `tour_schedule_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_schedule` (`schedule_id`);

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
-- AUTO_INCREMENT for table `booking_registrants`
--
ALTER TABLE `booking_registrants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tour_schedules`
--
ALTER TABLE `tour_schedules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `tour_schedule_details`
--
ALTER TABLE `tour_schedule_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1024;

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

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `fk_checkins_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fk_customers_registrants` FOREIGN KEY (`registrants_id`) REFERENCES `booking_registrants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `departures`
--
ALTER TABLE `departures`
  ADD CONSTRAINT `departures_ibfk_1` FOREIGN KEY (`tourId`) REFERENCES `tours` (`id`),
  ADD CONSTRAINT `departures_ibfk_2` FOREIGN KEY (`guideId`) REFERENCES `staffs` (`id`),
  ADD CONSTRAINT `departures_ibfk_3` FOREIGN KEY (`driverId`) REFERENCES `drivers` (`id`);

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
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `fk_payment_history_registrant` FOREIGN KEY (`registrant_id`) REFERENCES `booking_registrants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `tour_schedule_details`
--
ALTER TABLE `tour_schedule_details`
  ADD CONSTRAINT `fk_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `tour_schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_suppliers`
--
ALTER TABLE `tour_suppliers`
  ADD CONSTRAINT `tour_suppliers_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
