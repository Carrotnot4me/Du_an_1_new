-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2025 at 10:38 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tour_code` (`tour_code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2025 at 10:39 AM
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
('1', '11', 'Sắp đi', '9', 'WBeUkHX', '1'),
('2', '13', 'Sắp đi', '9', 'WBeUkHX', '2'),
('3', '19', 'Sắp đi', '9', '1', '3');

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
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2025 at 10:40 AM
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

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD PRIMARY KEY (`tour_id`,`image_url`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD CONSTRAINT `tour_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2025 at 10:48 AM
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
(1, '1', 'Tạ Văn Trường', 'tavantruog1702@gmail.com', '0362422418', 'Gia đình', '3', 'Chờ xác nhận'),
(2, '1', 'Tạ Vũ Trí', 'tvt2008@gmail.com', '0387261184', 'Cá nhân', '1', 'Chờ xác nhận'),
(3, '2', 'Tạ Văn Trường', 'tavantruog1702@gmail.com', '0362422418', 'Bạn bè', '3', 'Chờ xác nhận'),
(4, '2', 'Tạ Vũ Trí', 'tvt2008@gmail.com', '0387261184', 'Gia đình', '1', 'Chờ xác nhận'),
(5, '3', 'Tạ Văn Trường', 'tavantruog1702@gmail.com', '0362422418', 'Cá nhân', '1', 'Chờ xác nhận'),
(6, '3', 'Tạ Vũ Trí', 'tvt2008@gmail.com', '0387261184', 'Gia đình', '1', 'Chờ xác nhận');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_registrants`
--
ALTER TABLE `booking_registrants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_registrants`
--
ALTER TABLE `booking_registrants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 13, 2025 at 10:55 AM
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
('1', '11', '2025-12-12', '2026-01-10', 'Sân Bay Tân Sơn Nhất', 'WBeUkHX', '9'),
('2', '13', '2026-01-15', '2026-02-20', 'Sân Bay Tân Sơn Nhất', 'WBeUkHX', '9'),
('3', '19', '2026-02-11', '2026-03-05', 'Sân Bay Tân Sơn Nhất', '1', '9');

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
