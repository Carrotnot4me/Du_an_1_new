<?php
session_start();
require_once '../commons/env.php';
require_once '../commons/function.php';
require_once '../models/BookingModel.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $bookingModel = new BookingModel();

    switch ($method) {
        case 'GET':
            $action = $_GET['action'] ?? 'list';
            
            if ($action === 'statuses') {
                // Lấy danh sách trạng thái
                $statuses = $bookingModel->getStatuses();
                echo json_encode($statuses ? $statuses : []);
            } else {
                // Lấy danh sách booking
                $bookings = $bookingModel->getAll();
                echo json_encode($bookings ? $bookings : []);
            }
            break;
            
        case 'POST':
            // Cập nhật trạng thái booking
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
            
            $result = $bookingModel->updateStatus($id, $status);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status']);
            }
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage()
    ]);
    error_log('Booking Status API Error: ' . $e->getMessage());
}

