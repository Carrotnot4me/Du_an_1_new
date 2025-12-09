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
require_once './controllers/DashboardController.php';
require_once './controllers/RevenueReportController.php';
require_once './controllers/BookingController.php';
require_once './controllers/CustomerController.php';
require_once './controllers/NoteController.php';
require_once './controllers/GuideLogController.php';
require_once './controllers/GuideSpecialController.php';
require_once './controllers/SupplierController.php';

$action = $_GET['action'] ?? 'dashboard';

$tourController = new TourController();
$dashboardController = new DashboardController();
$revenueReportController = new RevenueReportController();
$bookingController = new BookingController();
$customerController = new CustomerController();
$noteController = new NoteController();
$guideLogController = new GuideLogController();
$guideSpecialController = new GuideSpecialController();
$supplierController = new SupplierController();

switch ($action) {
    // --- DASHBOARD ACTIONS ---
    case 'dashboard':
    case '':
        $dashboardController->index();
        break;
    case 'getDashboardStats':
        $dashboardController->getDashboardStats();
        break;

    // --- TOUR ACTIONS ---
    case 'tour-list':
        $tourController->list();
        break;
    case 'getTours':
        $tourController->getTours();
        break;
    case 'addTour':
        $tourController->add();
        break;
    case 'deleteTour':
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

    // --- REVENUE REPORT ACTIONS ---
    case 'revenue-report':
        $revenueReportController->index();
        break;

    // --- GUIDE LOG ACTIONS ---
    case 'guide-logs':
        $guideLogController->list();
        break;
    case 'getGuideLogs':
        $guideLogController->getAll();
        break;
    case 'getGuideLog':
        $guideLogController->getById();
        break;
    case 'getGuideLogsByTour':
        $guideLogController->getByTour();
        break;
    case 'addGuideLog':
        $guideLogController->add();
        break;
    case 'updateGuideLog':
        $guideLogController->update();
        break;
    case 'deleteGuideLog':
        $guideLogController->delete();
        break;
    case 'getGuideLogTours':
        $guideLogController->getTours();
        break;
    case 'getGuideLogGuides':
        $guideLogController->getGuides();
        break;

    // --- SUPPLIER ACTIONS ---
    case 'supplier-list':
        $supplierController->list();
        break;
    case 'getSuppliers':
        $supplierController->getSuppliers();
        break;
    case 'getSupplier':
        $supplierController->getSupplier();
        break;
    case 'addSupplier':
        $supplierController->add();
        break;
    case 'updateSupplier':
        $supplierController->update();
        break;
    case 'deleteSupplier':
        $supplierController->delete();
        break;
    case 'getSupplierServiceTypes':
        $supplierController->getServiceTypes();
        break;

    // --- DEFAULT ACTION ---
    default:
        $dashboardController->index(); 
        break;
}