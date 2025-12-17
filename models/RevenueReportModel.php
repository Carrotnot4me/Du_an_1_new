
<?php
require_once __DIR__ . '/../commons/function.php';

class RevenueReportModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Trong class RevenueReportModel

public function getRevenueReport($year, $month = 0) {
    $conn = connectDB();

    // Tổng doanh thu thực thu từ payment_history (hoặc bảng payments)
    $sqlRevenue = "SELECT COALESCE(SUM(amount), 0) as total_revenue 
                   FROM payment_history 
                   WHERE YEAR(created_at) = :year";
    $params = [':year' => $year];

    if ($month > 0) {
        $sqlRevenue .= " AND MONTH(created_at) = :month";
        $params[':month'] = $month;
    }

    $stmt = $conn->prepare($sqlRevenue);
    $stmt->execute($params);
    $totalRevenue = (float)$stmt->fetchColumn();

    // Nếu bạn có bảng chi phí riêng (expenses), thì tính tương tự
    // Ví dụ:
    // $totalExpense = ... tính từ bảng expenses

    $totalExpense = 0; // tạm thời nếu chưa có
    $totalProfit = $totalRevenue - $totalExpense;

    // Trả về summary đúng
    return [
        'summary' => [
            'total_revenue' => $totalRevenue,
            'total_expense' => $totalExpense,
            'total_profit'  => $totalProfit
        ],
        // có thể thêm chi tiết khác nếu cần
    ];
}

public function getPaymentHistory($year, $month = 0) {
    $conn = connectDB();

    $sql = "SELECT 
                p.id,
                p.amount,
                p.note,
                p.created_at,
                -- Nếu bảng payment_history có thêm cột method và status (từ code addPayment)
                -- thì thêm vào SELECT, nếu không có thì bỏ qua
                -- p.method,
                -- p.status,
                
                b.id AS bookingId,
                t.name AS tour_name,
                c.name AS customer_name  -- Tùy chọn: hiển thị tên khách nếu cần
            FROM payment_history p
            LEFT JOIN booking_registrants c ON p.registrant_id = c.id
            LEFT JOIN bookings b ON c.booking_id = b.id
            LEFT JOIN tours t ON b.tourId = t.id
            WHERE YEAR(p.created_at) = :year";

    $params = [':year' => $year];

    if ($month > 0) {
        $sql .= " AND MONTH(p.created_at) = :month";
        $params[':month'] = $month;
    }

    $sql .= " ORDER BY p.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

    public function getAvailableYears() {
        try {
            if (!$this->conn) {
                return [date('Y')];
            }
            $stmt = $this->conn->query("SELECT DISTINCT year FROM revenues ORDER BY year DESC");
            $revenueYears = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'year');
            
            $stmt = $this->conn->query("SELECT DISTINCT YEAR(payment_date) AS year FROM payments ORDER BY year DESC");
            $paymentYears = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'year');
            
            $allYears = array_unique(array_merge($revenueYears, $paymentYears));
            rsort($allYears);

            return !empty($allYears) ? $allYears : [date('Y')];

        } catch (PDOException $e) {
            error_log("Error fetching years: " . $e->getMessage());
            return [date('Y')];
        }
    }

    public function getAllTours() {
        try {
            $query = "SELECT id, name, tour_code FROM tours ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching tours: " . $e->getMessage());
            return [];
        }
    }

    public function addPayment($bookingId, $amount, $method, $status = 'Chờ xử lý', $transactionCode = NULL) {
        try {
            $query = "INSERT INTO payments (bookingId, amount, method, status, transaction_code) 
                      VALUES (:bookingId, :amount, :method, :status, :transactionCode)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':bookingId' => $bookingId,
                ':amount' => $amount,
                ':method' => $method,
                ':status' => $status,
                ':transactionCode' => $transactionCode
            ]);

            if ($status == 'Hoàn thành') {
                $queryUpdateBooking = "UPDATE bookings SET status = 'Hoàn thành' WHERE id = :bookingId AND status != 'Hủy'";
                $stmtUpdate = $this->conn->prepare($queryUpdateBooking);
                $stmtUpdate->execute([':bookingId' => $bookingId]);
            }

            return true;
        } catch (PDOException $e) {
            error_log("AddPayment Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function updatePaymentStatus($paymentId, $status) {
        try {
            $query = "UPDATE payments SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':status' => $status, ':id' => $paymentId]);

            $stmtBooking = $this->conn->prepare("SELECT bookingId FROM payments WHERE id = :id");
            $stmtBooking->execute([':id' => $paymentId]);
            $bookingId = $stmtBooking->fetchColumn();

            if ($bookingId) {
                if ($status == 'Hoàn thành') {
                    $queryUpdateBooking = "UPDATE bookings SET status = 'Hoàn thành' WHERE id = :bookingId AND status != 'Hủy'";
                    $stmtUpdate = $this->conn->prepare($queryUpdateBooking);
                    $stmtUpdate->execute([':bookingId' => $bookingId]);
                }
            }

            return true;
        } catch (PDOException $e) {
            error_log("UpdatePaymentStatus Error: " . $e->getMessage());
            return false;
        }
    }
}