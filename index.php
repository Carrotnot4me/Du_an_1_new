<?php
session_start();
require_once './commons/env.php';
require_once './commons/function.php';
require_once './controllers/TourController.php';

$controller = new TourController();
$action = $_GET['action'] ?? 'tour-list';

match ($action) {
    ''              => $controller->list(),
    'tour-list'     => $controller->list(),
    'getTours'      => $controller->getTours(),
    'addTour'       => $controller->add(),
    'deleteTour'    => $controller->delete(),
    default         => $controller->list(),
};