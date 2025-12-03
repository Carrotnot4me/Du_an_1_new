<?php
require_once './commons/function.php';

class GuideScheduleModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy thông tin HDV theo ID
    public function getStaffById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM staffs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy lịch làm việc, nếu $guideId = 0 thì lấy tất cả
    public function getSchedules($guideId = 0) {
        if ($guideId > 0) {
            $stmt = $this->conn->prepare("
                SELECT d.*, t.name AS tour_name, s.name AS guide_name
                FROM departures d
                JOIN tours t ON d.tourId = t.id
                LEFT JOIN staffs s ON d.guideId = s.id
                WHERE d.guideId = ?
                ORDER BY d.dateStart ASC
            ");
            $stmt->execute([$guideId]);
        } else {
            $stmt = $this->conn->prepare("
                SELECT d.*, t.name AS tour_name, s.name AS guide_name
                FROM departures d
                JOIN tours t ON d.tourId = t.id
                LEFT JOIN staffs s ON d.guideId = s.id
                ORDER BY d.dateStart ASC
            ");
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }
}
