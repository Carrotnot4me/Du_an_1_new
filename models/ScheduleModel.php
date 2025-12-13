<?php
// File: models/ScheduleModel.php

require_once './commons/function.php';

class ScheduleModel {
    public $conn;

    public function __construct() {
        if (function_exists('connectDB')) {
            try {
                $this->conn = connectDB();
            } catch (PDOException $e) {
                die("Lỗi kết nối DB: " . $e->getMessage());
            }
        } else {
            die("Lỗi: Hàm connectDB() không được tìm thấy.");
        }
    }

    public function getDepartures() {
        // [ĐÃ SỬA]: Lấy t.id thay cho t.code
        $sql = "
            SELECT 
                d.id as departure_id,
                d.dateStart,
                d.dateEnd,
                t.name as tour_name,
                t.id as tour_id,
                (SELECT image_url FROM tour_images WHERE tour_id = t.id LIMIT 1) as image
            FROM departures d
            JOIN tours t ON d.tourId = t.id
            ORDER BY d.dateStart ASC
        ";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateDeparture($depId, $tourId, $date, $guideId, $driverId) {
        $stmt = $this->conn->prepare("UPDATE departures SET tourId=?, date=?, assignedHDV=?, assignedDriver=? WHERE id=?");
        $stmt->execute([$tourId, $date, $guideId, $driverId, $depId]);
    }

    public function deleteDeparture($depId) {
        $stmt = $this->conn->prepare("DELETE FROM departures WHERE id=?");
        $stmt->execute([$depId]);
    }
}
