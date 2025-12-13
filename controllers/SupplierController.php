<?php
require_once './models/SupplierModel.php';
require_once './commons/function.php';

class SupplierController {
    private $model;

    public function __construct() {
        $this->model = new SupplierModel();
    }

    public function list() {
        $suppliers = $this->model->getAll();
        $serviceTypes = $this->model->getServiceTypes();
        include './views/admin/supplier-list.php';
    }

    public function getSuppliers() {
        header('Content-Type: application/json');
        try {
            $serviceType = $_GET['service_type'] ?? null;
            
            if ($serviceType) {
                $suppliers = $this->model->getByServiceType($serviceType);
            } else {
                $suppliers = $this->model->getAll();
            }
            
            echo json_encode($suppliers ? $suppliers : []);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('SupplierController getSuppliers Error: ' . $e->getMessage());
        }
        exit;
    }

    public function getSupplier() {
        header('Content-Type: application/json');
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
                exit;
            }
            
            $supplier = $this->model->getById($id);
            
            if ($supplier) {
                echo json_encode($supplier);
            } else {
                echo json_encode(['success' => false, 'message' => 'Supplier not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('SupplierController getSupplier Error: ' . $e->getMessage());
        }
        exit;
    }

    public function add() {
        header('Content-Type: application/json');
        try {
            // Xử lý cả FormData và JSON
            if (!empty($_FILES) || !empty($_POST)) {
                // FormData được gửi
                $input = $_POST;
            } else {
                // JSON được gửi
                $input = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
                    exit;
                }
            }
            
            // Xử lý upload logo nếu có
            $logoPath = null;
            if (!empty($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logoPath = uploadFile($_FILES['logo'], 'uploads/suppliers/');
            } elseif (!empty($input['logo'])) {
                $logoPath = $input['logo'];
            }
            
            $data = [
                'name' => $input['name'] ?? '',
                'service_type' => $input['service_type'] ?? 'khac',
                'address' => $input['address'] ?? null,
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
                'website' => $input['website'] ?? null,
                'description' => $input['description'] ?? null,
                'logo' => $logoPath
            ];
            
            if (empty($data['name'])) {
                echo json_encode(['success' => false, 'message' => 'Tên nhà cung cấp là bắt buộc']);
                exit;
            }
            
            $result = $this->model->create($data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Nhà cung cấp đã được thêm thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể thêm nhà cung cấp. Vui lòng thử lại.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('SupplierController add Error: ' . $e->getMessage());
        }
        exit;
    }

    public function update() {
        header('Content-Type: application/json');
        try {
            // Xử lý cả FormData và JSON
            if (!empty($_FILES) || !empty($_POST)) {
                // FormData được gửi
                $input = $_POST;
            } else {
                // JSON được gửi
                $input = json_decode(file_get_contents('php://input'), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
                    exit;
                }
            }
            
            $id = $input['id'] ?? null;
            
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
                exit;
            }
            
            // Xử lý upload logo nếu có
            $logoPath = null;
            if (!empty($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                // Xóa logo cũ nếu có
                $existingSupplier = $this->model->getById($id);
                if ($existingSupplier && $existingSupplier->logo) {
                    deleteFile($existingSupplier->logo);
                }
                $logoPath = uploadFile($_FILES['logo'], 'uploads/suppliers/');
            } elseif (!empty($input['logo'])) {
                $logoPath = $input['logo'];
            } else {
                // Giữ nguyên logo cũ
                $existingSupplier = $this->model->getById($id);
                $logoPath = $existingSupplier ? $existingSupplier->logo : null;
            }
            
            $data = [
                'name' => $input['name'] ?? '',
                'service_type' => $input['service_type'] ?? 'khac',
                'address' => $input['address'] ?? null,
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
                'website' => $input['website'] ?? null,
                'description' => $input['description'] ?? null,
                'logo' => $logoPath
            ];
            
            if (empty($data['name'])) {
                echo json_encode(['success' => false, 'message' => 'Tên nhà cung cấp là bắt buộc']);
                exit;
            }
            
            $result = $this->model->update($id, $data);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Nhà cung cấp đã được cập nhật thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật nhà cung cấp. Vui lòng thử lại.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('SupplierController update Error: ' . $e->getMessage());
        }
        exit;
    }

    public function delete() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? $_POST['id'] ?? null;
            
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
                exit;
            }
            
            // Xóa logo nếu có
            $supplier = $this->model->getById($id);
            if ($supplier && $supplier->logo) {
                deleteFile($supplier->logo);
            }
            
            $result = $this->model->delete($id);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Nhà cung cấp đã được xóa thành công']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa nhà cung cấp. Vui lòng thử lại.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('SupplierController delete Error: ' . $e->getMessage());
        }
        exit;
    }

    public function getServiceTypes() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getServiceTypes());
        exit;
    }
}

