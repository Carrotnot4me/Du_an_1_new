<?php
require_once __DIR__ . '/../models/CustomerModel.php';
require_once __DIR__ . '/../models/BookingModel.php';
require_once __DIR__ . '/../models/NoteModel.php';

class CustomerController {
    private $model;
    private $bookingModel;
    private $noteModel;

    public function __construct() {
        $this->model = new CustomerModel();
        $this->bookingModel = new BookingModel();
        $this->noteModel = new NoteModel();
    }

    // Render the customer list page (view contains its own small AJAX handler)
    public function list() {
        require_once __DIR__ . '/../views/admin/customer-list.php';
    }

    // JSON endpoint: return customers for a tour or departure
    public function getCustomers() {
        header('Content-Type: application/json; charset=utf-8');
        $db = connectDB();
        $departureId = $_GET['departure_id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;
        try {
            if ($departureId) {
                $sql = "SELECT c.* , br.booking_id, br.id AS registrant_id
                    FROM customers c
                    JOIN booking_registrants br ON c.registrants_id = br.id
                    JOIN bookings b ON br.booking_id = b.id
                    WHERE b.departuresId = :dep
                    ORDER BY c.id ASC";
                $stmt = $db->prepare($sql);
                $stmt->execute([':dep' => $departureId]);
            } elseif ($tourId) {
                $sql = "SELECT c.* , br.booking_id, br.id AS registrant_id
                    FROM customers c
                    JOIN booking_registrants br ON c.registrants_id = br.id
                    JOIN bookings b ON br.booking_id = b.id
                    WHERE b.tourId = :tour
                    ORDER BY c.id ASC";
                $stmt = $db->prepare($sql);
                $stmt->execute([':tour' => $tourId]);
            } else {
                echo json_encode(['success' => true, 'customers' => []], JSON_UNESCAPED_UNICODE);
                exit;
            }
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'customers' => $rows], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    // Return customer detail and related bookings/notes by email
    public function getCustomerDetail() {
        header('Content-Type: application/json; charset=utf-8');
        $email = $_GET['email'] ?? null;
        if (!$email) {
            echo json_encode(['success' => false, 'message' => 'Missing email']);
            exit;
        }

        try {
            $db = connectDB();
            $sql = "SELECT br.*, b.id AS booking_id, b.tourId, t.name AS tour_name, d.dateStart AS departureDate
                FROM booking_registrants br
                LEFT JOIN bookings b ON br.booking_id = b.id
                LEFT JOIN tours t ON b.tourId = t.id
                LEFT JOIN departures d ON b.departuresId = d.id
                WHERE br.email = :email OR br.phone = :email";
            $stmt = $db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $registrants = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Basic customer summary
            $summary = [
                'email' => $email,
                'phone' => $registrants[0]['phone'] ?? null,
                'total_bookings' => count($registrants),
                'total_spent' => 0
            ];

            // notes via NoteModel
            $notes = $this->noteModel->getByEmail($email);

            echo json_encode(['success' => true, 'customer' => $summary, 'bookings' => $registrants, 'notes' => $notes], JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
