<?php
require_once './models/BookingModel.php';
require_once './models/TourModel.php';

class BookingController {
    private $model;
    private $tourModel;

    public function __construct() {
        $this->model = new BookingModel();
        $this->tourModel = new TourModel();
    }

    // Show booking form (admin)
    public function form() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=showLogin');
            exit;
        }
        if (($_SESSION['user_role'] ?? 'user') !== 'admin') {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=trangchu');
            exit;
        }

        $tours = [];
        $tourObjects = $this->tourModel->getAll();
        foreach ($tourObjects as $t) {
            $row = is_array($t) ? $t : (array)$t;
            $id = $row['id'] ?? '';
            $tours[] = [
                'id' => $id,
                'name' => $row['name'] ?? '',
                'tour_code' => $row['tour_code'] ?? '',
                'main_destination' => $row['main_destination'] ?? '',
                'images' => $this->tourModel->getImages($id),
                'price' => ['adult' => (int)($row['adult_price'] ?? 0), 'child' => (int)($row['child_price'] ?? 0)],
                'schedule' => $this->tourModel->getSchedule($id),
                'departures' => $this->tourModel->getDeparturesByTourId($id)
            ];
        }

        $staffs = $this->model->getStaffs();
        include __DIR__ . '/../views/admin/booking-tour.php';
    }

    // Handle booking create (supports multiple registrants)
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=booking');
            exit;
        }

        $tourId = $_POST['tourId'] ?? null;
        // accept either contact_email/contact_phone (new) or email/phone (older view)
        $contact_email = trim($_POST['contact_email'] ?? $_POST['email'] ?? '');
        $contact_phone = trim($_POST['contact_phone'] ?? $_POST['phone'] ?? '');
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $departureDate = $_POST['departureDate'] ?? null;
        $travelType = $_POST['travelType'] ?? '';
        $type = $_POST['type'] ?? '';
        $guideId = $_POST['guideId'] ?? null;

        if (!$tourId || !$contact_email || !$contact_phone) {
            $_SESSION['flash_error'] = 'Thiếu thông tin bắt buộc (tour, email, phone).';
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=booking');
            exit;
        }

        // build registrants array from form arrays
        $names = $_POST['reg_name'] ?? [];
        $emails = $_POST['reg_email'] ?? [];
        $phones = $_POST['reg_phone'] ?? [];
        $notes = $_POST['reg_note'] ?? [];
        $registrants = [];
        $count = max(count($names), count($emails), count($phones));
        for ($i = 0; $i < $count; $i++) {
            $n = trim($names[$i] ?? '');
            $e = trim($emails[$i] ?? '');
            $p = trim($phones[$i] ?? '');
            $no = trim($notes[$i] ?? '');
            if ($n || $e || $p) {
                $registrants[] = ['name' => $n, 'email' => $e, 'phone' => $p, 'note' => $no];
            }
        }

        // compute total using tour adult price
        $tourRow = $this->tourModel->getTourById($tourId);
        $adult = isset($tourRow['adult_price']) ? (int)$tourRow['adult_price'] : 0;
        $total = $adult * max(1, $quantity);

        $data = [
            'tourId' => $tourId,
            'email' => $contact_email,
            'phone' => $contact_phone,
            'quantity' => $quantity,
            'departureDate' => $departureDate,
            'travelType' => $travelType,
            'type' => $type,
            'guideId' => $guideId,
            'total_amount' => $total,
            'status' => 'Đã cọc',
            'registrants' => $registrants
        ];

        $id = $this->model->createBooking($data);
        if ($id) {
            $_SESSION['flash_ok'] = 'Tạo booking thành công (ID: ' . htmlspecialchars($id) . ')';
        } else {
            $_SESSION['flash_error'] = 'Tạo booking thất bại.';
        }
        header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=bookingList');
        exit;
    }

    // Show booking list with registrants and financial summary
    public function listBookings() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=showLogin');
            exit;
        }
        if (($_SESSION['user_role'] ?? 'user') !== 'admin') {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=trangchu');
            exit;
        }

        $bookings = $this->model->getAllBookings();
        $bookingDetails = [];
        $totalQuantity = 0;
        $totalRevenue = 0;

        foreach ($bookings as $b) {
            $registrants = $this->model->getRegistrantsByBookingId($b['id']);
            $quantity = (int)($b['quantity'] ?? 0);
            $totalAmount = (int)($b['total_amount'] ?? 0);
            $totalQuantity += $quantity;
            $totalRevenue += $totalAmount;

            $bookingDetails[] = [
                'id' => $b['id'],
                'tourId' => $b['tourId'] ?? '',
                'email' => $b['email'] ?? '',
                'phone' => $b['phone'] ?? '',
                'quantity' => $quantity,
                'departureDate' => $b['departureDate'] ?? '',
                'status' => $b['status'] ?? '',
                'travelType' => $b['travelType'] ?? '',
                'type' => $b['type'] ?? '',
                'total_amount' => $totalAmount,
                'registrant_count' => (int)($b['registrant_count'] ?? 0),
                'registrants' => $registrants
            ];
        }

        // prepare tours and staffs so booking form partial can be shown on the same page
        $tours = [];
        $tourObjects = $this->tourModel->getAll();
        foreach ($tourObjects as $t) {
            $row = is_array($t) ? $t : (array)$t;
            $id = $row['id'] ?? '';
            $tours[] = [
                'id' => $id,
                'name' => $row['name'] ?? '',
                'tour_code' => $row['tour_code'] ?? '',
                'main_destination' => $row['main_destination'] ?? '',
                'images' => $this->tourModel->getImages($id),
                'price' => ['adult' => (int)($row['adult_price'] ?? 0), 'child' => (int)($row['child_price'] ?? 0)],
                'schedule' => $this->tourModel->getSchedule($id),
                'departures' => $this->tourModel->getDeparturesByTourId($id)
            ];
        }

        $staffs = $this->model->getStaffs();

        include __DIR__ . '/../views/admin/booking-list.php';
    }
}
