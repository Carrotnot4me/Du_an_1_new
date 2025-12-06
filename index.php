<?php
session_start();

// Core
require_once __DIR__ . '/commons/env.php';
require_once __DIR__ . '/commons/function.php';

// AUTOLOADER THẦN THÁNH – HẾT ĐỎ VSCode + KHÔNG LỖI KHI THIẾU FILE
spl_autoload_register(function ($className) {
    if (str_ends_with($className, 'Controller')) {
        $file = __DIR__ . '/controllers/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
        // Không tồn tại → im lặng, không die → IDE hết đỏ
    }
    if (str_ends_with($className, 'Model')) {
        $file = __DIR__ . '/models/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Hỗ trợ PHP < 8.0
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return $needle !== '' && substr($haystack, -strlen($needle)) === $needle;
    }
}

// ----------------------------------------
// BẢO VỆ ADMIN
// ----------------------------------------
function authGuard()
{
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: index.php?action=login");
        exit;
    }
}

// ----------------------------------------
// LẤY ACTION
// ----------------------------------------
$action = $_GET['action'] ?? 'dashboard';

// ========================================
// 1. CÁC TRANG CÔNG KHAI (KHÔNG CẦN LOGIN)
// ========================================
$public = ['login', 'login_submit', 'logout', 'register_admin', 'register_admin_submit'];

if (in_array($action, $public)) {
    switch ($action) {
        case 'login':                   (new LoginController())->index(); exit;
        case 'login_submit':            (new LoginController())->login(); exit;
        case 'logout':                  (new LoginController())->logout(); exit;
        case 'register_admin':          (new RegisterController())->index(); exit;
        case 'register_admin_submit':   (new RegisterController())->registerAdmin(); exit;
    }
}

// ========================================
// 2. TỪ ĐÂY TRỞ ĐI PHẢI LÀ ADMIN
// ========================================
authGuard();  // Bảo vệ toàn bộ phần dưới

// ========================================
// 3. ROUTING CHÍNH – ĐÃ ĐƯỢC GỘP TỪ CẢ NHÓM
// ========================================
switch ($action) {

    // DASHBOARD
    case 'dashboard':
    case '':
        (new DashboardController())->index(); break;
    case 'getDashboardStats':
        (new DashboardController())->getDashboardStats(); break;

    // TOUR
    case 'tour-list':       (new TourController())->list(); break;
    case 'getTours':        (new TourController())->getTours(); break;
    case 'addTour':         (new TourController())->add(); break;
    case 'deleteTour':      (new TourController())->delete(); break;
    case 'editTour':        (new TourController())->edit(); break;
    case 'updateTour':      (new TourController())->update(); break;
    case 'tourDetail':      (new TourController())->detail(); break;

    // BOOKING
    case 'booking-list':        (new BookingController())->list(); break;
    case 'getBookings':         (new BookingController())->getBookings(); break;
    case 'updateBookingStatus': (new BookingController())->updateStatus(); break;
    case 'getStatuses':         (new BookingController())->getStatuses(); break;

    // CUSTOMER
    case 'customer-list':       (new CustomerController())->list(); break;
    case 'getCustomers':        (new CustomerController())->getCustomers(); break;
    case 'getCustomerDetail':   (new CustomerController())->getCustomerDetail(); break;

    // SPECIAL NOTES
    case 'special-notes':           (new NoteController())->list(); break;
    case 'getNotes':                (new NoteController())->getNotes(); break;
    case 'getNotesByCustomer':      (new NoteController())->getNotesByCustomer(); break;
    case 'addNote':                 (new NoteController())->add(); break;
    case 'updateNote':              (new NoteController())->update(); break;
    case 'deleteNote':              (new NoteController())->delete(); break;
    case 'getNoteTypes':            (new NoteController())->getTypes(); break;

    // GUIDE SPECIAL REQUEST
    case 'guide-special':               (new GuideSpecialController())->list(); break;
    case 'getGuideSpecials':            (new GuideSpecialController())->getAll(); break;
    case 'getGuideSpecial':             (new GuideSpecialController())->getById(); break;
    case 'addGuideSpecial':             (new GuideSpecialController())->add(); break;
    case 'updateGuideSpecial':          (new GuideSpecialController())->update(); break;
    case 'deleteGuideSpecial':          (new GuideSpecialController())->delete(); break;
    case 'getGuideSpecialTypes':        (new GuideSpecialController())->getTypes(); break;
    case 'getGuideSpecialCustomers':    (new GuideSpecialController())->getCustomersWithRequests(); break;

    // GUIDE LOG
    case 'guide-logs':          (new GuideLogController())->list(); break;
    case 'getGuideLogs':        (new GuideLogController())->getAll(); break;
    case 'getGuideLog':         (new GuideLogController())->getById(); break;
    case 'getGuideLogsByTour':  (new GuideLogController())->getByTour(); break;
    case 'addGuideLog':         (new GuideLogController())->add(); break;
    case 'updateGuideLog':      (new GuideLogController())->update(); break;
    case 'deleteGuideLog':      (new GuideLogController())->delete(); break;
    case 'getGuideLogTours':    (new GuideLogController())->getTours(); break;
    case 'getGuideLogGuides':   (new GuideLogController())->getGuides(); break;

    // REVENUE & PAYMENT
    case 'revenue-report':      (new RevenueReportController())->index(); break;
    case 'add-payment-form':    (new PaymentController())->addPaymentForm(); break;
    case 'process-payment':     (new PaymentController())->processPayment(); break;

    // CHECKIN & SCHEDULE
    case 'checkin':             (new CheckinController())->handle(); break;
    case 'schedule-assign':     (new ScheduleController())->index(); break;
    case 'schedule-save':       (new ScheduleController())->save(); break;
    case 'guide-schedule':      (new GuideScheduleController())->index(); break;
    case 'guides':              (new GuideController())->index(); break;

    // MẶC ĐỊNH
    default:
        (new DashboardController())->index(); break;
}