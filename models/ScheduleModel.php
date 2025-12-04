<?php
require_once './commons/function.php';

class ScheduleModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAllDepartures() {
        $stmt = $this->conn->prepare("SELECT * FROM departures");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllTours() {
        $stmt = $this->conn->prepare("SELECT * FROM tours");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllStaffs() {
        $stmt = $this->conn->prepare("SELECT * FROM staffs");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getGuides() {
        $staffs = $this->getAllStaffs();
        $guides = array_filter($staffs, function($s) {
            $type = strtolower(trim($s['type']));
            return $type === 'guide' || $type === 'quoc_te' || $type === 'noi_dia';
        });
        return array_values($guides);
    }

    public function getDrivers() {
        $staffs = $this->getAllStaffs();
        $drivers = array_filter($staffs, function($s) {
            $type = strtolower(trim($s['type']));
            return $type === 'driver';
        });
        return array_values($drivers);
    }

    public function addDeparture($tourId, $date, $guideId, $driverId) {
        $stmt = $this->conn->prepare("INSERT INTO departures (tourId, date, assignedHDV, assignedDriver) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tourId, $date, $guideId, $driverId]);
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
