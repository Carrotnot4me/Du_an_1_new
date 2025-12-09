<?php
require_once './models/GuideSpecialModel.php';
require_once './models/BookingModel.php';

class GuideSpecialController {
    private $model;
    private $bookingModel;

    public function __construct() {
        $this->model = new GuideSpecialModel();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Hiển thị trang quản lý yêu cầu đặc biệt
     */
    public function list() {
        $email = $_GET['email'] ?? null;
        
        if ($email) {
            // Hiển thị yêu cầu đặc biệt của một khách hàng cụ thể
            $customer = $this->model->getCustomerWithSpecialRequests($email);
            $specialRequests = $customer ? $customer->special_requests : [];
        } else {
            // Hiển thị tất cả yêu cầu đặc biệt
            $specialRequests = $this->model->getAll();
            $customer = null;
        }
        
        $noteTypes = $this->model->getTypes();
        $customersWithRequests = $this->model->getCustomersWithRequests();
        
        include './views/admin/guide-special.php';
    }

    /**
     * Lấy tất cả yêu cầu đặc biệt (API)
     */
    public function getAll() {
        header('Content-Type: application/json');
        $email = $_GET['email'] ?? null;
        
        if ($email) {
            $requests = $this->model->getByCustomerEmail($email);
        } else {
            $requests = $this->model->getAll();
        }
        
        echo json_encode($requests);
        exit;
    }

    /**
     * Lấy yêu cầu đặc biệt theo ID (API)
     */
    public function getById() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $request = $this->model->getById($id);
            echo json_encode($request);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
        }
        exit;
    }

    /**
     * Lấy thông tin khách hàng kèm yêu cầu đặc biệt (API)
     */
    public function getCustomerWithRequests() {
        header('Content-Type: application/json');
        $email = $_GET['email'] ?? null;
        
        if ($email) {
            $customer = $this->model->getCustomerWithSpecialRequests($email);
            echo json_encode($customer);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing email parameter']);
        }
        exit;
    }

    /**
     * Thêm yêu cầu đặc biệt mới
     */
    public function add() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Nếu có email thay vì customerId, tìm booking đầu tiên
        if (!empty($input['email']) && empty($input['customerId'])) {
            $bookingId = $this->model->findBookingIdByEmail($input['email']);
            if (!$bookingId) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Không tìm thấy booking của khách hàng này. Vui lòng kiểm tra lại email.'
                ]);
                exit;
            }
            $input['customerId'] = intval($bookingId);
        }
        
        if (empty($input['customerId']) || empty($input['content'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Thiếu thông tin bắt buộc: customerId/email và content'
            ]);
            exit;
        }
        
        $data = [
            'customerId' => intval($input['customerId']),
            'type' => $input['type'] ?? 'yeu_cau_dac_biet',
            'content' => $input['content']
        ];
        
        $result = $this->model->create($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Yêu cầu đặc biệt đã được thêm thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể thêm yêu cầu đặc biệt. Vui lòng thử lại.']);
        }
        exit;
    }

    /**
     * Cập nhật yêu cầu đặc biệt
     */
    public function update() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
            exit;
        }
        
        if (empty($input['content'])) {
            echo json_encode(['success' => false, 'message' => 'Content is required']);
            exit;
        }
        
        $data = [
            'type' => $input['type'] ?? 'yeu_cau_dac_biet',
            'content' => $input['content']
        ];
        
        $result = $this->model->update($id, $data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Yêu cầu đặc biệt đã được cập nhật thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể cập nhật yêu cầu đặc biệt. Vui lòng thử lại.']);
        }
        exit;
    }

    /**
     * Xóa yêu cầu đặc biệt
     */
    public function delete() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
            exit;
        }
        
        $result = $this->model->delete($id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Yêu cầu đặc biệt đã được xóa thành công']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa yêu cầu đặc biệt. Vui lòng thử lại.']);
        }
        exit;
    }

    /**
     * Lấy danh sách loại yêu cầu đặc biệt
     */
    public function getTypes() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getTypes());
        exit;
    }

    /**
     * Lấy danh sách khách hàng có yêu cầu đặc biệt
     */
    public function getCustomersWithRequests() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getCustomersWithRequests());
        exit;
    }
}

