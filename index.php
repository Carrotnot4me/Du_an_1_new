<?php
// index.php - FILE QUAN TRỌNG NHẤT CỦA DỰ ÁN (ROUTER)

// 1. BẮT BUỘC – chỉ require 1 lần
require_once __DIR__ . '/commons/env.php';
require_once __DIR__ . '/commons/function.php';

// 2. SESSION + AUTOLOADER THẦN THÁNH (giữ lại bản ngon nhất)
session_start();

require_once './commons/env.php';
require_once './commons/function.php';

require_once './controllers/LoginController.php';
require_once './controllers/RegisterController.php';

require_once './controllers/DashboardController.php';
require_once './controllers/TourController.php';
require_once './controllers/RevenueReportController.php';
require_once './controllers/PaymentController.php';
require_once './controllers/CheckinController.php';

require_once './controllers/BookingController.php';
require_once './controllers/CustomerController.php';
require_once './controllers/NoteController.php';

require_once './controllers/GuideController.php';
require_once './controllers/GuideLogController.php';
require_once './controllers/GuideSpecialController.php';

require_once './controllers/ScheduleController.php';
require_once './controllers/GuideScheduleController.php';


$loginController         = new LoginController();
$registerController      = new RegisterController();

$dashboardController     = new DashboardController();
$tourController          = new TourController();
$revenueReportController = new RevenueReportController();
$paymentController       = new PaymentController();
$checkinController       = new CheckinController();

$bookingController       = new BookingController();
$customerController      = new CustomerController();
$noteController          = new NoteController();

$guideController         = new GuideController();
$guideLogController      = new GuideLogController();
$guideSpecialController  = new GuideSpecialController();

$scheduleController      = new ScheduleController();
$guideScheduleController = new GuideScheduleController();


function authGuard()
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?action=login');
        exit;
    }
}


$action = $_GET['action'] ?? 'dashboard';


switch ($action) {

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


    case 'dashboard':
    case '':
        authGuard();
        $dashboardController->index();
        break;

    case 'getDashboardStats':
        authGuard();
        $dashboardController->getDashboardStats();
        break;


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
    case 'tourDetail':
        $tourController->detail();
        break;
    case 'editTour':
        $tourController->edit();
        break;
    case 'updateTour':
        $tourController->update();
        break;

    // --- BOOKING ACTIONS ---
    case 'booking-list':
        $bookingController->list();
        break;
    case 'getBookings':
        $bookingController->getBookings();
        break;
    case 'updateBookingStatus':
        $bookingController->updateStatus();
        break;
    case 'getStatuses':
        $bookingController->getStatuses();
        break;

    // --- CUSTOMER ACTIONS ---
    case 'customer-list':
        $customerController->list();
        break;
    case 'getCustomers':
        $customerController->getCustomers();
        break;
    case 'getCustomerDetail':
        $customerController->getCustomerDetail();
        break;

    // --- NOTE ACTIONS ---
    case 'special-notes':
        $noteController->list();
        break;
    case 'getNotes':
        $noteController->getNotes();
        break;
    case 'getNotesByCustomer':
        $noteController->getNotesByCustomer();
        break;
    case 'addNote':
        $noteController->add();
        break;
    case 'updateNote':
        $noteController->update();
        break;
    case 'deleteNote':
        $noteController->delete();
        break;
    case 'getNoteTypes':
        $noteController->getTypes();
        break;

    // --- GUIDE SPECIAL (YÊU CẦU ĐẶC BIỆT CỦA KHÁCH) ---
    case 'guide-special':
        $guideSpecialController->list();
        break;
    case 'getGuideSpecials':
        $guideSpecialController->getAll();
        break;
    case 'getGuideSpecial':
        $guideSpecialController->getById();
        break;
    case 'addGuideSpecial':
        $guideSpecialController->add();
        break;
    case 'updateGuideSpecial':
        $guideSpecialController->update();
        break;
    case 'deleteGuideSpecial':
        $guideSpecialController->delete();
        break;
    case 'getGuideSpecialTypes':
        $guideSpecialController->getTypes();
        break;
    case 'getGuideSpecialCustomers':
        $guideSpecialController->getCustomersWithRequests();
        break;


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


    case 'revenue-report':
        authGuard();
        $revenueReportController->index();
        break;

    case 'update-payment-status':
        authGuard();
        $revenueReportController->updatePaymentStatus();
        break;


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


    case 'guides':
        authGuard();
        $guideController->index();
        break;


    case 'schedule-assign':
        authGuard();
        $scheduleController->index();
        break;

    case 'schedule-save':
        authGuard();
        $scheduleController->save();
        break;


    case 'guide-schedule':
        authGuard();
        $guideScheduleController->index();
        break;


    case 'checkin':
        authGuard();
        $checkinController->handle();
        break;

    case 'add-payment-form':
        authGuard();
        $paymentController->addPaymentForm();
        break;

    case 'process-payment':
        authGuard();
        $paymentController->processPayment();
        break;

    default:
        $dashboardController->index();
        break;
}