<?php
require_once __DIR__ . '/../models/BookingModel.php';

class BookingController{

    private $model;

    public function __construct(){
        $this->model = new BookingModel();
    }

    /**
     * Hiển thị form thêm booking
     */
    public function add(){
        $tours      = $this->model->getTours();
        $departures = $this->model->getDepartures();
        $staffs     = $this->model->getStaffs();
        $drivers    = $this->model->getDrivers();

        // Dùng cho ajax lịch trình
        $selectedTour = null;
        $schedule     = [];

        if (!empty($_GET['tour_id'])) {
            $selectedTour = $this->model->getTourDetail($_GET['tour_id']);
            $schedule     = $this->model->getTourSchedule($_GET['tour_id']);
        }

        extract(compact('tours','departures','staffs','drivers','selectedTour','schedule'));
        require_once __DIR__ . '/../views/admin/Booking-add.php';
    }

    /**
     * Lưu booking + khách hàng
     */
    public function save(){
        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Location: index.php?action=booking-add');
            exit;
        }

        $errors = [];

        $tour_id       = $_POST['tour_id'] ?? null;
        $departure_id  = $_POST['departure_id'] ?? null;
        $date_start    = $_POST['date_start'] ?? null;
        $date_end      = $_POST['date_end'] ?? null;
        $meeting_point = $_POST['meeting_point'] ?? null;
        $driver_id     = $_POST['driver_id'] ?? null;
        $staff_id      = $_POST['staff_id'] ?? null;

        // ===== VALIDATION =====
        if (empty($tour_id)) {
            $errors[] = 'Vui lòng chọn tour.';
        }

        if (empty($departure_id) && (empty($date_start) || empty($date_end))) {
            $errors[] = 'Vui lòng chọn hoặc nhập ngày khởi hành.';
        }

        if (!empty($date_start) && !empty($date_end)) {
            if (strtotime($date_start) > strtotime($date_end)) {
                $errors[] = 'Ngày kết thúc phải sau ngày bắt đầu.';
            }
            if (strtotime($date_start) < strtotime('today')) {
                $errors[] = 'Ngày bắt đầu không được ở quá khứ.';
            }
        }

        if (empty($_POST['fullname'])) {
            $errors[] = 'Vui lòng nhập ít nhất 1 khách.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?action=booking-add');
            exit;
        }

        // ===== SAVE =====
        $newId = (string)($this->model->getLastBookingId() + 1);

        // tạo departure mới nếu cần
        if (empty($departure_id)) {
            $departure_id = $this->model->insertDeparture([
                'tour_id'       => $tour_id,
                'date_start'    => $date_start,
                'date_end'      => $date_end,
                'meeting_point' => $meeting_point,
                'guide_id'      => $staff_id,
                'driver_id'     => $driver_id
            ]);
        }

        // xác định trạng thái booking
        $dep = $this->model->getDepartureById($departure_id);
        $today = date('Y-m-d');
        if ($today < $dep['dateStart']) {
            $status = 'Sắp đi';
        } elseif ($today <= $dep['dateEnd']) {
            $status = 'Đang đi';
        } else {
            $status = 'Đã kết thúc';
        }

        $bookingId = $this->model->insertBooking([
            'id'           => $newId,
            'tour_id'      => $tour_id,
            'status'       => $status,
            'driver_id'    => $driver_id,
            'staff_id'     => $staff_id,
            'departure_id' => $departure_id
        ]);

        if (!$bookingId) {
            $_SESSION['errors'][] = 'Lưu booking thất bại';
            header('Location: index.php?action=booking-add');
            exit;
        }

        // lưu khách
        foreach ($_POST['fullname'] as $i => $name) {
            if (empty(trim($name))) continue;

            $type = $_POST['type'][$i] ?? 'Cá nhân';
            $adult = (int)($_POST['quantity_adult'][$i] ?? 1);
            $child = (int)($_POST['quantity_child'][$i] ?? 0);

            $qty = $type === 'Gia đình' ? ($adult + $child) : max(1, $adult);

            $this->model->insertCustomer([
                'booking_id' => $bookingId,
                'name'       => $name,
                'email'      => $_POST['email'][$i] ?? '',
                'phone'      => $_POST['phone'][$i] ?? '',
                'type'       => $type,
                'quantity'   => $qty,
                'status'     => 'Chờ xác nhận'
            ]);

            // persist nested customers into customers table if provided (JSON per-registrant)
            $registrantId = $this->model->getLastRegistrantId();
            if (!empty($_POST['customers'][$i])) {
                require_once __DIR__ . '/../models/CustomerModel.php';
                $cm = new CustomerModel();
                $raw = $_POST['customers'][$i];
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $c) {
                        $cn = trim($c['name'] ?? '');
                        if ($cn === '') continue;
                        $cd = !empty($c['date']) ? $c['date'] : null;
                        $cg = isset($c['gender']) ? $c['gender'] : null;
                        $note = isset($c['note']) ? $c['note'] : null;
                        $cm->add($registrantId, $cn, $cd, $cg, $note);
                    }
                }
            }
        }

        $_SESSION['success'] = "Đặt tour thành công – Mã booking: <b>$newId</b>";
        header('Location: index.php?action=booking-list');
        exit;
    }

    /**
     * Ajax lấy lịch trình
     */
    public function getSchedule(){
        header('Content-Type: application/json');
        echo json_encode($this->model->getTourSchedule($_GET['tour_id'] ?? 0));
        exit;
    }

    /**
     * Danh sách booking / theo tour
     */
    public function list(){
        $departureId = $_GET['departure_id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;

        // If a departure_id is provided, show bookings for that departure.
        if ($departureId) {
            $bookings = $this->model->getBookingsByDeparture($departureId);
            require_once __DIR__ . '/../views/admin/BookingView.php';
            return;
        }

        // If a tour_id is provided (legacy), show bookings by tour
        if ($tourId) {
            $bookings = $this->model->getBookingsByTour($tourId);
            require_once __DIR__ . '/../views/admin/BookingView.php';
            return;
        }

        // Default: show aggregated summary (now grouped by departure)
        $tours = $this->model->getTourSummaries();
        $bookingsCount = $this->model->getTotalBookingsCount();
        require_once __DIR__ . '/../views/admin/booking-list.php';
    }

    /**
     * Booking detail - show one booking and its registrants
     */
    public function detail(){
        $bookingId = $_GET['booking_id'] ?? null;
        $departureId = $_GET['departure_id'] ?? null;
        $tourId = $_GET['tour_id'] ?? null;

        // If booking_id not provided, try to resolve from departure_id or tour_id
        if (!$bookingId) {
            if ($departureId) {
                $bookings = $this->model->getBookingsByDeparture($departureId);
                if (!empty($bookings)) {
                    $bookingId = $bookings[0]['id'];
                }
            } elseif ($tourId) {
                $bookings = $this->model->getBookingsByTour($tourId);
                if (!empty($bookings)) {
                    $bookingId = $bookings[0]['id'];
                }
            }
        }

        if (!$bookingId) {
            header('Location: index.php?action=booking-list');
            exit;
        }

        $booking = $this->model->getBookingById($bookingId);
        if (!$booking) {
            header('Location: index.php?action=booking-list');
            exit;
        }
        // registrants + paid amounts
        $registrants = $this->model->getRegistrantsByBooking($bookingId);
        foreach ($registrants as &$r) {
            $r['paid'] = $this->model->getPaidByRegistrant($r['id']);
        }
        unset($r);

        // load tour details, image and schedules for richer view
        $tourDetail = null;
        $tourImage = null;
        $tourSchedules = [];
        if (!empty($booking['tourId'])) {
            $tourDetail = $this->model->getTourDetail($booking['tourId']);
            $tourImage = $this->model->getTourImage($booking['tourId']);
            $tourSchedules = $this->model->getTourSchedule($booking['tourId']);
        }

        $paymentsTotal = $this->model->getPaymentsTotalByBooking($bookingId);
        require_once __DIR__ . '/../views/admin/booking-detail.php';
    }

    /**
     * Add a registrant (customer) to a booking
     */
    public function addRegistrant(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=booking-list');
            exit;
        }

        $bookingId = $_POST['booking_id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $adult = (int)($_POST['adult_count'] ?? 0);
        $child = (int)($_POST['child_count'] ?? 0);
        $type = $_POST['type'] ?? null;

        // compute quantity: adults + children, fallback to 1
        $quantity = max(1, $adult + $child);
        if (!$type) {
            $type = ($child > 0) ? 'Gia đình' : 'Cá nhân';
        }

        if (empty($bookingId) || empty($name)) {
            $_SESSION['errors'][] = 'Thiếu thông tin khách hàng';
            header("Location: index.php?action=booking-detail&booking_id=".urlencode($bookingId));
            exit;
        }


        $this->model->insertCustomer([
            'booking_id' => $bookingId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'type' => $type,
            'quantity' => $quantity,
            'adult_count' => $adult,
            'child_count' => $child,
            'status' => 'Chờ xác nhận'
        ]);

        // get inserted registrant id (pattern used elsewhere in the app)
        $registrantId = $this->model->getLastRegistrantId();

        // Persist nested customers if provided
        require_once __DIR__ . '/../models/CustomerModel.php';
        $cm = new CustomerModel();

        // Support JSON payload: 'customers' => JSON string [{name,date,gender},...]
        if (!empty($_POST['customers'])) {
            $raw = $_POST['customers'];
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                foreach ($decoded as $c) {
                    $cn = trim($c['name'] ?? '');
                    if ($cn === '') continue;
                    $cd = !empty($c['date']) ? $c['date'] : null;
                    $cg = isset($c['gender']) ? $c['gender'] : null;
                    $cm->add($registrantId, $cn, $cd, $cg);
                }
            }
        }

        // Or support flat arrays: customer_name[], customer_date[], customer_gender[]
        elseif (!empty($_POST['customer_name']) && is_array($_POST['customer_name'])) {
            $names = $_POST['customer_name'];
            $dates = $_POST['customer_date'] ?? [];
            $genders = $_POST['customer_gender'] ?? [];
            foreach ($names as $i => $cn) {
                $cn = trim($cn);
                if ($cn === '') continue;
                $cd = $dates[$i] ?? null;
                $cg = $genders[$i] ?? null;
                $cm->add($registrantId, $cn, $cd, $cg);
            }
        }

        header("Location: index.php?action=booking-detail&booking_id=".urlencode($bookingId));
        exit;
    }

    /**
     * Process a payment for a registrant: create payment record and update registrant status
     */
    public function registrantPayment(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=booking-list');
            exit;
        }

        $registrantId = $_POST['registrant_id'] ?? null;
        $amount = (float)($_POST['amount'] ?? 0);
        $method = trim($_POST['method'] ?? '');

        if (empty($registrantId) || $amount <= 0) {
            $_SESSION['errors'][] = 'Thông tin thanh toán không hợp lệ';
            header('Location: index.php?action=booking-list');
            exit;
        }

        // load registrant and booking
        $reg = $this->model->getRegistrantById($registrantId);
        if (!$reg) {
            $_SESSION['errors'][] = 'Không tìm thấy khách hàng';
            header('Location: index.php?action=booking-list');
            exit;
        }

        $booking = $this->model->getBookingById($reg['booking_id']);
        if (!$booking) {
            $_SESSION['errors'][] = 'Không tìm thấy booking';
            header('Location: index.php?action=booking-list');
            exit;
        }

        // compute expected deposit: quantity * adult_price * 0.3 (fallback 0)
        $qty = (int)($reg['quantity'] ?? 1);
        $adultPrice = (int)($booking['adult_price'] ?? 0);
        $expectedDeposit = round($qty * $adultPrice * 0.3);

        // insert payment via RevenueReportModel to payments table (legacy)
        require_once __DIR__ . '/../models/RevenueReportModel.php';
        $rev = new RevenueReportModel();
        $ok = $rev->addPayment($booking['id'], (int)$amount, $method ?: 'Tiền mặt', 'Hoàn thành', NULL);

        // Also persist into payment_history for per-registrant tracking
        require_once __DIR__ . '/../models/PaymentHistoryModel.php';
        $ph = new PaymentHistoryModel();
        $ph->add($registrantId, $booking['id'], (float)$amount, $method ?: 'Tiền mặt', null);

        // determine total paid by registrant and set status accordingly
        $paidTotal = $this->model->getPaidByRegistrant($registrantId);

        // compute registrant total amount (consider adult/child if present)
        $qty = (int)($reg['quantity'] ?? 1);
        $adultPrice = (int)($booking['adult_price'] ?? 0);
        $childPrice = (int)($booking['child_price'] ?? 0);
        $regAdult = isset($reg['adult_count']) ? (int)$reg['adult_count'] : null;
        $regChild = isset($reg['child_count']) ? (int)$reg['child_count'] : null;
        if ($regAdult !== null || $regChild !== null) {
            $regAdult = $regAdult ?? 0; $regChild = $regChild ?? 0;
            $totalExpected = $regAdult * $adultPrice + $regChild * $childPrice;
            $qty = $regAdult + $regChild;
        } else {
            $totalExpected = $qty * $adultPrice;
        }

        $expectedDeposit = round($totalExpected * 0.3);

        if ($paidTotal >= $totalExpected && $totalExpected > 0) {
            $newStatus = 'Đã hoàn thành';
        } elseif ($paidTotal >= $expectedDeposit && $expectedDeposit > 0) {
            $newStatus = 'Đã cọc';
        } elseif ($paidTotal > 0) {
            $newStatus = 'Đang xử lý';
        } else {
            $newStatus = 'Chờ xác nhận';
        }

        $this->model->updateRegistrantStatus($registrantId, $newStatus);

        // redirect back to booking detail
        header('Location: index.php?action=booking-detail&booking_id=' . urlencode($booking['id']));
        exit;
    }

    /**
     * Xóa booking
     */
    public function delete(){
        $id   = $_REQUEST['id'] ?? null;
        $mode = $_REQUEST['mode'] ?? null;

        if ($id) {
            if ($mode === 'by_tour') {
                $this->model->deleteByTour($id);
            } elseif ($mode === 'by_departure') {
                $this->model->deleteByDeparture($id);
            } else {
                $this->model->delete($id);
            }
        }

        header('Location: index.php?action=booking-list');
        exit;
    }
}
