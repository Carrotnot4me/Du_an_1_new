<?php
session_start();
require_once './commons/env.php';
require_once './commons/function.php';
require_once './controllers/TourController.php';
require_once './controllers/DashboardController.php';

$action = $_GET['action'] ?? 'dashboard';

$tourController = new TourController();
$dashboardController = new DashboardController();

switch ($action) {
    // --- DASHBOARD ACTIONS ---
    case 'dashboard':
        $dashboardController->index();
        break;
    case 'getDashboardStats':
        $dashboardController->getDashboardStats();
        break;

    // --- TOUR ACTIONS ---
    case 'tour-list':
    case '': // action rỗng cũng dẫn đến danh sách tour hoặc dashboard
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

    // --- DEFAULT ACTION ---
    default:
        // Nếu không khớp với bất kỳ action nào, trở về trang dashboard
        $dashboardController->index(); 
        break;
}