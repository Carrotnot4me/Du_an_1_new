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
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Nếu có email thay vì customerId, tìm booking đầu tiên
        if (!empty($input['email']) && empty($input['customerId'])) {
            $bookings = $this->bookingModel->getByEmail($input['email']);
            if (!empty($bookings) && isset($bookings[0]->id)) {
                $input['customerId'] = intval($bookings[0]->id);
            }
        }
        
        if (!empty($input['customerId']) && !empty($input['content'])) {
            // Đảm bảo customerId là số nguyên
            $customerId = is_numeric($input['customerId']) ? intval($input['customerId']) : intval($input['customerId']);
            
            $data = [
                'customerId' => $customerId,
                'type' => $input['type'] ?? 'khac',
                'content' => $input['content']
            ];
            $result = $this->model->create($data);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing required fields (customerId/email and content)']);
        }
        exit;
    }

    public function update() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        
        if ($id && !empty($input['content'])) {
            $data = [
                'type' => $input['type'] ?? 'khac',
                'content' => $input['content']
            ];
            $result = $this->model->update($id, $data);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        }
        exit;
    }

    public function delete() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? null;
        
        if ($id) {
            $result = $this->model->delete($id);
            echo json_encode(['success' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
        }
        exit;
    }

    public function getTypes() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getTypes());
        exit;
    }
}

