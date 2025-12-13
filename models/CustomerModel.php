<?php
require_once __DIR__ . '/../commons/function.php';

class CustomerModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        // Lấy danh sách customers từ bookings (group by email)
        $sql = "
            SELECT 
                b.email,
                b.phone,
                COUNT(DISTINCT b.id) as total_bookings,
                SUM(b.total_amount) as total_spent,
                MIN(b.departureDate) as first_booking_date,
                MAX(b.departureDate) as last_booking_date
            FROM bookings b
            WHERE b.email IS NOT NULL AND b.email != ''
            GROUP BY b.email, b.phone
            ORDER BY last_booking_date DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByEmail($email) {
        $sql = "
            SELECT 
                b.email,
                b.phone,
                COUNT(DISTINCT b.id) as total_bookings,
                SUM(b.total_amount) as total_spent,
                MIN(b.departureDate) as first_booking_date,
                MAX(b.departureDate) as last_booking_date
            FROM bookings b
            WHERE b.email = :email
            GROUP BY b.email, b.phone
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getBookingsByEmail($email) {
        $sql = "
            SELECT 
                b.*,
                t.name as tour_name,
                t.type as tour_type,
                t.main_destination
            FROM bookings b
            LEFT JOIN tours t ON b.tourId = t.id
            WHERE b.email = :email
            ORDER BY b.departureDate DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

