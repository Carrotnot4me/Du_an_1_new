<?php
require_once __DIR__ . '/../commons/function.php';

class NoteModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "
            SELECT 
                n.*,
                b.email as customer_email,
                b.phone as customer_phone
            FROM notes n
            LEFT JOIN bookings b ON n.customerId = CAST(b.id AS UNSIGNED)
            ORDER BY n.id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $notes = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Nếu không tìm thấy qua booking, thử tìm qua customerId trực tiếp
        foreach ($notes as $note) {
            if (!$note->customer_email && $note->customerId) {
                // Thử tìm booking với id = customerId
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

    public function getByCustomerId($customerId) {
        $sql = "SELECT * FROM notes WHERE customerId = :customerId ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':customerId' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByEmail($email) {
        // Lấy notes theo email - tìm booking đầu tiên của email đó
        $sql = "
            SELECT n.*, b.email as customer_email, b.phone as customer_phone
            FROM notes n
            INNER JOIN bookings b ON n.customerId = CAST(b.id AS UNSIGNED)
            WHERE b.email = :email
            ORDER BY n.id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByBookingId($bookingId) {
        $sql = "SELECT * FROM notes WHERE customerId = CAST(:bookingId AS UNSIGNED) ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':bookingId' => $bookingId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function create($data) {
        try {
            $nextId = $this->getNextId();
            $sql = "INSERT INTO notes (id, customerId, type, content) VALUES (:id, :customerId, :type, :content)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $nextId,
                ':customerId' => $data['customerId'],
                ':type' => $data['type'],
                ':content' => $data['content']
            ]);
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        $sql = "SELECT * FROM notes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function update($id, $data) {
        try {
            // Kiểm tra note có tồn tại không
            $existingNote = $this->getById($id);
            if (!$existingNote) {
                return false;
            }

            $sql = "UPDATE notes SET type = :type, content = :content WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':type' => $data['type'],
                ':content' => $data['content']
            ]);
            
            // Kiểm tra xem có bản ghi nào được update không
            return $result && $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log('NoteModel update error: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM notes WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getTypes() {
        return [
            'an_chay' => 'Ăn chay',
            'di_ung' => 'Dị ứng',
            'suc_khoe' => 'Sức khỏe',
            'yeu_cau_dac_biet' => 'Yêu cầu đặc biệt',
            'khac' => 'Khác'
        ];
    }

    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM notes";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}

