<?php
// models/CheckinModel.php

function getBookingListForCheckin($keyword = '') {
    $conn = connectDB();
    
    $sql = "
        SELECT 
            b.id AS booking_id, 
            b.email AS customer_email, 
            b.phone AS customer_phone, 
            t.name AS tour_name, 
            t.tour_code,
            b.quantity,
            b.departureDate,
            ch.is_checked_in,
            ch.checkin_time,
            ch.id AS checkin_id
        FROM bookings b
        JOIN tours t ON b.tourId = t.id
        LEFT JOIN checkins ch ON b.id = ch.bookingId
        WHERE b.status = 'Đã xác nhận' OR b.status = 'Hoàn thành'
    ";

    $params = [];
    if (!empty($keyword)) {
        $sql .= " AND (t.name LIKE :keyword_name OR t.tour_code LIKE :keyword_code OR b.id = :keyword_id)";
        $params[':keyword_name'] = '%' . $keyword . '%';
        $params[':keyword_code'] = '%' . $keyword . '%'; // Sử dụng LIKE cho tour_code nếu muốn tìm kiếm linh hoạt
        $params[':keyword_id'] = $keyword;
    }

    $sql .= " ORDER BY b.departureDate ASC, b.id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function performCheckin($bookingId) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT id FROM checkins WHERE bookingId = :booking_id");
    $stmt->execute([':booking_id' => $bookingId]);
    if ($stmt->fetch()) {
        return false; 
    }

    $stmtBooking = $conn->prepare("SELECT tourId, departureDate FROM bookings WHERE id = :booking_id AND (status = 'Đã xác nhận' OR status = 'Hoàn thành')");
    $stmtBooking->execute([':booking_id' => $bookingId]);
    $booking = $stmtBooking->fetch();
    
    if (!$booking) {
        return false; 
    }

    $departureId = null;
    $stmtDeparture = $conn->prepare("SELECT id FROM departures WHERE tourId = :tourId AND dateStart = :dateStart");
    $stmtDeparture->execute([
        ':tourId' => $booking['tourId'],
        ':dateStart' => $booking['departureDate']
    ]);
    $departure = $stmtDeparture->fetch();
    if ($departure) {
        $departureId = $departure['id'];
    }

    $sql = "INSERT INTO checkins (bookingId, departureId, checkin_time, is_checked_in) VALUES (:booking_id, :departure_id, NOW(), 1)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([
        ':booking_id' => $bookingId,
        ':departure_id' => $departureId
    ]);
}