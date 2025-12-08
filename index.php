<?php
// index.php - FILE QUAN TRỌNG NHẤT CỦA DỰ ÁN (ROUTER)

// 1. BẮT BUỘC – chỉ require 1 lần
require_once __DIR__ . '/commons/env.php';
require_once __DIR__ . '/commons/function.php';

// 2. SESSION + AUTOLOADER THẦN THÁNH (giữ lại bản ngon nhất)
session_start();

spl_autoload_register(function ($className) {
    if (str_ends_with($className, 'Controller')) {
        $file = __DIR__ . '/controllers/' . $className . '.php';
        if (file_exists($file)) require_once $file;
    }
    if (str_ends_with($className, 'Model')) {
        $file = __DIR__ . '/models/' . $className . '.php';
        if (file_exists($file)) require_once $file;
    }
});

// Hỗ trợ PHP < 8.0
if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
        return $needle !== '' && substr($haystack, -strlen($needle)) === $needle;
    }
}

// 3. BẢO VỆ ADMIN
function authGuard() {
    if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?action=login');
        exit;
    }
}

// 4. KHỞI TẠO TẤT CẢ CONTROLLER (chỉ 1 lần)
require_once './controllers/LoginController.php';
require_once './controllers/RegisterController.php';
require_once './controllers/DashboardController.php';
require_once './controllers/TourController.php';
require_once './controllers/BookingController.php';
require_once './controllers/CustomerController.php';
require_once './controllers/NoteController.php';
require_once './controllers/GuideController.php';
require_once './controllers/GuideLogController.php';
require_once './controllers/GuideSpecialController.php';
require_once './controllers/ScheduleController.php';
require_once './controllers/GuideScheduleController.php';
require_once './controllers/RevenueReportController.php';
require_once './controllers/PaymentController.php';
require_once './controllers/CheckinController.php';

$loginController         = new LoginController();
$registerController      = new RegisterController();
$dashboardController     = new DashboardController();
$tourController          = new TourController();
$bookingController       = new BookingController();
$customerController      = new CustomerController();
$noteController          = new NoteController();
$guideController         = new GuideController();
$guideLogController      = new GuideLogController();
$guideSpecialController  = new GuideSpecialController();
$scheduleController      = new ScheduleController();
$guideScheduleController = new GuideScheduleController();
$revenueReportController = new RevenueReportController();
$paymentController       = new PaymentController();
$checkinController       = new CheckinController();

// 5. LẤY ACTION
$action = $_GET['action'] ?? 'dashboard';

// 6. CÁC TRANG CÔNG KHAI (KHÔNG CẦN LOGIN)
$publicActions = ['login', 'login_submit', 'logout', 'register_admin', 'register_admin_submit'];

if (in_array($action, $publicActions)) {
    switch ($action) {
        case 'login':                  $loginController->index(); exit;
        case 'login_submit':           $loginController->login(); exit;
        case 'logout':                 $loginController->logout(); exit;
        case 'register_admin':         $registerController->index(); exit;
        case 'register_admin_submit':  $registerController->registerAdmin(); exit;
    }
}

// 7. TỪ ĐÂY TRỞ ĐI PHẢI LÀ ADMIN
authGuard();

// 8. ROUTING CHÍNH – ĐÃ GỘP TỪ CẢ NHÓM (chỉ 1 switch duy nhất)
switch ($action) {
    // Dashboard
    case 'dashboard':
    case '':
        $dashboardController->index(); break;
    case 'getDashboardStats':
        $dashboardController->getDashboardStats(); break;

    // Tour
    case 'tour-list':       $tourController->list(); break;
    case 'getTours':        $tourController->getTours(); break;
    case 'addTour':         $tourController->add(); break;
    case 'deleteTour':      $tourController->delete(); break;
    case 'editTour':        $tourController->edit(); break;
    case 'updateTour':      $tourController->update(); break;
    case 'tourDetail':      $tourController->detail(); break;

    // Booking
    case 'booking-list':        $bookingController->list(); break;
    case 'getBookings':         $bookingController->getBookings(); break;
    case 'updateBookingStatus': $bookingController->updateStatus(); break;
    case 'getStatuses':         $bookingController->getStatuses(); break;

    // Customer
    case 'customer-list':       $customerController->list(); break;
    case 'getCustomers':        $customerController->getCustomers(); break;
    case 'getCustomerDetail':   $customerController->getCustomerDetail(); break;

    // Special Notes
    case 'special-notes':           $noteController->list(); break;
    case 'getNotes':                $noteController->getNotes(); break;
    case 'getNotesByCustomer':      $noteController->getNotesByCustomer(); break;
    case 'addNote':                 $noteController->add(); break;
    case 'updateNote':              $noteController->update(); break;
    case 'deleteNote':              $noteController->delete(); break;
    case 'getNoteTypes':            $noteController->getTypes(); break;

    // Guide Special Request
    case 'guide-special':               $guideSpecialController->list(); break;
    case 'getGuideSpecials':            $guideSpecialController->getAll(); break;
    case 'addGuideSpecial':             $guideSpecialController->add(); break;
    case 'updateGuideSpecial':          $guideSpecialController->update(); break;
    case 'deleteGuideSpecial':          $guideSpecialController->delete(); break;

    // Guide Log
    case 'guide-logs':          $guideLogController->list(); break;
    case 'getGuideLogs':        $guideLogController->getAll(); break;
    case 'addGuideLog':         $guideLogController->add(); break;
    case 'updateGuideLog':      $guideLogController->update(); break;
    case 'deleteGuideLog':      $guideLogController->delete(); break;

    // Revenue & Payment
    case 'revenue-report':      $revenueReportController->index(); break;
    case 'add-payment-form':    $paymentController->addPaymentForm(); break;
    case 'process-payment':     $paymentController->processPayment(); break;

    // Checkin & Schedule
    case 'checkin':             $checkinController->handle(); break;
    case 'schedule-assign':     $scheduleController->index(); break;
    case 'schedule-save':       $scheduleController->save(); break;
    case 'guide-schedule':      $guideScheduleController->index(); break;
    case 'guides':              $guideController->index(); break;

    // Mặc định
    default:
        $dashboardController->index(); break;
}