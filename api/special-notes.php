<?php
session_start();
require_once '../commons/env.php';
require_once '../commons/function.php';
require_once '../models/NoteModel.php';
require_once '../models/BookingModel.php';

header('Content-Type: application/json');

try {
    $method = $_SERVER['REQUEST_METHOD'];
    $noteModel = new NoteModel();
    $bookingModel = new BookingModel();

    switch ($method) {
    case 'GET':
        $action = $_GET['action'] ?? 'list';
        $email = $_GET['email'] ?? null;
        
        if ($action === 'types') {
            // Lấy danh sách loại ghi chú
            echo json_encode($noteModel->getTypes());
        } else if ($action === 'by-customer' && $email) {
            // Lấy ghi chú theo khách hàng
            echo json_encode($noteModel->getByEmail($email));
        } else {
            // Lấy tất cả ghi chú
            echo json_encode($noteModel->getAll());
        }
        break;
        
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
            exit;
        }
        
        $action = $input['action'] ?? $_POST['action'] ?? 'add';
        
        if ($action === 'add') {
            // Thêm ghi chú mới
            // Nếu có email thay vì customerId, tìm booking đầu tiên
            if (!empty($input['email']) && empty($input['customerId'])) {
                $bookings = $bookingModel->getByEmail($input['email']);
                if (empty($bookings) || !isset($bookings[0]->id)) {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Không tìm thấy booking của khách hàng này. Vui lòng kiểm tra lại email.'
                    ]);
                    exit;
                }
                $input['customerId'] = intval($bookings[0]->id);
            }
            
            if (empty($input['customerId']) || empty($input['content'])) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Thiếu thông tin bắt buộc: customerId/email và content'
                ]);
                exit;
            }
            
            $customerId = is_numeric($input['customerId']) ? intval($input['customerId']) : intval($input['customerId']);
            
            $data = [
                'customerId' => $customerId,
                'type' => $input['type'] ?? 'khac',
                'content' => $input['content']
            ];
            
            $result = $noteModel->create($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Ghi chú đã được thêm thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm ghi chú. Vui lòng thử lại.']);
            }
        } else if ($action === 'update') {
            // Cập nhật ghi chú
            $id = $input['id'] ?? null;
            
            if (!$id) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Thiếu thông tin: ID ghi chú là bắt buộc'
                ]);
                exit;
            }
            
            if (empty($input['content'])) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Nội dung ghi chú không được để trống'
                ]);
                exit;
            }
            
            // Kiểm tra note có tồn tại không
            $existingNote = $noteModel->getById($id);
            if (!$existingNote) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Không tìm thấy ghi chú cần cập nhật'
                ]);
                exit;
            }
            
            $data = [
                'type' => $input['type'] ?? 'khac',
                'content' => trim($input['content'])
            ];
            
            $result = $noteModel->update($id, $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Ghi chú đã được cập nhật thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật ghi chú. Vui lòng thử lại.']);
            }
        } else if ($action === 'delete') {
            // Xóa ghi chú
            $id = $input['id'] ?? $_POST['id'] ?? null;
            
            if (!$id) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Thiếu thông tin: id là bắt buộc'
                ]);
                exit;
            }
            
            $result = $noteModel->delete($id);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Ghi chú đã được xóa thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa ghi chú. Vui lòng thử lại.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
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
    error_log('Special Notes API Error: ' . $e->getMessage());
}

