<?php
session_start();

require_once './commons/env.php';
require_once './commons/function.php';

/* -------------------------
   LOAD CONTROLLERS
---------------------------*/
require_once './controllers/LoginController.php';
require_once './controllers/RegisterController.php';

require_once './controllers/DashboardController.php';
require_once './controllers/TourController.php';
require_once './controllers/RevenueReportController.php';
require_once './controllers/CheckinController.php';

require_once './controllers/BookingController.php';
require_once './controllers/CustomerController.php';
require_once './controllers/NoteController.php';

require_once './controllers/GuideController.php';
require_once './controllers/GuideLogController.php';
require_once './controllers/GuideSpecialController.php';

require_once './controllers/ScheduleController.php';
require_once './controllers/GuideScheduleController.php';


/* -------------------------
   KHỞI TẠO CONTROLLER
---------------------------*/
$loginController         = new LoginController();
$registerController      = new RegisterController();

$dashboardController     = new DashboardController();
$tourController          = new TourController();
$revenueReportController = new RevenueReportController();
$checkinController       = new CheckinController();

$bookingController       = new BookingController();
$customerController      = new CustomerController();
$noteController          = new NoteController();

$guideController         = new GuideController();
$guideLogController      = new GuideLogController();
$guideSpecialController  = new GuideSpecialController();

$scheduleController      = new ScheduleController();
$guideScheduleController = new GuideScheduleController();


/* -------------------------
    HÀM CHECK LOGIN ADMIN
---------------------------*/
function authGuard()
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?action=login');
        exit;
    }
}


// Lấy action (router)
$action = $_GET['action'] ?? 'dashboard';


/* -------------------------
    ROUTER CHÍNH
---------------------------*/
switch ($action) {

    /* ===== LOGIN, REGISTER ===== */
    case 'login':
        $loginController->index();
        break;

    case 'login_submit':
        $loginController->login();
        break;

    case 'logout':
        $loginController->logout();
        break;

    case 'register_admin':
        $registerController->index();
        break;

    case 'register_admin_submit':
        $registerController->registerAdmin();
        break;


    /* ===== DASHBOARD ===== */
    case 'dashboard':
    case '':
        authGuard();
        $dashboardController->index();
        break;

    case 'getDashboardStats':
        authGuard();
        $dashboardController->getDashboardStats();
        break;


    /* ===== TOUR ===== */
    case 'tour-list':
        authGuard();
        $tourController->list();
        break;

    case 'getTours':
        authGuard();
        $tourController->getTours();
        break;

    case 'addTour':
        authGuard();
        $tourController->add();
        break;

    case 'deleteTour':
        authGuard();
        $tourController->delete();
        break;


    /* ===== BOOKING ===== */
    case 'booking-list':
        authGuard();
        $bookingController->list();
        break;

    case 'getBookings':
        authGuard();
        $bookingController->getBookings();
        break;

    case 'updateBookingStatus':
        authGuard();
        $bookingController->updateStatus();
        break;

    case 'getStatuses':
        authGuard();
        $bookingController->getStatuses();
        break;


    /* ===== CUSTOMER ===== */
    case 'customer-list':
        authGuard();
        $customerController->list();
        break;

    case 'getCustomers':
        authGuard();
        $customerController->getCustomers();
        break;

    case 'getCustomerDetail':
        authGuard();
        $customerController->getCustomerDetail();
        break;


    /* ===== SPECIAL NOTES ===== */
    case 'special-notes':
        authGuard();
        $noteController->list();
        break;

    case 'getNotes':
        authGuard();
        $noteController->getNotes();
        break;

    case 'getNotesByCustomer':
        authGuard();
        $noteController->getNotesByCustomer();
        break;

    case 'addNote':
        authGuard();
        $noteController->add();
        break;

    case 'updateNote':
        authGuard();
        $noteController->update();
        break;

    case 'deleteNote':
        authGuard();
        $noteController->delete();
        break;

    case 'getNoteTypes':
        authGuard();
        $noteController->getTypes();
        break;


    /* ===== GUIDE SPECIAL REQUEST ===== */
    case 'guide-special':
        authGuard();
        $guideSpecialController->list();
        break;

    case 'getGuideSpecials':
        authGuard();
        $guideSpecialController->getAll();
        break;

    case 'getGuideSpecial':
        authGuard();
        $guideSpecialController->getById();
        break;

    case 'addGuideSpecial':
        authGuard();
        $guideSpecialController->add();
        break;

    case 'updateGuideSpecial':
        authGuard();
        $guideSpecialController->update();
        break;

    case 'deleteGuideSpecial':
        authGuard();
        $guideSpecialController->delete();
        break;

    case 'getGuideSpecialTypes':
        authGuard();
        $guideSpecialController->getTypes();
        break;

    case 'getGuideSpecialCustomers':
        authGuard();
        $guideSpecialController->getCustomersWithRequests();
        break;


    /* ===== REVENUE REPORT ===== */
    case 'revenue-report':
        authGuard();
        $revenueReportController->index();
        break;


    /* ===== GUIDE LOGS ===== */
    case 'guide-logs':
        authGuard();
        $guideLogController->list();
        break;

    case 'getGuideLogs':
        authGuard();
        $guideLogController->getAll();
        break;

    case 'getGuideLog':
        authGuard();
        $guideLogController->getById();
        break;

    case 'getGuideLogsByTour':
        authGuard();
        $guideLogController->getByTour();
        break;

    case 'addGuideLog':
        authGuard();
        $guideLogController->add();
        break;

    case 'updateGuideLog':
        authGuard();
        $guideLogController->update();
        break;

    case 'deleteGuideLog':
        authGuard();
        $guideLogController->delete();
        break;

    case 'getGuideLogTours':
        authGuard();
        $guideLogController->getTours();
        break;

    case 'getGuideLogGuides':
        authGuard();
        $guideLogController->getGuides();
        break;


    /* ===== GUIDE ===== */
    case 'guides':
        authGuard();
        $guideController->index();
        break;


    /* ===== LỊCH KHỞI HÀNH ===== */
    case 'schedule-assign':
        authGuard();
        $scheduleController->index();
        break;

    case 'schedule-save':
        authGuard();
        $scheduleController->save();
        break;


    /* ===== LỊCH THEO HƯỚNG DẪN VIÊN ===== */
    case 'guide-schedule':
        authGuard();
        $guideScheduleController->index();
        break;


    /* ===== CHECKIN ===== */
    case 'checkin':
        authGuard();
        $checkinController->handle();
        break;


    /* ===== DEFAULT ===== */
    default:
        $dashboardController->index();
        break;
}

