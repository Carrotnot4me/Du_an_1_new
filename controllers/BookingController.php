<?php
require_once __DIR__ . '/../models/BookingModel.php';

class BookingController{

    private $model;

    public function __construct(){
        $this->model = new BookingModel();
    }

    public function add(){
    $tours      = $this->model->getTours();
    $departures = $this->model->getDepartures();
    $staffs     = $this->model->getStaffs();
    $drivers    = $this->model->getDrivers();

    // Dùng để hiển thị lịch trình khi chọn tour (Ajax sẽ gọi)
    $selectedTour = null;
    $schedule     = [];

    if (isset($_GET['tour_id']) && !empty($_GET['tour_id'])) {
        $selectedTour = $this->model->getTourDetail($_GET['tour_id']);
        $schedule     = $this->model->getTourSchedule($_GET['tour_id']);
    }

    extract([
        'tours'        => $tours,
        'departures'   => $departures,
        'staffs'       => $staffs,
        'drivers'      => $drivers,
        'selectedTour' => $selectedTour,
        'schedule'     => $schedule
    ]);

    require_once __DIR__ . '/../views/admin/BookingAdd.php';
}
   public function save(){
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?controller=booking&action=add");
        exit;
    }

    // Lấy dữ liệu cơ bản
    $tour_id        = $_POST['tour_id'] ?? null;
    $departure_id   = $_POST['departure_id'] ?? null;
    $date_start     = $_POST['date_start'] ?? null;
    $date_end       = $_POST['date_end'] ?? null;
    $meeting_point  = $_POST['meeting_point'] ?? null;
    $driver_id      = $_POST['driver_id'] ?? null;
    $staff_id       = $_POST['staff_id'] ?? null;
    $total_amount   = $_POST['total_amount'] ?? 0;
    $total_quantity = $_POST['total_quantity'] ?? 1;

    // ===== VALIDATION =====
    $errors = [];

    // 1. Kiểm tra tour bắt buộc
    if (empty($tour_id)) {
        $errors[] = "Vui lòng chọn tour.";
    }

    // 2. Kiểm tra ngày khởi hành (phải chọn ngày có sẵn HOẶC nhập ngày mới)
    if (empty($departure_id) && (empty($date_start) || empty($date_end))) {
        $errors[] = "Vui lòng chọn ngày khởi hành có sẵn hoặc nhập ngày bắt đầu và kết thúc.";
    }

    // 3. Nếu nhập ngày mới, kiểm tra định dạng và logic ngày
    if (!empty($date_start) && !empty($date_end)) {
        // Kiểm tra định dạng ngày (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_start)) {
            $errors[] = "Ngày bắt đầu không hợp lệ (định dạng: YYYY-MM-DD).";
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_end)) {
            $errors[] = "Ngày kết thúc không hợp lệ (định dạng: YYYY-MM-DD).";
        }
        
        // Kiểm tra ngày bắt đầu <= ngày kết thúc
        if (strtotime($date_start) > strtotime($date_end)) {
            $errors[] = "Ngày kết thúc phải >= ngày bắt đầu.";
        }

        // Kiểm tra ngày không ở quá khứ
        if (strtotime($date_start) < strtotime('today')) {
            $errors[] = "Ngày bắt đầu không được ở quá khứ.";
        }
    }

    // 4. Kiểm tra danh sách khách hàng
    if (empty($_POST['fullname'])) {
        $errors[] = "Vui lòng thêm ít nhất 1 khách hàng.";
    } else {
        $fullnames = $_POST['fullname'];
        $emails    = $_POST['email'] ?? [];
        $phones    = $_POST['phone'] ?? [];
        $types     = $_POST['type'] ?? [];

        // Kiểm tra mỗi khách
        foreach ($fullnames as $i => $name) {
            if (empty(trim($name))) {
                $errors[] = "Khách hàng thứ " . ($i + 1) . ": Họ và tên bắt buộc.";
            }
            // Kiểm tra email nếu có
            if (!empty($emails[$i] ?? '')) {
                if (!filter_var($emails[$i], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Khách hàng thứ " . ($i + 1) . ": Email không hợp lệ.";
                }
            }
            // Kiểm tra số điện thoại nếu có (chỉ chứa số và dấu - +)
            if (!empty($phones[$i] ?? '')) {
                if (!preg_match('/^[\d\s\-\+\(\)]{7,20}$/', $phones[$i])) {
                    $errors[] = "Khách hàng thứ " . ($i + 1) . ": Số điện thoại không hợp lệ.";
                }
            }
            // Kiểm tra loại khách
            $validTypes = ['Gia đình', 'Cá nhân', 'Bạn bè'];
            if (!in_array($types[$i] ?? '', $validTypes)) {
                $errors[] = "Khách hàng thứ " . ($i + 1) . ": Loại khách không hợp lệ.";
            }
        }

        // Kiểm tra tổng số khách > 0
        if ($total_quantity <= 0) {
            $errors[] = "Tổng số khách phải > 0.";
        }
    }

    // Nếu có lỗi, lưu vào session và redirect về form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?controller=booking&action=add");
        exit;
    }

    // ===== VALIDATION PASS - CONTINUE WITH SAVE =====

        // No debug file logging: persist directly to DB

    // Tạo mã booking tự động là số nguyên tăng dần (1,2,3...)
    $lastId = $this->model->getLastBookingId();
    $newId = (string)($lastId + 1);

    // Nếu người dùng nhập ngày bắt đầu/kết thúc mới, lưu vào bảng departures và sử dụng id mới
    if (empty($departure_id) && !empty($date_start) && !empty($date_end)) {
        $depData = [
            'tour_id' => $tour_id,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'meeting_point' => $meeting_point,
            'guide_id' => $staff_id,
            'driver_id' => $driver_id
        ];
        $departure_id = $this->model->insertDeparture($depData);
    }

    // Lưu booking chính
    // Use first customer's contact and type as booking-level info
    $bookingEmail = $_POST['email'][0] ?? null;
    $bookingPhone = $_POST['phone'][0] ?? null;
    $bookingTravelType = $_POST['type'][0] ?? null;  // Save first customer's type to bookings.travelType

    // Determine booking status based on departure date (Sắp đi / Đang đi / Đã kết thúc)
    $bookingStatus = null;
    $depStart = null; $depEnd = null;
    if (!empty($departure_id)) {
        $dep = $this->model->getDepartureById($departure_id);
        if ($dep) {
            $depStart = $dep['dateStart'] ?? null;
            $depEnd = $dep['dateEnd'] ?? null;
        }
    } else {
        // nếu không có departure_id nhưng user nhập date_start/date_end
        $depStart = $date_start ?: null;
        $depEnd = $date_end ?: null;
    }

    $today = date('Y-m-d');
    if ($depStart && $depEnd) {
        if ($today < $depStart) {
            $bookingStatus = 'Sắp đi';
        } elseif ($today >= $depStart && $today <= $depEnd) {
            $bookingStatus = 'Đang đi';
        } else {
            $bookingStatus = 'Đã kết thúc';
        }
    } else {
        // Nếu không có dates, đặt mặc định là 'Sắp đi'
        $bookingStatus = 'Sắp đi';
    }

    $bookingData = [
        'id'            => $newId,
        'tour_id'       => $tour_id,
        'status'        => $bookingStatus,
        'driver_id'     => $driver_id,
        'staff_id'      => $staff_id,
        'departure_id'  => $departure_id
    ];

    $bookingId = $this->model->insertBooking($bookingData);

    if ($bookingId === false) {
        $_SESSION['errors'] = (array)($_SESSION['errors'] ?? []);
        $_SESSION['errors'][] = "Lưu booking thất bại!";
        header("Location: index.php?controller=booking&action=add");
        exit;
    }

    // Lưu danh sách khách chi tiết (nếu có nhập)
    // Loop through fullname array and save all customers with matching email, phone, type indices
    if (!empty($_POST['fullname'])) {
        $fullnames = $_POST['fullname'];
        $emails    = $_POST['email'] ?? [];
        $phones    = $_POST['phone'] ?? [];
        $types     = $_POST['type'] ?? [];
        $qty_adults = $_POST['quantity_adult'] ?? [];
        $qty_childs = $_POST['quantity_child'] ?? [];
        
        // Ensure session errors is an array so we can append per-customer errors
        $_SESSION['errors'] = (array)($_SESSION['errors'] ?? []);

        foreach ($fullnames as $i => $name) {
            if (empty(trim($name))) continue;

            try {
                // Read raw values (may be missing if inputs were disabled)
                $rawAdult = isset($qty_adults[$i]) ? $qty_adults[$i] : null;
                $rawChild = isset($qty_childs[$i]) ? $qty_childs[$i] : null;

                $adultQty = ($rawAdult !== null && $rawAdult !== '') ? (int)$rawAdult : null;
                $childQty = ($rawChild !== null && $rawChild !== '') ? (int)$rawChild : null;
                $typeVal = $types[$i] ?? '';

                // Tính quantity theo yêu cầu và xử lý fallback nếu trường bị thiếu:
                if ($typeVal === 'Cá nhân') {
                    $personQty = 1;
                } elseif ($typeVal === 'Bạn bè') {
                    // Bạn bè dùng số người lớn; nếu không nhập, mặc định 1
                    $personQty = ($adultQty !== null && $adultQty > 0) ? $adultQty : 1;
                } elseif ($typeVal === 'Gia đình') {
                    // Gia đình: nếu adult missing -> mặc định 1; child missing -> 0
                    $a = ($adultQty !== null) ? $adultQty : 1;
                    $c = ($childQty !== null) ? $childQty : 0;
                    $personQty = $a + $c;
                } else {
                    // fallback: adult+child nếu có, ngược lại 1
                    $sum = (($adultQty !== null ? $adultQty : 0) + ($childQty !== null ? $childQty : 0));
                    $personQty = $sum > 0 ? $sum : 1;
                }

                $ok = $this->model->insertCustomer([
                    'booking_id' => $bookingId,
                    'name'       => $name,
                    'email'      => $emails[$i] ?? '',
                    'phone'      => $phones[$i] ?? '',
                    'type'       => $typeVal,
                    'quantity'   => (string)$personQty,
                    'status'     => 'Chờ xác nhận'
                ]);
                if (!$ok) {
                    $_SESSION['errors'][] = "Lưu khách hàng thứ " . ($i+1) . " thất bại.";
                }
            } catch (\Exception $e) {
                $_SESSION['errors'][] = "Lỗi khi lưu khách hàng thứ " . ($i+1) . ": " . $e->getMessage();
            }
        }
    }

    $_SESSION['success'] = "Đặt tour thành công! Mã booking: <strong>$newId</strong>";
    header("Location: index.php?controller=booking&action=list");
    exit;
}

    public function getSchedule(){
        header('Content-Type: application/json');
        
        if (!isset($_GET['tour_id'])) {
            echo json_encode([]);
            exit;
        }

        $tour_id = $_GET['tour_id'];
        $schedule = $this->model->getTourSchedule($tour_id);
        
        echo json_encode($schedule);
        exit;
    }

    public function list(){
        $bookings   = $this->model->getAll();
        // Also provide lookup data so the view can render selects
        $tours      = $this->model->getTours();
        $departures = $this->model->getDepartures();
        $staffs     = $this->model->getStaffs();
        $drivers    = $this->model->getDrivers();

        extract([
            'bookings'   => $bookings,
            'tours'      => $tours,
            'departures' => $departures,
            'staffs'     => $staffs,
            'drivers'    => $drivers
        ]);

        require_once __DIR__ . '/../views/admin/BookingView.php';
    }

    public function delete(){
        if(isset($_GET['id'])) $this->model->delete($_GET['id']);
        header("Location: index.php?controller=booking&action=list");
        exit;
    }
}