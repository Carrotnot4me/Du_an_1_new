<?php
require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php'; 

class BookingModel{

    private $conn;

    public function __construct(){
        $this->conn = connectDB();
        // Debug: Kiểm tra kết nối
        // if (!$this->conn) die("LỖI KẾT NỐI DB!");
    }

    public function getAll(){
        return $this->conn->query("SELECT * FROM bookings ORDER BY id DESC")->fetchAll();
    }

    public function insert($data){
        $sql = "INSERT INTO bookings (tourId, departureId, staffId, email, phone, quantity, type, total_amount, status)
                VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'],
            $data['departure_id'],
            $data['staff_id'],
            $data['email'],
            $data['phone'],
            $data['quantity'],
            $data['type'],
            $data['total_amount'],
            $data['status']
        ]);
    }

    public function getTours(){
        $result = $this->conn->query("SELECT t.*, tp.adult_price, tp.child_price FROM tours t LEFT JOIN tour_prices tp ON t.id = tp.tour_id ORDER BY t.name");
        return $result->fetchAll();
    }

    public function getDepartures(){
        $result = $this->conn->query("SELECT d.*, t.name as tour_name FROM departures d JOIN tours t ON d.tourId = t.id ORDER BY d.dateStart DESC");
        return $result->fetchAll();
    }

    public function getStaffs(){
        $result = $this->conn->query("SELECT * FROM staffs ORDER BY name");
        return $result->fetchAll();
    }

    public function getDrivers(){
        $result = $this->conn->query("SELECT * FROM drivers ORDER BY name");
        return $result->fetchAll();
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM bookings WHERE id=?");
        $stmt->execute([$id]);
    }

    public function getTourDetail($tourId) {
    $sql = "SELECT t.*, 
                   tp.adult_price, tp.child_price 
            FROM tours t
            LEFT JOIN tour_prices tp ON t.id = tp.tour_id
            WHERE t.id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetch();
}

public function getTourSchedule($tourId) {
    $sql = "SELECT * FROM tour_schedules 
            WHERE tour_id = ? 
            ORDER BY day";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll();
}

public function getLastBookingId() {
    $stmt = $this->conn->query("SELECT id FROM bookings ORDER BY CAST(id AS UNSIGNED) DESC LIMIT 1");
    $row = $stmt->fetch();
    if (!$row) return 0;
    return (int)$row['id'];
}

public function insertBooking($data) {
    // Align with actual bookings table columns from travel_db.sql:
    // `bookings` columns: id, tourId, status, driverId, staffId, departuresId
    $sql = "INSERT INTO bookings (id, tourId, status, driverId, staffId, departuresId)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $ok = $stmt->execute([
        $data['id'],
        $data['tour_id'],
        $data['status'] ?? null,
        $data['driver_id'] ?? null,
        $data['staff_id'] ?? null,
        $data['departure_id'] ?? null
    ]);

    if ($ok) return $data['id'];
    return false;
}

public function getLastDepartureId() {
    $stmt = $this->conn->query("SELECT id FROM departures ORDER BY CAST(id AS UNSIGNED) DESC LIMIT 1");
    $row = $stmt->fetch();
    if (!$row) return 0;
    return (int)$row['id'];
}

public function insertDeparture($data) {
    $newId = (string)($this->getLastDepartureId() + 1);
    $sql = "INSERT INTO departures (id, tourId, dateStart, dateEnd, meetingPoint, guideId, driverId) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        $newId,
        $data['tour_id'],
        $data['date_start'],
        $data['date_end'],
        $data['meeting_point'],
        $data['guide_id'] ?? null,
        $data['driver_id'] ?? null
    ]);
    return $newId;
}

public function getDepartureById($departureId) {
    $sql = "SELECT * FROM departures WHERE id = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$departureId]);
    return $stmt->fetch();
}
    public function insertCustomer($data) {
        try {
            $sql = "INSERT INTO booking_registrants (id, booking_id, name, email, phone, type, quantity, status)
                    SELECT COALESCE(MAX(id),0)+1, ?, ?, ?, ?, ?, ?, ? FROM booking_registrants";
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute([
                $data['booking_id'],
                $data['name'] ?? $data['fullname'] ?? null,
                $data['email'] ?? null,
                $data['phone'] ?? null,
                $data['type'] ?? null,
                $data['quantity'] ?? null,
                $data['status'] ?? null
            ]);
            return $ok;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

}

