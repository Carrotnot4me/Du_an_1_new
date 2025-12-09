<?php
require_once __DIR__ . '/../commons/function.php';

class GuideSpecialModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    /**
     * Lấy tất cả yêu cầu đặc biệt kèm thông tin khách hàng
     */
    public function getAll() {
        $sql = "
            SELECT 
                n.*,
                b.email as customer_email,
                b.phone as customer_phone,
                b.id as booking_id,
                t.name as tour_name,
                t.type as tour_type
            FROM notes n
            LEFT JOIN bookings b ON n.customerId = CAST(b.id AS UNSIGNED)
            LEFT JOIN tours t ON b.tourId = t.id
            ORDER BY n.id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Nếu không tìm thấy qua booking, thử tìm qua customerId trực tiếp
        foreach ($notes as $note) {
            if (!$note->customer_email && $note->customerId) {
                $sql2 = "SELECT email, phone FROM bookings WHERE id = :id LIMIT 1";
                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->execute([':id' => $note->customerId]);
                $booking = $stmt2->fetch(PDO::FETCH_OBJ);
                if ($booking) {
                    $note->customer_email = $booking->email;
                    $note->customer_phone = $booking->phone;
                }
            }
        }
        
        return $notes;
    }

    /**
     * Lấy yêu cầu đặc biệt theo email khách hàng
     */
    public function getByCustomerEmail($email) {
        $sql = "
            SELECT 
                n.*,
                b.email as customer_email,
                b.phone as customer_phone,
                b.id as booking_id,
                t.name as tour_name,
                t.type as tour_type
            FROM notes n
            INNER JOIN bookings b ON n.customerId = CAST(b.id AS UNSIGNED)
            LEFT JOIN tours t ON b.tourId = t.id
            WHERE b.email = :email
            ORDER BY n.id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy thông tin khách hàng kèm tất cả yêu cầu đặc biệt
     */
    public function getCustomerWithSpecialRequests($email) {
        // Lấy thông tin khách hàng
        $customerSql = "
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
        $stmt = $this->conn->prepare($customerSql);
        $stmt->execute([':email' => $email]);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (!$customer) {
            return null;
        }
        
        // Lấy tất cả yêu cầu đặc biệt của khách hàng
        $customer->special_requests = $this->getByCustomerEmail($email);
        
        return $customer;
    }

    /**
     * Lấy yêu cầu đặc biệt theo ID
     */
    public function getById($id) {
        $sql = "
            SELECT 
                n.*,
                b.email as customer_email,
                b.phone as customer_phone,
                b.id as booking_id,
                t.name as tour_name,
                t.type as tour_type
            FROM notes n
            LEFT JOIN bookings b ON n.customerId = CAST(b.id AS UNSIGNED)
            LEFT JOIN tours t ON b.tourId = t.id
            WHERE n.id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Tạo yêu cầu đặc biệt mới
     */
    public function create($data) {
        try {
            $nextId = $this->getNextId();
            $sql = "INSERT INTO notes (id, customerId, type, content) VALUES (:id, :customerId, :type, :content)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $nextId,
                ':customerId' => $data['customerId'],
                ':type' => $data['type'] ?? 'yeu_cau_dac_biet',
                ':content' => $data['content']
            ]);
            return true;
        } catch (Exception $e) {
            error_log('GuideSpecialModel create error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cập nhật yêu cầu đặc biệt
     */
    public function update($id, $data) {
        try {
            $existingNote = $this->getById($id);
            if (!$existingNote) {
                return false;
            }

            $sql = "UPDATE notes SET type = :type, content = :content WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':type' => $data['type'] ?? 'yeu_cau_dac_biet',
                ':content' => $data['content']
            ]);
            
            return $result && $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log('GuideSpecialModel update error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Xóa yêu cầu đặc biệt
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM notes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log('GuideSpecialModel delete error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Lấy danh sách loại yêu cầu đặc biệt
     */
    public function getTypes() {
        return [
            'an_chay' => 'Ăn chay',
            'di_ung' => 'Dị ứng',
            'suc_khoe' => 'Sức khỏe',
            'yeu_cau_dac_biet' => 'Yêu cầu đặc biệt',
            'khac' => 'Khác'
        ];
    }

    /**
     * Lấy danh sách khách hàng có yêu cầu đặc biệt
     */
    public function getCustomersWithRequests() {
        $sql = "
            SELECT DISTINCT
                b.email,
                b.phone,
                COUNT(DISTINCT n.id) as total_requests,
                MAX(n.id) as latest_request_id
            FROM bookings b
            INNER JOIN notes n ON n.customerId = CAST(b.id AS UNSIGNED)
            WHERE b.email IS NOT NULL AND b.email != ''
            GROUP BY b.email, b.phone
            ORDER BY latest_request_id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Tìm booking ID từ email
     */
    public function findBookingIdByEmail($email) {
        $sql = "SELECT id FROM bookings WHERE email = :email ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result ? $result->id : null;
    }

    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM notes";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}

