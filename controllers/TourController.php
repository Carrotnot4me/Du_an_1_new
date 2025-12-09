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
}