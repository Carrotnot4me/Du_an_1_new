-- Create suppliers table expected by SupplierModel
CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text,
  `logo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Sample data
INSERT INTO `suppliers` (`id`, `name`, `service_type`, `address`, `email`, `phone`, `website`, `description`, `logo`) VALUES
('1', 'Khách sạn Grand', 'khach_san', 'Số 1 Đường A, Quận B', 'grand@example.com', '0909000001', 'https://grand.example.com', 'Khách sạn 4 sao', NULL),
('2', 'Nhà hàng Biển Xanh', 'nha_hang', 'Số 2 Đường C, Quận D', 'bienxanh@example.com', '0909000002', NULL, 'Nhà hàng hải sản', NULL),
('3', 'Dịch vụ Xe 45 chỗ', 'xe', 'Bến xe Trung tâm', 'xe45@example.com', '0909000003', NULL, 'Cho thuê xe du lịch', NULL);
