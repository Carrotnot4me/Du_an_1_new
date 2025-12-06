<?php
require_once './models/TourModel.php';

class TourController {
    private $model;

    public function __construct() {
        $this->model = new TourModel();
    }

    public function list() {
        include './views/admin/tour-list.php';
    }

    public function getTours() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAll());
        exit;
    }

    public function add() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $result = $this->model->create($input);
        echo json_encode(['success' => $result]);
        exit;
    }

    public function delete() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? null;
        $success = $id ? $this->model->delete($id) : false;
        echo json_encode(['success' => $success]);
        exit;
    }

    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=tour-list');
            exit;
        }
        
        $tour = $this->model->getById($id);
        if (!$tour) {
            header('Location: index.php?action=tour-list');
            exit;
        }
        
        include './views/admin/tour-detail.php';
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?action=tour-list');
            exit;
        }
        
        $tour = $this->model->getById($id);
        if (!$tour) {
            header('Location: index.php?action=tour-list');
            exit;
        }
        
        include './views/admin/tour-edit.php';
    }

    public function update() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID tour']);
            exit;
        }

        $data = [
            'max_people' => $_POST['max_people'] ?? null,
            'suppliers' => [
                'hotel' => $_POST['hotel'] ?? null,
                'restaurant' => $_POST['restaurant'] ?? null,
                'transport' => $_POST['transport'] ?? null
            ]
        ];

        // Nếu có departure_id và date_end thì cập nhật
        if (!empty($_POST['departure_id']) && !empty($_POST['date_end'])) {
            $data['departure_id'] = $_POST['departure_id'];
            $data['departure_date_end'] = $_POST['date_end'];
        }

        $result = $this->model->update($id, $data);
        echo json_encode(['success' => $result]);
        exit;
    }
}