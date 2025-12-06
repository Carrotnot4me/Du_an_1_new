<?php
require_once './commons/function.php';

class DashboardModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getStats() {
        $data = [];

        try {
            // 1. Thống kê chung (Không đổi)
            $stmt = $this->conn->query("SELECT COUNT(id) as total_tours FROM tours");
            $data['total_tours'] = $stmt->fetchColumn();

            $stmt = $this->conn->query("SELECT COUNT(id) as total_departures FROM departures");
            $data['total_departures'] = $stmt->fetchColumn();

            $stmt = $this->conn->query("SELECT COUNT(id) as total_bookings FROM bookings");
            $data['total_bookings'] = $stmt->fetchColumn();

            $stmt = $this->conn->query("SELECT COUNT(id) as total_guides FROM staffs WHERE type IN ('quoc_te', 'noi_dia')");
            $data['total_guides'] = $stmt->fetchColumn();
            
            // 2. Thống kê Tài chính (ĐÃ THAY ĐỔI: Lấy tổng tích lũy từ trước đến nay)
            
            // Lấy TỔNG TÍCH LŨY tất cả doanh thu, chi phí, lợi nhuận từ bảng revenues
            $stmt = $this->conn->query("SELECT SUM(revenue) as total_revenue, SUM(expense) as total_expense, SUM(profit) as total_profit FROM revenues");
            $accumulated_stats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Lấy tháng và năm hiện tại để hiển thị trên tiêu đề
            $data['current_month'] = date('n');
            $data['current_year'] = date('Y');
            
            // Gán dữ liệu TÍCH LŨY
            $data['total_revenue_acc'] = $accumulated_stats['total_revenue'] ?? 0;
            $data['total_expense_acc'] = $accumulated_stats['total_expense'] ?? 0;
            $data['total_profit_acc'] = $accumulated_stats['total_profit'] ?? 0;

            // 3. Top Tour Bán Chạy (Không đổi)
            $stmt = $this->conn->query("SELECT t.name, SUM(b.quantity) as total_quantity
                                        FROM bookings b
                                        JOIN tours t ON b.tourId = t.id
                                        GROUP BY t.id, t.name
                                        ORDER BY total_quantity DESC
                                        LIMIT 5");
            $data['top_tours'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 4. Phân tích Booking theo Trạng thái (Không đổi)
            $stmt = $this->conn->query("SELECT status, COUNT(id) as count FROM bookings GROUP BY status");
            $data['booking_status'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            // 5. Lịch khởi hành sắp tới (Không đổi)
            $today = date('Y-m-d');
            $stmt = $this->conn->prepare("SELECT d.dateStart, d.dateEnd, t.name as tour_name, s.name as guide 
                                          FROM departures d
                                          JOIN tours t ON d.tourId = t.id
                                          LEFT JOIN staffs s ON d.guideId = s.id
                                          WHERE d.dateStart >= :today
                                          ORDER BY d.dateStart ASC
                                          LIMIT 5");
            $stmt->execute(['today' => $today]);
            $data['upcoming_departures'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 6. Danh sách Hướng dẫn viên gần đây (Không đổi)
            $stmt = $this->conn->query("SELECT name, experience, toursLed
                                        FROM staffs
                                        WHERE type IN ('quoc_te', 'noi_dia')
                                        ORDER BY toursLed DESC, name ASC
                                        LIMIT 3");
            $data['recent_guides'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 7. Dữ liệu Doanh thu/Chi phí 12 tháng gần nhất (ĐÃ THAY ĐỔI)
            $data['last_12_months_data'] = $this->getLastTwelveMonthsData();
            $data['last_12_months_revenue'] = array_column($data['last_12_months_data'], 'revenue');
            $data['last_12_months_expenses'] = array_column($data['last_12_months_data'], 'expense');
            $data['last_12_months_labels'] = array_column($data['last_12_months_data'], 'label');
            

        } catch (PDOException $e) {
            error_log("DashboardModel Error: " . $e->getMessage());
            $data = $this->getEmptyStats(date('n'), date('Y'));
        }

        return $data;
    }

    // Hàm lấy dữ liệu 12 tháng gần nhất (ĐÃ THAY ĐỔI)
    private function getLastTwelveMonthsData() {
        $data = [];
        $today = new DateTime();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = clone $today;
            $date->modify("-$i month");
            $month = $date->format('n');
            $year = $date->format('Y');

            $stmt = $this->conn->prepare("SELECT SUM(revenue) as revenue, SUM(expense) as expense
                                          FROM revenues 
                                          WHERE month = :month AND year = :year");
            $stmt->execute(['month' => $month, 'year' => $year]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $data[] = [
                'label' => 'T' . $month . '/' . $year, // Dùng định dạng ngắn gọn cho biểu đồ
                'revenue' => $row['revenue'] ?? 0,
                'expense' => $row['expense'] ?? 0,
            ];
        }
        return $data;
    }
    
    // Hàm khởi tạo dữ liệu rỗng (Cần điều chỉnh các key mới)
    private function getEmptyStats($month, $year) {
        return [
            'total_tours' => 0, 'total_departures' => 0, 'total_bookings' => 0, 'total_guides' => 0,
            'current_month' => $month, 'current_year' => $year,
            'total_revenue_acc' => 0, 'total_expense_acc' => 0, 'total_profit_acc' => 0,
            'top_tours' => [], 'booking_status' => [],
            'upcoming_departures' => [], 'recent_guides' => [],
            'last_12_months_data' => [], 
            'last_12_months_revenue' => array_fill(0, 12, 0), 
            'last_12_months_expenses' => array_fill(0, 12, 0),
            'last_12_months_labels' => array_fill(0, 12, 'T'),
        ];
    }
}