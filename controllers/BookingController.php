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
        try {
            $bookings = $this->model->getAll();
            echo json_encode($bookings ? $bookings : []);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('BookingController getBookings Error: ' . $e->getMessage());
        }
        exit;
    }

    public function updateStatus() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
                exit;
            }
            
            $id = $input['id'] ?? null;
            $status = $input['status'] ?? null;
            
            if (!$id || !$status) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters: id and status are required']);
                exit;
            }
            
            $result = $this->model->updateStatus($id, $status);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('BookingController updateStatus Error: ' . $e->getMessage());
        }
        exit;
    }

    public function getStatuses() {
        header('Content-Type: application/json');
        try {
            $statuses = $this->model->getStatuses();
            echo json_encode($statuses ? $statuses : []);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('BookingController getStatuses Error: ' . $e->getMessage());
        }
        exit;
    }
}

