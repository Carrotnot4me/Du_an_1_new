<?php
require_once __DIR__ . '/../commons/function.php';

class NoteModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "SELECT n.* FROM notes n ORDER BY n.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByCustomerId($customerId) {
        $sql = "SELECT * FROM notes WHERE customerId = :customerId ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':customerId' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByEmail($email) {
        // Find notes for bookings that have a registrant with this email
        $sql = "SELECT n.* FROM notes n WHERE n.customerId IN (
            SELECT DISTINCT booking_id FROM booking_registrants WHERE email = :email
        ) ORDER BY n.id DESC";
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

