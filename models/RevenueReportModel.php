
<?php
require_once './commons/function.php';

class RevenueReportModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getRevenueReport($year, $month) {
        $data = [];
        
        $params = [':year' => $year];
        
        $whereDetail = "WHERE r.year = :year";
        $whereSummary = "WHERE year = :year"; 

        if ($month > 0) {
            $whereDetail .= " AND r.month = :month";
            $whereSummary .= " AND month = :month";
            $params[':month'] = $month;
        }

        try {
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

            $querySummary = "SELECT 
                                SUM(revenue) AS total_revenue, 
                                SUM(expense) AS total_expense, 
                                SUM(profit) AS total_profit 
                             FROM 
                                revenues 
                             $whereSummary";
            
            $stmtSummary = $this->conn->prepare($querySummary);
            $stmtSummary->execute($params); 
            $data['summary'] = $stmtSummary->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("RevenueReportModel Error: " . $e->getMessage());
            $data['error'] = $e->getMessage();
            $data['details'] = [];
            $data['summary'] = ['total_revenue' => 0, 'total_expense' => 0, 'total_profit' => 0];
        }

        return $data;
    }

    public function getPaymentHistory($year, $month) {
        $params = [':year' => $year];
        $where = "WHERE YEAR(p.payment_date) = :year";

        if ($month > 0) {
            $where .= " AND MONTH(p.payment_date) = :month";
            $params[':month'] = $month;
        }

        try {
            $query = "SELECT 
                        p.id, p.payment_date, p.amount, p.method, p.status,
                        b.id AS bookingId, b.total_amount AS total_booking_amount,
                        t.name AS tour_name
                      FROM 
                        payments p
                      JOIN 
                        bookings b ON p.bookingId = b.id 
                      LEFT JOIN 
                        tours t ON b.tourId = t.id 
                      $where
                      ORDER BY 
                        p.payment_date DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("PaymentHistory Error: " . $e->getMessage());
            return [];
        }
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