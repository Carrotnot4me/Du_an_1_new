<?php
require_once __DIR__ . '/../commons/function.php';

class BookingModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "
            SELECT 
                b.*,
                t.name as tour_name,
                t.type as tour_type
            FROM bookings b
            LEFT JOIN tours t ON b.tourId = t.id
            ORDER BY b.departureDate DESC, b.id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id) {
        $sql = "
            SELECT 
                b.*,
                t.name as tour_name,
                t.type as tour_type,
                t.main_destination
            FROM bookings b
            LEFT JOIN tours t ON b.tourId = t.id
            WHERE b.id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getByEmail($email) {
        $sql = "
            SELECT 
                b.*,
                t.name as tour_name,
                t.type as tour_type
            FROM bookings b
            LEFT JOIN tours t ON b.tourId = t.id
            WHERE b.email = :email
            ORDER BY b.departureDate DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE bookings SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':status' => $status
            ]);
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getStatuses() {
        return [
            'Đang xử lý',
            'Đã xác nhận',
            'Đã cọc',
            'Hoàn thành',
            'Đã hủy'
        ];
    }
}

