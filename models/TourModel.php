<?php
require_once './commons/function.php';

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
    
    // Lấy danh sách ảnh cho tour
    public function getImages($tour_id) {
        $sql = "SELECT image_url FROM tour_images WHERE tour_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $tour_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $out = [];
        foreach ($rows as $r) {
            $out[] = $r['image_url'];
        }
        return $out;
    }
    
    // Lấy chính sách cho tour (nếu có)
    public function getPolicy($tour_id) {
        $sql = "SELECT cancel_policy, refund_policy FROM tour_policies WHERE tour_id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $tour_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return ['cancel' => '', 'refund' => ''];
        return ['cancel' => $row['cancel_policy'] ?? '', 'refund' => $row['refund_policy'] ?? ''];
    }
    
    // Lấy lịch trình cho tour
    public function getSchedule($tour_id) {
        $sql = "SELECT * FROM tour_schedules WHERE tour_id = :id ORDER BY day";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $tour_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return [];

        $ids = array_map(function($r){ return (int)$r['id']; }, $rows);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $dsql = "SELECT * FROM tour_schedule_details WHERE schedule_id IN ($placeholders) ORDER BY start_time";
        $dstmt = $this->conn->prepare($dsql);
        $dstmt->execute($ids);
        $details = $dstmt->fetchAll(PDO::FETCH_ASSOC);
        $map = [];
        foreach ($details as $d) {
            $sid = (int)$d['schedule_id'];
            if (!isset($map[$sid])) $map[$sid] = [];
            $map[$sid][] = $d;
        }

        $out = [];
        foreach ($rows as $r) {
            $out[] = [
                'day' => (int)$r['day'],
                'activity' => $r['activity'] ?? null,
                'details' => $map[(int)$r['id']] ?? []
            ];
        }
        return $out;
    }

    // Lấy các đợt khởi hành (departures) cho tour
    public function getDeparturesByTourId($tour_id) {
        // Determine which columns exist in `departures` so we don't reference a missing column.
        try {
            $hasDriver = false;
            $hasDriverId = false;

            $stmt = $this->conn->query("SHOW COLUMNS FROM departures LIKE 'driver'");
            if ($stmt && $stmt->fetch()) $hasDriver = true;

            $stmt = $this->conn->query("SHOW COLUMNS FROM departures LIKE 'driverId'");
            if ($stmt && $stmt->fetch()) $hasDriverId = true;

            if ($hasDriver && $hasDriverId) {
                $driverExpr = "COALESCE(d.driver, dr.name) AS driver";
            } elseif ($hasDriver) {
                $driverExpr = "d.driver AS driver";
            } elseif ($hasDriverId) {
                $driverExpr = "dr.name AS driver";
            } else {
                $driverExpr = "NULL AS driver";
            }

            $driverIdExpr = $hasDriverId ? "d.driverId" : "NULL AS driverId";

            $sql = "SELECT d.id, d.tourId, d.dateStart, d.dateEnd, d.meetingPoint, d.guideId, " . $driverExpr . ", " . $driverIdExpr . " 
                    FROM departures d
                    LEFT JOIN drivers dr ON d.driverId = dr.id
                    WHERE d.tourId = :id
                    ORDER BY d.dateStart";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $tour_id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $out = [];
            foreach ($rows as $r) {
                $out[] = [
                    'id' => $r['id'],
                    'tourId' => $r['tourId'],
                    'dateStart' => $r['dateStart'],
                    'dateEnd' => $r['dateEnd'],
                    'meetingPoint' => $r['meetingPoint'],
                    'guideId' => $r['guideId'],
                    'driver' => $r['driver'] ?? null,
                    'driverId' => $r['driverId'] ?? null
                ];
            }
            return $out;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    // Xóa departure theo id
    public function deleteDepartureById($id) {
        try {
            $sql = "DELETE FROM departures WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Tạo đợt khởi hành mới
    public function createDeparture($data) {
        try {
            $sql = "INSERT INTO departures (id, tourId, dateStart, dateEnd, meetingPoint, guideId, driver) VALUES (:id, :tourId, :dateStart, :dateEnd, :meetingPoint, :guideId, :driver)";
            $stmt = $this->conn->prepare($sql);

            // nếu không có id, tạo id tự động (số tiếp theo)
            $id = $data['id'] ?? null;
            if (!$id) {
                $stmtMax = $this->conn->query("SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM departures");
                $row = $stmtMax->fetch();
                $id = $row['next_id'] ?? 1;
            }

            return $stmt->execute([
                ':id' => $id,
                ':tourId' => $data['tourId'] ?? null,
                ':dateStart' => $data['dateStart'] ?? null,
                ':dateEnd' => $data['dateEnd'] ?? null,
                ':meetingPoint' => $data['meetingPoint'] ?? '',
                ':guideId' => $data['guideId'] ?? null,
                ':driver' => $data['driver'] ?? ''
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Cập nhật đợt khởi hành
    public function updateDeparture($id, $data) {
        try {
            $sql = "UPDATE departures SET dateStart = :dateStart, dateEnd = :dateEnd, meetingPoint = :meetingPoint, guideId = :guideId, driver = :driver WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':dateStart' => $data['dateStart'] ?? null,
                ':dateEnd' => $data['dateEnd'] ?? null,
                ':meetingPoint' => $data['meetingPoint'] ?? '',
                ':guideId' => $data['guideId'] ?? null,
                ':driver' => $data['driver'] ?? '',
                ':id' => $id
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    // Cập nhật tour (đầy đủ: xóa dữ liệu cũ rồi insert mới)
    public function updateTourComplete($id, $data) {
        try {
            $this->conn->beginTransaction();

            // Update bảng tours
            $sql = "UPDATE tours SET type = :type, name = :name, tour_code = :tour_code, main_destination = :main_destination, short_description = :short_description, max_people = :max_people WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':type' => $data['type'],
                ':name' => $data['name'],
                ':tour_code' => $data['tour_code'],
                ':main_destination' => $data['main_destination'],
                ':short_description' => $data['short_description'] ?? '',
                ':max_people' => $data['max_people'] ?? null,
                ':id' => $id
            ]);

            // Update tour_prices
            $sql = "UPDATE tour_prices SET adult_price = :adult, child_price = :child WHERE tour_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':adult' => $data['price']['adult'] ?? 0,
                ':child' => $data['price']['child'] ?? 0,
                ':id' => $id
            ]);

            // Delete and re-insert images
            $sql = "DELETE FROM tour_images WHERE tour_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);

            if (!empty($data['images']) && is_array($data['images'])) {
                $sqlImg = "INSERT INTO tour_images (tour_id, image_url) VALUES (:id, :url)";
                $stmtImg = $this->conn->prepare($sqlImg);
                foreach ($data['images'] as $u) {
                    if (!$u) continue;
                    $stmtImg->execute([':id' => $id, ':url' => $u]);
                }
            }

            // Delete and re-insert policies
            $sql = "DELETE FROM tour_policies WHERE tour_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);

            if (!empty($data['policy']) && is_array($data['policy'])) {
                $sqlPol = "INSERT INTO tour_policies (tour_id, cancel_policy, refund_policy) VALUES (:id, :cancel, :refund)";
                $stmtPol = $this->conn->prepare($sqlPol);
                $stmtPol->execute([
                    ':id' => $id,
                    ':cancel' => $data['policy']['cancel'] ?? '',
                    ':refund' => $data['policy']['refund'] ?? ''
                ]);
            }

            // Delete and re-insert schedules and their details
            // first delete details linked to existing schedules
            $sql = "DELETE d FROM tour_schedule_details d JOIN tour_schedules s ON d.schedule_id = s.id WHERE s.tour_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);

            // then delete schedules
            $sql = "DELETE FROM tour_schedules WHERE tour_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);

            if (!empty($data['schedule']) && is_array($data['schedule'])) {
                $sqlSch = "INSERT INTO tour_schedules (tour_id, day, activity) VALUES (:id, :day, :activity)";
                $stmtSch = $this->conn->prepare($sqlSch);
                $sqlDet = "INSERT INTO tour_schedule_details (schedule_id, start_time, end_time, content) VALUES (:schedule_id, :start_time, :end_time, :content)";
                $stmtDet = $this->conn->prepare($sqlDet);
                foreach ($data['schedule'] as $s) {
                    $day = isset($s['day']) ? (int)$s['day'] : 1;
                    $activity = $s['activity'] ?? '';
                    $stmtSch->execute([':id' => $id, ':day' => $day, ':activity' => $activity]);
                    $newScheduleId = $this->conn->lastInsertId();
                    if (!empty($s['details']) && is_array($s['details'])) {
                        foreach ($s['details'] as $d) {
                            $start = $d['start_time'] ?? null;
                            $end = $d['end_time'] ?? null;
                            $content = $d['content'] ?? '';
                            $stmtDet->execute([':schedule_id' => $newScheduleId, ':start_time' => $start, ':end_time' => $end, ':content' => $content]);
                        }
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

    // Duplicate tour: create a new tour using provided data and return new id, or false
    public function duplicateTour($data) {
        try {
            $this->conn->beginTransaction();

            $nextId = $this->getNextId();
            $newId = $nextId;

            // Insert tour
            $sql = "INSERT INTO tours (id, type, name, tour_code, main_destination, short_description, max_people)
                    VALUES (:id, :type, :name, :tour_code, :main_destination, :short_description, :max_people)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $newId,
                ':type' => $data['type'],
                ':name' => $data['name'],
                ':tour_code' => $data['tour_code'] ?? 'TOUR' . $newId,
                ':main_destination' => $data['main_destination'],
                ':short_description' => $data['short_description'] ?? '',
                ':max_people' => $data['max_people'] ?? 30
            ]);

            // Price
            $sql = "INSERT INTO tour_prices (tour_id, adult_price, child_price) VALUES (:id, :adult, :child)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $newId,
                ':adult' => $data['price']['adult'] ?? 0,
                ':child' => $data['price']['child'] ?? 0
            ]);

            // Images
            if (!empty($data['images']) && is_array($data['images'])) {
                $sqlImg = "INSERT INTO tour_images (tour_id, image_url) VALUES (:id, :url)";
                $stmtImg = $this->conn->prepare($sqlImg);
                foreach ($data['images'] as $u) {
                    if (!$u) continue;
                    $stmtImg->execute([':id' => $newId, ':url' => $u]);
                }
            }

            // Policies
            if (!empty($data['policy']) && is_array($data['policy'])) {
                $sqlPol = "INSERT INTO tour_policies (tour_id, cancel_policy, refund_policy) VALUES (:id, :cancel, :refund)";
                $stmtPol = $this->conn->prepare($sqlPol);
                $stmtPol->execute([
                    ':id' => $newId,
                    ':cancel' => $data['policy']['cancel'] ?? '',
                    ':refund' => $data['policy']['refund'] ?? ''
                ]);
            }

            // Schedules
            if (!empty($data['schedule']) && is_array($data['schedule'])) {
                $sqlSch = "INSERT INTO tour_schedules (tour_id, day, activity) VALUES (:id, :day, :activity)";
                $stmtSch = $this->conn->prepare($sqlSch);
                foreach ($data['schedule'] as $s) {
                    $day = isset($s['day']) ? (int)$s['day'] : 1;
                    $activity = $s['activity'] ?? '';
                    $stmtSch->execute([':id' => $newId, ':day' => $day, ':activity' => $activity]);
                }
            }

            // Insert schedule details if provided
            if (!empty($data['schedule']) && is_array($data['schedule'])) {
                $sqlDet = "INSERT INTO tour_schedule_details (schedule_id, start_time, end_time, content) VALUES (:schedule_id, :start_time, :end_time, :content)";
                $stmtDet = $this->conn->prepare($sqlDet);
                // we need to fetch schedules we just inserted to get their ids in order
                $stmtFetch = $this->conn->prepare("SELECT id FROM tour_schedules WHERE tour_id = :id ORDER BY id ASC");
                $stmtFetch->execute([':id' => $newId]);
                $schRows = $stmtFetch->fetchAll(PDO::FETCH_COLUMN);
                $i = 0;
                foreach ($data['schedule'] as $s) {
                    $schId = $schRows[$i] ?? null;
                    if ($schId && !empty($s['details']) && is_array($s['details'])) {
                        foreach ($s['details'] as $d) {
                            $stmtDet->execute([':schedule_id' => $schId, ':start_time' => $d['start_time'] ?? null, ':end_time' => $d['end_time'] ?? null, ':content' => $d['content'] ?? '']);
                        }
                    }
                    $i++;
                }
            }

            $this->conn->commit();
            return (string)$newId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
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
            // ensure id fits VARCHAR(10)
            if (strlen((string)$data['id']) > 10) {
                $data['id'] = $nextId;
            }

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

            // Images (array of urls)
            if (!empty($data['images']) && is_array($data['images'])) {
                $sqlImg = "INSERT INTO tour_images (tour_id, image_url) VALUES (:id, :url)";
                $stmtImg = $this->conn->prepare($sqlImg);
                foreach ($data['images'] as $u) {
                    if (!$u) continue;
                    $stmtImg->execute([':id' => $data['id'], ':url' => $u]);
                }
            }

            // Policies
            if (!empty($data['policy']) && is_array($data['policy'])) {
                $sqlPol = "INSERT INTO tour_policies (tour_id, cancel_policy, refund_policy) VALUES (:id, :cancel, :refund)";
                $stmtPol = $this->conn->prepare($sqlPol);
                $stmtPol->execute([
                    ':id' => $data['id'],
                    ':cancel' => $data['policy']['cancel'] ?? '',
                    ':refund' => $data['policy']['refund'] ?? ''
                ]);
            }

            // Schedules
            if (!empty($data['schedule']) && is_array($data['schedule'])) {
                $sqlSch = "INSERT INTO tour_schedules (tour_id, day, activity) VALUES (:id, :day, :activity)";
                $stmtSch = $this->conn->prepare($sqlSch);
                foreach ($data['schedule'] as $s) {
                    $day = isset($s['day']) ? (int)$s['day'] : 1;
                    $activity = $s['activity'] ?? '';
                    $stmtSch->execute([':id' => $data['id'], ':day' => $day, ':activity' => $activity]);
                }
            }

            // Insert schedule details if provided
            if (!empty($data['schedule']) && is_array($data['schedule'])) {
                $sqlDet = "INSERT INTO tour_schedule_details (schedule_id, start_time, end_time, content) VALUES (:schedule_id, :start_time, :end_time, :content)";
                $stmtDet = $this->conn->prepare($sqlDet);
                $stmtFetch = $this->conn->prepare("SELECT id FROM tour_schedules WHERE tour_id = :id ORDER BY id ASC");
                $stmtFetch->execute([':id' => $data['id']]);
                $schRows = $stmtFetch->fetchAll(PDO::FETCH_COLUMN);
                $i = 0;
                foreach ($data['schedule'] as $s) {
                    $schId = $schRows[$i] ?? null;
                    if ($schId && !empty($s['details']) && is_array($s['details'])) {
                        foreach ($s['details'] as $d) {
                            $stmtDet->execute([':schedule_id' => $schId, ':start_time' => $d['start_time'] ?? null, ':end_time' => $d['end_time'] ?? null, ':content' => $d['content'] ?? '']);
                        }
                    }
                    $i++;
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

    // Lấy id kế tiếp
    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM tours";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}