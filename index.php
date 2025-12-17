<?php
// index.php - main router

require_once __DIR__ . '/commons/env.php';
require_once __DIR__ . '/commons/function.php';

session_start();

require_once __DIR__ . '/controllers/AuthController.php';

require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/TourController.php';
require_once __DIR__ . '/controllers/RevenueReportController.php';
require_once __DIR__ . '/controllers/PaymentController.php';
require_once __DIR__ . '/controllers/CheckinController.php';

require_once __DIR__ . '/controllers/BookingController.php';
require_once __DIR__ . '/controllers/NoteController.php';

require_once __DIR__ . '/controllers/GuideController.php';
require_once __DIR__ . '/controllers/GuideLogController.php';
require_once __DIR__ . '/controllers/GuideSpecialController.php';

require_once __DIR__ . '/controllers/ScheduleController.php';
require_once __DIR__ . '/controllers/GuideScheduleController.php';
require_once __DIR__ . '/controllers/SupplierController.php';

$authController          = new AuthController();

$dashboardController     = new DashboardController();
$tourController          = new TourController();
$revenueReportController = new RevenueReportController();
$paymentController       = new PaymentController();
$checkinController       = new CheckinController();

$bookingController       = new BookingController();
$noteController          = new NoteController();

$guideController         = new GuideController();
$guideLogController      = new GuideLogController();
$guideSpecialController  = new GuideSpecialController();

$scheduleController      = new ScheduleController();
$guideScheduleController = new GuideScheduleController();
$supplierController      = new SupplierController();

function authGuard()
{
    if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
        header('Location: index.php?action=login');
        exit;
    }
}

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    // Public / auth routes
    case 'login':
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;
    case 'showLogin':
        $authController->showLogin();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'register':
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;
    case 'showRegister':
        $authController->showRegister();
        break;
    case 'profile':
        authGuard();
        $authController->profile();
        break;

    // Dashboard (admin)
    case 'dashboard':
    case '':
        authGuard();
        $dashboardController->index();
        break;
    case 'getDashboardStats':
        authGuard();
        $dashboardController->getDashboardStats();
        break;

    // Tours
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
    // case 'tourDetail':
    //     authGuard();
    //     $tourController->detail();
    //     break;
    case 'editTour':
        authGuard();
        $tourController->edit();
        break;
    case 'updateTour':
        authGuard();
        $tourController->update();
        break;

    // Bookings
    case 'booking-list':
        authGuard();
        $bookingController->list();
        break;
    case 'booking-add':
        authGuard();
        $bookingController->add();
        break;
    case 'booking-save':
        authGuard();
        $bookingController->save();
        break;
    case 'getSchedule':
        authGuard();
        $bookingController->getSchedule();
        break;
    case 'booking-detail':
        authGuard();
        $bookingController->detail();
        break;
    case 'add-registrant':
        authGuard();
        $bookingController->addRegistrant();
        break;
    case 'registrant-payment':
        authGuard();
        $bookingController->registrantPayment();
        break;
    case 'deleteBooking':
        authGuard();
        $bookingController->delete();
        break;
    // case 'getBookings':
    //     authGuard();
    //     $bookingController->getBookings();
    //     break;
    // case 'updateBookingStatus':
    //     authGuard();
    //     $bookingController->updateStatus();
    //     break;
    // case 'getStatuses':
    //     authGuard();
    //     $bookingController->getStatuses();
    //     break;

    // Customers
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

    // Suppliers
    case 'supplier-list':
        authGuard();
        $supplierController->list();
        break;
    case 'getSuppliers':
        authGuard();
        $supplierController->getSuppliers();
        break;
    case 'getSupplier':
        authGuard();
        $supplierController->getSupplier();
        break;
    case 'addSupplier':
        authGuard();
        $supplierController->add();
        break;
    case 'updateSupplier':
        authGuard();
        $supplierController->update();
        break;
    case 'deleteSupplier':
        authGuard();
        $supplierController->delete();
        break;
    case 'getSupplierServiceTypes':
        authGuard();
        $supplierController->getServiceTypes();
        break;

    // Notes
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

    // Guide special
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

    // Revenue / payments
    case 'revenue-report':
        authGuard();
        $revenueReportController->index();
        break;
    case 'update-payment-status':
        authGuard();
        $revenueReportController->updatePaymentStatus();
        break;

    // Guide logs
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

    // Guides
    case 'guides':
        authGuard();
        $guideController->index();
        break;

    // Schedule
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

    // Checkin
    case 'checkin':
        authGuard();
        $checkinController->handle();
        break;

    // Payments
    case 'add-payment-form':
        authGuard();
        $paymentController->addPaymentForm();
        break;
    case 'process-payment':
        authGuard();
        $paymentController->processPayment();
        break;

    default:
        authGuard();
        $dashboardController->index();
        break;
}