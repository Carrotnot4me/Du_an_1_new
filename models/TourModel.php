<?php
require_once './commons/function.php';

class TourModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

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

            // Images
            if (!empty($data['images']) && is_array($data['images'])) {
                $sql = "INSERT INTO tour_images (tour_id, image_url) VALUES (:id, :url)";
                $stmt = $this->conn->prepare($sql);
                foreach ($data['images'] as $url) {
                    if (trim($url)) $stmt->execute([':id' => $data['id'], ':url' => trim($url)]);
                }
            }

            // Policies
            $sql = "INSERT INTO tour_policies (tour_id, cancel_policy, refund_policy) VALUES (:id, :c, :r)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $data['id'],
                ':c' => $data['policy']['cancel'] ?? '',
                ':r' => $data['policy']['refund'] ?? ''
            ]);

            // Schedule
            if (!empty($data['schedule']) && is_array($data['schedule'])) {
                $sql = "INSERT INTO tour_schedules (tour_id, day, activity) VALUES (:id, :day, :act)";
                $stmt = $this->conn->prepare($sql);
                foreach ($data['schedule'] as $s) {
                    if (!empty($s['day']) && !empty($s['activity'])) {
                        $stmt->execute([
                            ':id' => $data['id'],
                            ':day' => $s['day'],
                            ':act' => $s['activity']
                        ]);
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM tours";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}