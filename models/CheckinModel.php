<?php
// models/CheckinModel.php
require_once __DIR__ . '/../commons/function.php';

function getBookingListForCheckin($keyword = '') {
    $conn = connectDB();

    // Build a schema-safe query: bookings table does not contain email/phone/quantity/departureDate
    // We aggregate registrants and checkins per booking using booking_registrants -> customers -> checkins
    $sql = "
        SELECT
            b.id AS booking_id,
            t.id AS tour_id,
            t.name AS tour_name,
            COALESCE(t.tour_code, '') AS tour_code,
            COALESCE(d.dateStart, '') AS departureDate,
            COALESCE(SUM(CAST(br.quantity AS UNSIGNED)), 0) AS quantity,
            COALESCE(SUM(CASE WHEN ch.id IS NOT NULL THEN 1 ELSE 0 END), 0) AS checked_customers
        FROM bookings b
        JOIN tours t ON b.tourId = t.id
        LEFT JOIN departures d ON b.departuresId = d.id
        LEFT JOIN booking_registrants br ON br.booking_id = b.id
        LEFT JOIN customers c ON c.registrants_id = br.id
        LEFT JOIN checkins ch ON ch.customer_id = c.id
        WHERE (b.status = 'Đã xác nhận' OR b.status = 'Hoàn thành')
    ";

    $params = [];
    if (!empty($keyword)) {
        $sql .= " AND (t.name LIKE :keyword_name OR t.tour_code LIKE :keyword_code OR b.id = :keyword_id)";
        $params[':keyword_name'] = '%' . $keyword . '%';
        $params[':keyword_code'] = '%' . $keyword . '%';
        $params[':keyword_id'] = $keyword;
    }

    $sql .= " GROUP BY b.id ORDER BY departureDate ASC, CAST(b.id AS UNSIGNED) DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function performCheckin($bookingId) {
    $conn = connectDB();

    // Perform per-customer checkin for all customers linked to this booking.
    $stmt = $conn->prepare("SELECT c.id AS customer_id
        FROM booking_registrants br
        JOIN customers c ON c.registrants_id = br.id
        WHERE br.booking_id = :booking_id");
    $stmt->execute([':booking_id' => $bookingId]);
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$customers) return false;

    $inserted = 0;
    $ins = $conn->prepare("INSERT INTO checkins (customer_id, checkin_time, status) VALUES (:customer_id, NOW(), 'Đã checkin')");
    $check = $conn->prepare("SELECT id FROM checkins WHERE customer_id = :customer_id LIMIT 1");
    foreach ($customers as $c) {
        $check->execute([':customer_id' => $c['customer_id']]);
        if ($check->fetch()) continue;
        if ($ins->execute([':customer_id' => $c['customer_id']])) $inserted++;
    }
    return $inserted > 0;
}

function undoCheckin($bookingId) {
    $conn = connectDB();

    // Delete checkins for customers belonging to this booking
    $sql = "DELETE ch FROM checkins ch
        JOIN customers c ON ch.customer_id = c.id
        JOIN booking_registrants br ON c.registrants_id = br.id
        WHERE br.booking_id = :booking_id";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([':booking_id' => $bookingId]);
}

// --- migrated from CheckinModelExtras.php ---
function isCustomerCheckedIn($customerId) {
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT id, checkin_time FROM checkins WHERE customer_id = ? LIMIT 1");
    $stmt->execute([$customerId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function checkCustomer($customerId) {
    $conn = connectDB();
    // avoid duplicate
    $exists = isCustomerCheckedIn($customerId);
    if ($exists) return false;
    $stmt = $conn->prepare("INSERT INTO checkins (customer_id, checkin_time, status) VALUES (?, NOW(), 'Đã checkin')");
    return $stmt->execute([$customerId]);
}

function undoCheckCustomer($customerId) {
    $conn = connectDB();
    $stmt = $conn->prepare("DELETE FROM checkins WHERE customer_id = ?");
    return $stmt->execute([$customerId]);
}
