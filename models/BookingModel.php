<?php
require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

class BookingModel {

    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    /* ======================
     * BASIC STATS / LISTING
     * ====================== */

    public function getAll() {
        return $this->conn
            ->query("SELECT * FROM bookings ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalBookingsCount() {
        $stmt = $this->conn->query("SELECT COUNT(*) AS cnt FROM bookings");
        $row  = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['cnt'] ?? 0);
    }

    /**
     * Summary per tour (used for dashboard / report)
     */
    public function getTourSummaries() {
        // Summarize bookings per tour + per departure (separate rows when same tour has different departures)
        $sql = "SELECT t.id AS tour_id,
                       b.departuresId AS departure_id,
                       d.dateStart AS departure_start,
                       d.dateEnd AS departure_end,
                       t.type,
                       t.name,
                       t.tour_code,
                       t.main_destination,
                       (SELECT ti.image_url FROM tour_images ti WHERE ti.tour_id = t.id LIMIT 1) AS image_url,
                       t.max_people,
                       COALESCE(SUM(CAST(br.quantity AS UNSIGNED)), 0) AS total_quantity,
                       GROUP_CONCAT(DISTINCT b.status SEPARATOR ', ') AS statuses
                FROM tours t
                INNER JOIN bookings b ON b.tourId = t.id
                LEFT JOIN departures d ON d.id = b.departuresId
                LEFT JOIN booking_registrants br ON br.booking_id = b.id
                GROUP BY t.id, b.departuresId
                ORDER BY t.name ASC, departure_start DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return all bookings for a specific departure id
     */
    public function getBookingsByDeparture($departureId) {
        $sql = "SELECT b.*, t.name AS tour_name, d.dateStart AS departureDate
                FROM bookings b
                LEFT JOIN tours t ON b.tourId = t.id
                LEFT JOIN departures d ON b.departuresId = d.id
                WHERE b.departuresId = ?
                ORDER BY b.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$departureId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingsByTour($tourId) {
        $sql = "SELECT b.*, t.name AS tour_name, d.dateStart AS departureDate
                FROM bookings b
                LEFT JOIN tours t ON b.tourId = t.id
                LEFT JOIN departures d ON b.departuresId = d.id
                WHERE b.tourId = ?
                ORDER BY b.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ======================
     * INSERT / CREATE
     * ====================== */

    // Full booking insert (frontend form)
    public function insert($data) {
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
            $data['status'],
        ]);
    }

    // Low-level booking insert (admin / auto id)
    public function getLastBookingId() {
        $stmt = $this->conn->query("SELECT id FROM bookings ORDER BY CAST(id AS UNSIGNED) DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : 0;
    }

    public function insertBooking($data) {
        $sql = "INSERT INTO bookings (id, tourId, status, driverId, staffId, departuresId)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            $data['id'],
            $data['tour_id'],
            $data['status'] ?? null,
            $data['driver_id'] ?? null,
            $data['staff_id'] ?? null,
            $data['departure_id'] ?? null,
        ]);
        return $ok ? $data['id'] : false;
    }

    public function insertCustomer($data) {
        $sql = "INSERT INTO booking_registrants (id, booking_id, name, email, phone, type, quantity, status)
                SELECT COALESCE(MAX(id),0)+1, ?, ?, ?, ?, ?, ?, ? FROM booking_registrants";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['booking_id'],
            $data['name'] ?? $data['fullname'] ?? null,
            $data['email'] ?? null,
            $data['phone'] ?? null,
            $data['type'] ?? null,
            $data['quantity'] ?? null,
            $data['status'] ?? null,
        ]);
    }

    /* ======================
     * TOURS / DEPARTURES
     * ====================== */

    public function getTours() {
        $sql = "SELECT t.*, tp.adult_price, tp.child_price
                FROM tours t
                LEFT JOIN tour_prices tp ON t.id = tp.tour_id
                ORDER BY t.name";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTourDetail($tourId) {
        $sql = "SELECT t.*, tp.adult_price, tp.child_price
                FROM tours t
                LEFT JOIN tour_prices tp ON t.id = tp.tour_id
                WHERE t.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTourSchedule($tourId) {
        $sql = "SELECT * FROM tour_schedules WHERE tour_id = ? ORDER BY day";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartures() {
        $sql = "SELECT d.*, t.name AS tour_name
                FROM departures d
                JOIN tours t ON d.tourId = t.id
                ORDER BY d.dateStart DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastDepartureId() {
        $stmt = $this->conn->query("SELECT id FROM departures ORDER BY CAST(id AS UNSIGNED) DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : 0;
    }

    public function insertDeparture($data) {
        $newId = (string)($this->getLastDepartureId() + 1);
        $sql = "INSERT INTO departures (id, tourId, dateStart, dateEnd, meetingPoint, guideId, driverId)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $newId,
            $data['tour_id'],
            $data['date_start'],
            $data['date_end'],
            $data['meeting_point'],
            $data['guide_id'] ?? null,
            $data['driver_id'] ?? null,
        ]);
        return $newId;
    }

    public function getDepartureById($departureId) {
        $stmt = $this->conn->prepare("SELECT * FROM departures WHERE id = ? LIMIT 1");
        $stmt->execute([$departureId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ======================
     * STAFF / DRIVER
     * ====================== */

    public function getStaffs() {
        return $this->conn
            ->query("SELECT * FROM staffs ORDER BY name")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDrivers() {
        return $this->conn
            ->query("SELECT * FROM drivers ORDER BY name")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ======================
     * DELETE (SAFE)
     * ====================== */

    public function delete($id) {
        try {
            $this->conn->beginTransaction();

            // find departure
            $stmt = $this->conn->prepare("SELECT departuresId FROM bookings WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $depId = $stmt->fetchColumn();

            // delete registrants
            $stmt = $this->conn->prepare("DELETE FROM booking_registrants WHERE booking_id = ?");
            $stmt->execute([$id]);

            // delete booking
            $stmt = $this->conn->prepare("DELETE FROM bookings WHERE id = ?");
            $stmt->execute([$id]);

            // delete unused departure
            if ($depId) {
                $check = $this->conn->prepare("SELECT COUNT(*) FROM bookings WHERE departuresId = ?");
                $check->execute([$depId]);
                if ((int)$check->fetchColumn() === 0) {
                    $del = $this->conn->prepare("DELETE FROM departures WHERE id = ?");
                    $del->execute([$depId]);
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteByTour($tourId) {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("SELECT id FROM bookings WHERE tourId = ?");
            $stmt->execute([$tourId]);
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if ($ids) {
                $in = implode(',', array_fill(0, count($ids), '?'));
                $this->conn->prepare("DELETE FROM booking_registrants WHERE booking_id IN ($in)")->execute($ids);
                $this->conn->prepare("DELETE FROM bookings WHERE id IN ($in)")->execute($ids);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Delete all bookings (and registrants) for a specific departure id
     */
    public function deleteByDeparture($departureId) {
        try {
            $this->conn->beginTransaction();

            // find bookings for this departure
            $stmt = $this->conn->prepare("SELECT id FROM bookings WHERE departuresId = ?");
            $stmt->execute([$departureId]);
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if ($ids) {
                $in = implode(',', array_fill(0, count($ids), '?'));
                $this->conn->prepare("DELETE FROM booking_registrants WHERE booking_id IN ($in)")->execute($ids);
                $this->conn->prepare("DELETE FROM bookings WHERE id IN ($in)")->execute($ids);
            }

            // After deleting bookings, remove departure if no bookings reference it
            $check = $this->conn->prepare("SELECT COUNT(*) FROM bookings WHERE departuresId = ?");
            $check->execute([$departureId]);
            if ((int)$check->fetchColumn() === 0) {
                $del = $this->conn->prepare("DELETE FROM departures WHERE id = ?");
                $del->execute([$departureId]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
}
