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
                                             GROUP_CONCAT(DISTINCT b.status SEPARATOR ', ') AS statuses,
                                             CASE
                                                 WHEN d.dateStart IS NOT NULL AND CURDATE() < d.dateStart THEN 'Sắp đi'
                                                 WHEN d.dateStart IS NOT NULL AND d.dateEnd IS NOT NULL AND CURDATE() BETWEEN d.dateStart AND d.dateEnd THEN 'Đang đi'
                                                 WHEN d.dateEnd IS NOT NULL AND CURDATE() > d.dateEnd THEN 'Đã kết thúc'
                                                 ELSE 'Sắp đi'
                                             END AS computed_status
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
        $sql = "SELECT b.*, t.name AS tour_name, d.dateStart AS departureDate, d.dateEnd AS departureEnd,
                   CASE
                 WHEN d.dateStart IS NOT NULL AND CURDATE() < d.dateStart THEN 'Sắp đi'
                 WHEN d.dateStart IS NOT NULL AND d.dateEnd IS NOT NULL AND CURDATE() BETWEEN d.dateStart AND d.dateEnd THEN 'Đang đi'
                 WHEN d.dateEnd IS NOT NULL AND CURDATE() > d.dateEnd THEN 'Đã kết thúc'
                 ELSE 'Sắp đi'
                   END AS computed_status
            FROM bookings b
            LEFT JOIN tours t ON b.tourId = t.id
            LEFT JOIN departures d ON b.departuresId = d.id
            WHERE b.departuresId = ?
            ORDER BY b.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$departureId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookingById($bookingId) {
        // Determine which meeting column exists in departures to avoid SQL errors
        $meetingSelect = 'NULL AS meeting_point';
        try {
            $colStmt = $this->conn->query("DESCRIBE departures");
            $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('meetingPoint', $cols)) {
                $meetingSelect = 'd.meetingPoint AS meeting_point';
            } elseif (in_array('meeting_point', $cols)) {
                $meetingSelect = 'd.meeting_point AS meeting_point';
            }
        } catch (Exception $e) {
            // leave meetingSelect as NULL alias
        }

        $sql = "SELECT b.*, t.name AS tour_name, t.tour_code, tp.adult_price, tp.child_price, d.dateStart AS dateStart, d.dateEnd AS dateEnd, " . $meetingSelect . "
            FROM bookings b
            LEFT JOIN tours t ON b.tourId = t.id
            LEFT JOIN tour_prices tp ON t.id = tp.tour_id
            LEFT JOIN departures d ON b.departuresId = d.id
            WHERE b.id = ? LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRegistrantsByBooking($bookingId) {
        $stmt = $this->conn->prepare("SELECT * FROM booking_registrants WHERE booking_id = ? ORDER BY id ASC");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegistrantById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM booking_registrants WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateRegistrantStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE booking_registrants SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getBookingsByTour($tourId) {
        $sql = "SELECT b.*, t.name AS tour_name, d.dateStart AS departureDate, d.dateEnd AS departureEnd,
                   CASE
                 WHEN d.dateStart IS NOT NULL AND CURDATE() < d.dateStart THEN 'Sắp đi'
                 WHEN d.dateStart IS NOT NULL AND d.dateEnd IS NOT NULL AND CURDATE() BETWEEN d.dateStart AND d.dateEnd THEN 'Đang đi'
                 WHEN d.dateEnd IS NOT NULL AND CURDATE() > d.dateEnd THEN 'Đã kết thúc'
                 ELSE 'Sắp đi'
                   END AS computed_status
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
        // Determine next id
        $stmt = $this->conn->query("SELECT COALESCE(MAX(id),0)+1 AS next_id FROM booking_registrants");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $newId = $row ? (int)$row['next_id'] : 1;

        // Detect optional columns (adult_count, child_count, amount)
        $colsToCheck = ['adult_count','child_count','amount'];
        $existing = [];
        try {
            $colStmt = $this->conn->query("DESCRIBE booking_registrants");
            $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($colsToCheck as $c) {
                if (in_array($c, $cols)) $existing[] = $c;
            }
        } catch (Exception $e) {
            // ignore, fall back to basic columns
        }

        $base = ['id','booking_id','name','email','phone','type','quantity','status'];
        $allCols = array_merge($base, $existing);

        $placeholders = implode(',', array_fill(0, count($allCols), '?'));
        $sql = "INSERT INTO booking_registrants (" . implode(',', $allCols) . ") VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        $params = [];
        $params[] = $newId;
        $params[] = $data['booking_id'];
        $params[] = $data['name'] ?? $data['fullname'] ?? null;
        $params[] = $data['email'] ?? null;
        $params[] = $data['phone'] ?? null;
        $params[] = $data['type'] ?? null;
        $params[] = $data['quantity'] ?? null;
        $params[] = $data['status'] ?? null;

        if (in_array('adult_count', $existing)) $params[] = $data['adult_count'] ?? 0;
        if (in_array('child_count', $existing)) $params[] = $data['child_count'] ?? 0;
        if (in_array('amount', $existing)) $params[] = $data['amount'] ?? null;

        return $stmt->execute($params);
    }

    public function getLastRegistrantId() {
        $stmt = $this->conn->query("SELECT id FROM booking_registrants ORDER BY CAST(id AS UNSIGNED) DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : 0;
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
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return [];

        // fetch details for all schedule ids
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
                'day' => isset($r['day']) ? (int)$r['day'] : null,
                'activity' => $r['activity'] ?? null,
                'details' => $map[(int)$r['id']] ?? []
            ];
        }
        return $out;
    }

    /**
     * Get first image URL for a tour if available
     */
    public function getTourImage($tourId) {
        try {
            $stmt = $this->conn->prepare("SELECT image_url FROM tour_images WHERE tour_id = ? LIMIT 1");
            $stmt->execute([$tourId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['image_url'] : null;
        } catch (Exception $e) {
            error_log('getTourImage error: ' . $e->getMessage());
            return null;
        }
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

    /**
     * Return total payments amount recorded for a booking
     */
    public function getPaymentsTotalByBooking($bookingId) {
        try {
            // Check payment_history columns to decide query
            try {
                $colStmt = $this->conn->query("DESCRIBE payment_history");
                $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {
                $cols = [];
            }

            // support either snake_case or camelCase column naming
            $phCol = null;
            if (in_array('booking_id', $cols)) $phCol = 'booking_id';
            elseif (in_array('bookingId', $cols)) $phCol = 'bookingId';

            if ($phCol) {
                $sql = "SELECT COALESCE(SUM(amount),0) AS total FROM payment_history WHERE $phCol = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$bookingId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (float)($row['total'] ?? 0);
            }

            // If payment_history doesn't store booking id directly but stores registrant_id,
            // sum payments by joining booking_registrants -> bookings
            if (in_array('registrant_id', $cols) || in_array('registrantId', $cols)) {
                $regCol = in_array('registrant_id', $cols) ? 'registrant_id' : 'registrantId';
                $sql = "SELECT COALESCE(SUM(ph.amount),0) AS total
                        FROM payment_history ph
                        JOIN booking_registrants br ON ph." . $regCol . " = br.id
                        WHERE br.booking_id = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$bookingId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (float)($row['total'] ?? 0);
            }

            // fallback to legacy payments table (handle its column variants too)
            try {
                $pcolStmt = $this->conn->query("DESCRIBE payments");
                $pcols = $pcolStmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {
                $pcols = [];
            }
            $payCol = null;
            if (in_array('booking_id', $pcols)) $payCol = 'booking_id';
            elseif (in_array('bookingId', $pcols)) $payCol = 'bookingId';

            if ($payCol) {
                $sql = "SELECT COALESCE(SUM(amount),0) AS total FROM payments WHERE $payCol = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$bookingId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (float)($row['total'] ?? 0);
            }

            return 0;
        } catch (Exception $e) {
            error_log('getPaymentsTotalByBooking error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Sum payments for a specific registrant from payment_history (fallback 0)
     */
    public function getPaidByRegistrant($registrantId) {
        try {
            // detect column naming in payment_history
            try {
                $colStmt = $this->conn->query("DESCRIBE payment_history");
                $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Exception $e) {
                $cols = [];
            }
            $col = null;
            if (in_array('registrant_id', $cols)) $col = 'registrant_id';
            elseif (in_array('registrantId', $cols)) $col = 'registrantId';

            if ($col) {
                $sql = "SELECT COALESCE(SUM(amount),0) AS total FROM payment_history WHERE $col = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([$registrantId]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return (float)($row['total'] ?? 0);
            }

            return 0;
        } catch (Exception $e) {
            error_log('getPaidByRegistrant error: ' . $e->getMessage());
            return 0;
        }
    }
}
