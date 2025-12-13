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

        $guides = array_filter($staffs, function($s) {
            $type = strtolower(trim($s['type']));
            return $type === 'guide' || $type === 'quoc_te' || $type === 'noi_dia';
        });

        return array_values($guides);
    }
}
