<?php
require_once __DIR__ . '/../commons/function.php';

class TourModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy tất cả tour
    public function getAll() {
        $sql = "
            SELECT 
                t.*,
                tp.adult_price,
                tp.child_price,
                COALESCE((SELECT image_url FROM tour_images WHERE tour_id = t.id ORDER BY id LIMIT 1), '/assets/placeholder.jpg') AS image_url
            FROM tours t
            LEFT JOIN tour_prices tp ON t.id = tp.tour_id
            ORDER BY CAST(t.id AS UNSIGNED) ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Lấy tour theo ID
    public function getTourById($id) {
        $sql = "
            SELECT 
                t.*,
                tp.adult_price,
                tp.child_price,
                COALESCE((SELECT image_url FROM tour_images WHERE tour_id = t.id ORDER BY id LIMIT 1), '/assets/placeholder.jpg') AS image_url
            FROM tours t
            LEFT JOIN tour_prices tp ON t.id = tp.tour_id
            WHERE t.id = :id
            LIMIT 1
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật tour
    public function updateTour($id, $data) {
        try {
            $this->conn->beginTransaction();

            // Update bảng tours
            $sql = "UPDATE tours SET name = :name, type = :type, main_destination = :main_destination WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':type' => $data['type'],
                ':main_destination' => $data['main_destination'],
                ':id' => $id
            ]);

            // Update bảng tour_prices nếu có
            if (isset($data['price'])) {
                $sql = "UPDATE tour_prices SET adult_price = :adult, child_price = :child WHERE tour_id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':adult' => $data['price']['adult'] ?? 0,
                    ':child' => $data['price']['child'] ?? 0,
                    ':id' => $id
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    // Xóa tour
    public function delete($id) {
        try {
            $this->conn->beginTransaction();
            $tables = ['tour_prices', 'tour_images', 'tour_policies', 'tour_schedules'];
            foreach ($tables as $table) {
                $sql = "DELETE FROM $table WHERE tour_id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':id' => $id]);
            }
            $sql = "DELETE FROM tours WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Tạo tour mới
    public function create($data) {
        try {
            $this->conn->beginTransaction();

            $nextId = $this->getNextId();
            $data['id'] = $data['id'] ?? $nextId;

            // Insert tour
            $sql = "INSERT INTO tours (id, type, name, tour_code, main_destination, short_description, max_people)
                    VALUES (:id, :type, :name, :tour_code, :main_destination, :short_description, :max_people)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $data['id'],
                ':type' => $data['type'],
                ':name' => $data['name'],
                ':tour_code' => $data['tour_code'] ?? 'TOUR' . $data['id'],
                ':main_destination' => $data['main_destination'],
                ':short_description' => $data['short_description'] ?? '',
                ':max_people' => $data['max_people'] ?? 30
            ]);

            // Price
            $sql = "INSERT INTO tour_prices (tour_id, adult_price, child_price) VALUES (:id, :adult, :child)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $data['id'],
                ':adult' => $data['price']['adult'] ?? 0,
                ':child' => $data['price']['child'] ?? 0
            ]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    // Lấy id kế tiếp
    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM tours";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}