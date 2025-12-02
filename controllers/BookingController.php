<?php
require_once './models/BookingModel.php';

class BookingController {
    private $model;

    public function __construct() {
        $this->model = new BookingModel();
    }

    public function list() {
        $bookings = $this->model->getAll();
        $statuses = $this->model->getStatuses();
        include './views/admin/booking-list.php';
    }

    public function getBookings() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAll());
        exit;
    }

    public function updateStatus() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        $status = $input['status'] ?? null;
        
        if ($id && $status) {
            $result = $this->model->updateStatus($id, $status);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        }
        exit;
    }

    public function getStatuses() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getStatuses());
        exit;
    }
}

