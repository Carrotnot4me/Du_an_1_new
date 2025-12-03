<?php
require_once './models/NoteModel.php';
require_once './models/BookingModel.php';

class NoteController {
    private $model;
    private $bookingModel;

    public function __construct() {
        $this->model = new NoteModel();
        $this->bookingModel = new BookingModel();
    }

    public function list() {
        $notes = $this->model->getAll();
        $noteTypes = $this->model->getTypes();
        include './views/admin/special-notes.php';
    }

    public function getNotes() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAll());
        exit;
    }

    public function getNotesByCustomer() {
        header('Content-Type: application/json');
        $email = $_GET['email'] ?? null;
        
        if ($email) {
            echo json_encode($this->model->getByEmail($email));
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing email parameter']);
        }
        exit;
    }

    public function add() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
                exit;
            }
            
            // Nếu có email thay vì customerId, tìm booking đầu tiên
            if (!empty($input['email']) && empty($input['customerId'])) {
                $bookings = $this->bookingModel->getByEmail($input['email']);
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
            
            $result = $this->model->create($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Ghi chú đã được thêm thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm ghi chú. Vui lòng thử lại.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('NoteController add Error: ' . $e->getMessage());
        }
        exit;
    }

    public function update() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
                exit;
            }
            
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
            $existingNote = $this->model->getById($id);
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
            
            $result = $this->model->update($id, $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Ghi chú đã được cập nhật thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật ghi chú. Vui lòng thử lại.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('NoteController update Error: ' . $e->getMessage());
        }
        exit;
    }

    public function delete() {
        header('Content-Type: application/json');
        try {
            // Hỗ trợ cả POST và JSON input
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? $_POST['id'] ?? null;
            
            if (!$id) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Thiếu thông tin: id là bắt buộc'
                ]);
                exit;
            }
            
            $result = $this->model->delete($id);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Ghi chú đã được xóa thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa ghi chú. Vui lòng thử lại.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('NoteController delete Error: ' . $e->getMessage());
        }
        exit;
    }

    public function getTypes() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getTypes());
        exit;
    }
}

