<?php
require_once __DIR__ . '/../commons/function.php';

class GuideLogModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "
            SELECT 
                gl.*,
                t.name as tour_name,
                t.type as tour_type,
                s.name as guide_name,
                d.dateStart,
                d.dateEnd
            FROM guideslogs gl
            LEFT JOIN tours t ON gl.tourId = t.id
            LEFT JOIN staffs s ON gl.guideId = s.id
            LEFT JOIN departures d ON d.tourId = gl.tourId AND d.guideId = gl.guideId
            ORDER BY gl.day DESC, gl.id DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Load images for each log
        foreach ($logs as $log) {
            $log->images = $this->getImages($log->id);
        }
        
        return $logs;
    }

    public function getByDate($date) {
        // Lấy tất cả logs, không lọc theo ngày departure
        // Vì guideslogs không có trường date riêng, nên hiển thị tất cả logs
        // Người dùng có thể xem logs của tất cả các tour
        try {
            $sql = "
                SELECT 
                    gl.*,
                    t.name as tour_name,
                    t.type as tour_type,
                    s.name as guide_name,
                    d.dateStart,
                    d.dateEnd
                FROM guideslogs gl
                LEFT JOIN tours t ON gl.tourId = t.id
                LEFT JOIN staffs s ON gl.guideId = s.id
                LEFT JOIN departures d ON d.tourId = gl.tourId AND d.guideId = gl.guideId
                ORDER BY gl.id DESC, gl.day DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $logs = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Load images for each log
            foreach ($logs as $log) {
                $log->images = $this->getImages($log->id);
            }
            
            return $logs;
        } catch (Exception $e) {
            error_log('GuideLogModel getByDate error: ' . $e->getMessage());
            return [];
        }
    }

    public function getByTourId($tourId) {
        $sql = "
            SELECT 
                gl.*,
                t.name as tour_name,
                t.type as tour_type,
                s.name as guide_name,
                d.dateStart,
                d.dateEnd
            FROM guideslogs gl
            LEFT JOIN tours t ON gl.tourId = t.id
            LEFT JOIN staffs s ON gl.guideId = s.id
            LEFT JOIN departures d ON d.tourId = gl.tourId AND d.guideId = gl.guideId
            WHERE gl.tourId = :tourId
            ORDER BY gl.day ASC, gl.id ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':tourId' => $tourId]);
        $logs = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Load images for each log
        foreach ($logs as $log) {
            $log->images = $this->getImages($log->id);
        }
        
        return $logs;
    }

    public function getById($id) {
        $sql = "
            SELECT 
                gl.*,
                t.name as tour_name,
                t.type as tour_type,
                s.name as guide_name,
                d.dateStart,
                d.dateEnd
            FROM guideslogs gl
            LEFT JOIN tours t ON gl.tourId = t.id
            LEFT JOIN staffs s ON gl.guideId = s.id
            LEFT JOIN departures d ON d.tourId = gl.tourId AND d.guideId = gl.guideId
            WHERE gl.id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $log = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($log) {
            // Lấy hình ảnh đính kèm
            $log->images = $this->getImages($id);
        }
        
        return $log;
    }

    public function getImages($logId) {
        $sql = "SELECT image_url FROM guideslog_images WHERE log_id = :log_id ORDER BY image_url";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':log_id' => $logId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function create($data) {
        try {
            $this->conn->beginTransaction();
            
            $nextId = $this->getNextId();
            
            $sql = "INSERT INTO guideslogs (id, guideId, tourId, day, content) 
                    VALUES (:id, :guideId, :tourId, :day, :content)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $nextId,
                ':guideId' => $data['guideId'] ?? null,
                ':tourId' => $data['tourId'] ?? null,
                ':day' => $data['day'] ?? 1,
                ':content' => $data['content'] ?? ''
            ]);

            // Thêm hình ảnh nếu có
            if (!empty($data['images']) && is_array($data['images'])) {
                $sqlImg = "INSERT INTO guideslog_images (log_id, image_url) VALUES (:log_id, :image_url)";
                $stmtImg = $this->conn->prepare($sqlImg);
                foreach ($data['images'] as $imageUrl) {
                    if (trim($imageUrl)) {
                        $stmtImg->execute([
                            ':log_id' => $nextId,
                            ':image_url' => trim($imageUrl)
                        ]);
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('GuideLogModel create error: ' . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();
            
            $sql = "UPDATE guideslogs 
                    SET guideId = :guideId, tourId = :tourId, day = :day, content = :content 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':guideId' => $data['guideId'] ?? null,
                ':tourId' => $data['tourId'] ?? null,
                ':day' => $data['day'] ?? 1,
                ':content' => $data['content'] ?? ''
            ]);

            // Xóa hình ảnh cũ và thêm mới
            if (isset($data['images'])) {
                $sqlDel = "DELETE FROM guideslog_images WHERE log_id = :log_id";
                $stmtDel = $this->conn->prepare($sqlDel);
                $stmtDel->execute([':log_id' => $id]);

                if (!empty($data['images']) && is_array($data['images'])) {
                    $sqlImg = "INSERT INTO guideslog_images (log_id, image_url) VALUES (:log_id, :image_url)";
                    $stmtImg = $this->conn->prepare($sqlImg);
                    foreach ($data['images'] as $imageUrl) {
                        if (trim($imageUrl)) {
                            $stmtImg->execute([
                                ':log_id' => $id,
                                ':image_url' => trim($imageUrl)
                            ]);
                        }
                    }
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('GuideLogModel update error: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $this->conn->beginTransaction();
            
            // Xóa hình ảnh trước
            $sqlDel = "DELETE FROM guideslog_images WHERE log_id = :log_id";
            $stmtDel = $this->conn->prepare($sqlDel);
            $stmtDel->execute([':log_id' => $id]);
            
            // Xóa log
            $sql = "DELETE FROM guideslogs WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('GuideLogModel delete error: ' . $e->getMessage());
            return false;
        }
    }

    public function getTours() {
        $sql = "SELECT id, name, type FROM tours ORDER BY name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getGuides() {
        $sql = "SELECT id, name FROM staffs WHERE type IN ('quoc_te', 'noi_dia') ORDER BY name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM guideslogs";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}

