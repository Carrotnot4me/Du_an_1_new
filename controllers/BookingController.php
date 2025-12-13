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
