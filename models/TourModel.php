<?php
require_once __DIR__ . '/../commons/function.php';

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

    public function getById($id) {
        // Lấy thông tin tour cơ bản
        $sql = "
            SELECT 
                t.*,
                tp.adult_price,
                tp.child_price,
                ts.hotel,
                ts.restaurant,
                ts.transport
            FROM tours t
            LEFT JOIN tour_prices tp ON t.id = tp.tour_id
            LEFT JOIN tour_suppliers ts ON t.id = ts.tour_id
            WHERE t.id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $tour = $stmt->fetch(PDO::FETCH_OBJ);
        
        if (!$tour) {
            return null;
        }

        // Lấy tất cả hình ảnh
        $sql = "SELECT image_url FROM tour_images WHERE tour_id = :id ORDER BY tour_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $tour->images = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Lấy chính sách
        $sql = "SELECT cancel_policy, refund_policy FROM tour_policies WHERE tour_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $policy = $stmt->fetch(PDO::FETCH_OBJ);
        if ($policy) {
            $tour->cancel_policy = $policy->cancel_policy;
            $tour->refund_policy = $policy->refund_policy;
        }

        // Lấy lịch trình
        $sql = "SELECT day, activity FROM tour_schedules WHERE tour_id = :id ORDER BY day";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $tour->schedules = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Lấy departures (chuyến khởi hành)
        $sql = "
            SELECT 
                d.*,
                s.name as guide_name,
                s.email as guide_email
            FROM departures d
            LEFT JOIN staffs s ON d.guideId = s.id
            WHERE d.tourId = :id
            ORDER BY d.dateStart ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $tour->departures = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $tour;
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

    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();

            // Cập nhật số lượng khách tối đa
            if (isset($data['max_people'])) {
                $sql = "UPDATE tours SET max_people = :max_people WHERE id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':id' => $id,
                    ':max_people' => $data['max_people']
                ]);
            }

            // Cập nhật nhà cung cấp (suppliers)
            if (isset($data['suppliers'])) {
                $sql = "SELECT COUNT(*) FROM tour_suppliers WHERE tour_id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':id' => $id]);
                $exists = $stmt->fetchColumn() > 0;

                if ($exists) {
                    $sql = "UPDATE tour_suppliers SET 
                            hotel = :hotel,
                            restaurant = :restaurant,
                            transport = :transport
                            WHERE tour_id = :id";
                } else {
                    $sql = "INSERT INTO tour_suppliers (tour_id, hotel, restaurant, transport)
                            VALUES (:id, :hotel, :restaurant, :transport)";
                }
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':id' => $id,
                    ':hotel' => $data['suppliers']['hotel'] ?? null,
                    ':restaurant' => $data['suppliers']['restaurant'] ?? null,
                    ':transport' => $data['suppliers']['transport'] ?? null
                ]);
            }

            // Cập nhật ngày kết thúc của departure (nếu có)
            if (isset($data['departure_date_end']) && isset($data['departure_id'])) {
                $sql = "UPDATE departures SET dateEnd = :dateEnd WHERE id = :departure_id AND tourId = :tour_id";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([
                    ':dateEnd' => $data['departure_date_end'],
                    ':departure_id' => $data['departure_id'],
                    ':tour_id' => $id
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

    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM tours";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}