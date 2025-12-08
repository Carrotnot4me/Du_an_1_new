<?php
session_start();
require_once './commons/env.php';
require_once './commons/function.php';
require_once './controllers/TourController.php';
require_once './controllers/AuthController.php';
require_once './controllers/BookingController.php';

$tourController = new TourController();
$authController = new AuthController();
$bookingController = new BookingController();

// Accept action from GET or POST so forms can submit to actions
$action = $_REQUEST['action'] ?? 'showLogin';

match ($action) {
    ''              => $authController->showLogin(),
    'showLogin'     => $authController->showLogin(),
    'showRegister'  => $authController->showRegister(),
    'tour-list'     => $tourController->list(),
    'getTours'      => $tourController->getTours(),
    'addTour'       => $tourController->add(),
    'deleteTour'    => $tourController->delete(),
    'editTour'      => $tourController->edit(),
    'deleteDeparture' => $tourController->deleteDeparture(),
    'addDeparture'    => $tourController->addDeparture(),
    'updateDeparture' => $tourController->updateDeparture(),
    'duplicateTour'  => $tourController->duplicateTour(),
    'updateTour'    => $tourController->update(),
    'booking'       => $bookingController->form(),
    'addBooking'    => $bookingController->create(),
    'bookingList'   => $bookingController->listBookings(),
    'bookingCreate' => $bookingController->create(),
    'login'         => $authController->login(),
    'logout'        => $authController->logout(),
    'register'      => $authController->register(),
    'profile'       => $authController->profile(),
    'trangchu'      => include './views/trangchu.php',
    default         => $tourController->list(),
};