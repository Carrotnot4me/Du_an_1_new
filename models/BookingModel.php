<?php
require_once './commons/function.php';

class BookingModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
        // ensure booking_registrants table exists
        try {
            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS booking_registrants (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    booking_id VARCHAR(20) NOT NULL,
                    name VARCHAR(255) DEFAULT NULL,
                    email VARCHAR(255) DEFAULT NULL,
                    phone VARCHAR(50) DEFAULT NULL,
                    note TEXT,
                    INDEX (booking_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
            );
        } catch (Exception $e) {
            // ignore creation errors but log
            error_log('booking_registrants create: ' . $e->getMessage());
        }

        // ensure bookings.guideId column exists (for storing selected guide)
        try {
            $check = $this->conn->prepare("SELECT COUNT(*) as c FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'bookings' AND COLUMN_NAME = 'guideId'");
            $check->execute();
            $row = $check->fetch(PDO::FETCH_ASSOC);
            if (isset($row['c']) && (int)$row['c'] === 0) {
                $this->conn->exec("ALTER TABLE bookings ADD COLUMN guideId VARCHAR(20) DEFAULT NULL");
            }
        } catch (Exception $e) {
            error_log('guideId check/create: ' . $e->getMessage());
        }
    }

    // Create booking master record
    public function createBooking($data) {
        try {
            $id = $this->getNextBookingId();
            $sql = "INSERT INTO bookings (id, tourId, email, phone, quantity, departureDate, status, travelType, type, total_amount, guideId)
                    VALUES (:id, :tourId, :email, :phone, :quantity, :departureDate, :status, :travelType, :type, :total, :guideId)";
            $stmt = $this->conn->prepare($sql);
            $total = isset($data['total_amount']) ? (int)$data['total_amount'] : 0;
            $status = $data['status'] ?? 'Đã đặt';
            $ok = $stmt->execute([
                ':id' => $id,
                ':tourId' => $data['tourId'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':quantity' => isset($data['quantity']) ? (int)$data['quantity'] : 1,
                ':departureDate' => $data['departureDate'] ?? null,
                ':status' => $status,
                ':travelType' => $data['travelType'] ?? null,
                ':type' => $data['type'] ?? null,
                ':total' => $total,
                ':guideId' => $data['guideId'] ?? null
            ]);
            if (!$ok) return false;

            // If registrants provided, insert into booking_registrants
            if (!empty($data['registrants']) && is_array($data['registrants'])) {
                $sqlR = "INSERT INTO booking_registrants (booking_id, name, email, phone, note) VALUES (:booking_id, :name, :email, :phone, :note)";
                $stmtR = $this->conn->prepare($sqlR);
                foreach ($data['registrants'] as $r) {
                    $stmtR->execute([
                        ':booking_id' => $id,
                        ':name' => $r['name'] ?? null,
                        ':email' => $r['email'] ?? null,
                        ':phone' => $r['phone'] ?? null,
                        ':note' => $r['note'] ?? ''
                    ]);
                }
            }

            return $id;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getStaffs() {
        $sql = "SELECT id, name, type, phone, avatar FROM staffs ORDER BY name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all bookings with registrant counts
    public function getAllBookings() {
        $sql = "SELECT b.*, 
                       (SELECT COUNT(*) FROM booking_registrants WHERE booking_id = b.id) as registrant_count
                FROM bookings b
                ORDER BY CAST(b.id AS UNSIGNED) DESC";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Get registrants for a booking
    public function getRegistrantsByBookingId($bookingId) {
        $sql = "SELECT id, booking_id, name, email, phone, note FROM booking_registrants WHERE booking_id = :booking_id ORDER BY id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':booking_id' => $bookingId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    private function getNextBookingId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM bookings";
        try {
            $stmt = $this->conn->query($sql);
            $row = $stmt->fetch();
            return $row['next_id'] ?? 1;
        } catch (Exception $e) {
            return 1;
        }
    }
}
