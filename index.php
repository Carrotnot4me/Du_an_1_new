<?php
session_start();
require_once './commons/env.php';
require_once './commons/function.php';
require_once './controllers/TourController.php';
require_once './controllers/DashboardController.php';
require_once './controllers/RevenueReportController.php';

$action = $_GET['action'] ?? 'dashboard';

$tourController = new TourController();
$dashboardController = new DashboardController();
$revenueReportController = new RevenueReportController(); 

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

    // --- REVENUE REPORT ACTIONS ---
    case 'revenue-report':
        $revenueReportController->index();
        break;

    // --- DEFAULT ACTION ---
    default:
        $dashboardController->index(); 
        break;
}