<?php
require_once './commons/function.php';

class GuideModel {

    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Lấy toàn bộ staff
    public function getAllStaffs() {
        $stmt = $this->conn->prepare("SELECT * FROM staffs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy tours
    public function getAllTours() {
        $stmt = $this->conn->prepare("SELECT * FROM tours");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy departures
    public function getAllDepartures() {
        $stmt = $this->conn->prepare("SELECT * FROM departures");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lọc hướng dẫn viên
    public function getGuides() {
        $staffs = $this->getAllStaffs();

        $guides = array_values(array_filter($staffs, function($s) {
            $type = strtolower(trim($s['type'] ?? ''));
            return $type === 'guide' || $type === 'quoc_te' || $type === 'noi_dia';
        }));

        // For each guide, compute whether they have any departures assigned
        foreach ($guides as &$g) {
            $gid = $g['id'] ?? null;
            if (!$gid) { $g['has_departure'] = false; $g['departures_count'] = 0; continue; }
            try {
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM departures WHERE guideId = ?");
                $stmt->execute([$gid]);
                $cnt = (int)$stmt->fetchColumn();
            } catch (Exception $e) {
                $cnt = 0;
            }
            $g['departures_count'] = $cnt;
            $g['has_departure'] = $cnt > 0;
        }

        return $guides;
    }

    // Lấy các booking chưa có staffId (chưa có HDV phân công)
    public function getUnassignedBookings() {
        $sql = "SELECT b.id, b.tourId, b.departuresId, t.name AS tour_name, d.dateStart, d.dateEnd
                FROM bookings b
                LEFT JOIN tours t ON b.tourId = t.id
                LEFT JOIN departures d ON b.departuresId = d.id
                WHERE (b.staffId IS NULL OR b.staffId = '' )
                ORDER BY d.dateStart ASC, b.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
