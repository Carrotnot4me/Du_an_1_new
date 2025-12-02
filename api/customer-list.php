<?php
session_start();
require_once '../commons/env.php';
require_once '../commons/function.php';
require_once '../models/CustomerModel.php';
require_once '../models/BookingModel.php';
require_once '../models/NoteModel.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $customerModel = new CustomerModel();
    $bookingModel = new BookingModel();
    $noteModel = new NoteModel();

    switch ($method) {
        case 'GET':
            $action = $_GET['action'] ?? 'list';
            $email = $_GET['email'] ?? null;
            
            if ($action === 'detail' && $email) {
                // Lấy chi tiết khách hàng
                $customer = $customerModel->getByEmail($email);
                $bookings = $customerModel->getBookingsByEmail($email);
                $notes = $noteModel->getByEmail($email);
                
                echo json_encode([
                    'customer' => $customer ? $customer : null,
                    'bookings' => $bookings ? $bookings : [],
                    'notes' => $notes ? $notes : []
                ]);
            } else {
                // Lấy danh sách khách hàng
                $customers = $customerModel->getAll();
                echo json_encode($customers ? $customers : []);
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
    error_log('Customer List API Error: ' . $e->getMessage());
}

