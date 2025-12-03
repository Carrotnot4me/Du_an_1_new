<?php
session_start();

require_once './commons/env.php';
require_once './commons/function.php';

require_once './controllers/DashboardController.php';
require_once './controllers/TourController.php';
require_once './controllers/RevenueReportController.php';
require_once './controllers/LoginController.php';
require_once './controllers/RegisterController.php';
require_once './controllers/CheckinController.php';

$dashboardController      = new DashboardController();
$tourController           = new TourController();
$revenueReportController  = new RevenueReportController();
$loginController          = new LoginController();
$registerController       = new RegisterController();
$checkinController        = new CheckinController();

function authGuard() {
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

    case 'revenue-report':
        authGuard();
        $revenueReportController->index();
        break;
        
    case 'checkin':
        authGuard();
        $checkinController->handle();
        break;

    default:
        header('Location: index.php?action=dashboard');
        exit;
}