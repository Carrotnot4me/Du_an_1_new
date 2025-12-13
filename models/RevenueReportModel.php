<?php
require_once __DIR__ . '/../commons/function.php';

class RevenueReportModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getRevenueReport($year, $month) {
        $data = [];
        
        // --- 1. Chuẩn bị tham số cho Chi tiết & Tổng kết ---
        $params = [':year' => $year];
        
        // WHERE clause cho truy vấn chi tiết (sử dụng tiền tố 'r')
        $whereDetail = "WHERE r.year = :year";

        // WHERE clause cho truy vấn tổng kết (KHÔNG sử dụng tiền tố)
        $whereSummary = "WHERE year = :year"; 

        if ($month > 0) {
            $whereDetail .= " AND r.month = :month";
            $whereSummary .= " AND month = :month";
            $params[':month'] = $month; // Thêm tham số tháng
        }

        try {
            // 1. Lấy dữ liệu chi tiết (JOIN với tours)
            $query = "SELECT 
                        r.id, r.month, r.year, r.revenue, r.expense, r.profit,
                        t.name AS tour_name, t.tour_code
                      FROM 
                        revenues r
                      LEFT JOIN 
                        tours t ON r.tourId = t.id 
                      $whereDetail
                      ORDER BY 
                        r.year DESC, r.month DESC, r.id DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $data['details'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Lấy tổng cộng (Total Summary)
            $querySummary = "SELECT 
                                SUM(revenue) AS total_revenue, 
                                SUM(expense) AS total_expense, 
                                SUM(profit) AS total_profit 
                             FROM 
                                revenues 
                             $whereSummary";
            
            $stmtSummary = $this->conn->prepare($querySummary);
            // $params này đã bao gồm cả :year và :month (nếu có), và chúng khớp với $whereSummary
            $stmtSummary->execute($params); 
            $data['summary'] = $stmtSummary->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Hiển thị lỗi mạnh mẽ hơn để debug
            error_log("RevenueReportModel Error: " . $e->getMessage());
            $data['error'] = $e->getMessage(); // Báo cáo lỗi cho debug
            $data['details'] = [];
            $data['summary'] = ['total_revenue' => 0, 'total_expense' => 0, 'total_profit' => 0];
        }

        return $data;
    }

    public function getAvailableYears() {
        try {
            // Lưu ý: Nếu connectDB() thất bại, $this->conn có thể không phải là object, gây lỗi
            if (!$this->conn) {
                return [date('Y')];
            }
            $stmt = $this->conn->query("SELECT DISTINCT year FROM revenues ORDER BY year DESC");
            return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'year');
        } catch (PDOException $e) {
            error_log("Error fetching years: " . $e->getMessage());
            return [date('Y')];
        }
    }
}