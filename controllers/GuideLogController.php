<?php
require_once './models/GuideLogModel.php';

class GuideLogController {
    private $model;

    public function __construct() {
        $this->model = new GuideLogModel();
    }

    public function list() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $logs = $this->model->getByDate($date);
        $tours = $this->model->getTours();
        $guides = $this->model->getGuides();
        
        include './views/admin/guide-logs.php';
    }

    public function getAll() {
        header('Content-Type: application/json');
        $date = $_GET['date'] ?? null;
        
        if ($date) {
            $logs = $this->model->getByDate($date);
        } else {
            $logs = $this->model->getAll();
        }
        
        echo json_encode($logs);
        exit;
    }

    public function getByTour() {
        header('Content-Type: application/json');
        $tourId = $_GET['tourId'] ?? null;
        
        if ($tourId) {
            $logs = $this->model->getByTourId($tourId);
            echo json_encode($logs);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing tourId parameter']);
        }
        exit;
    }

    public function getById() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $log = $this->model->getById($id);
            echo json_encode($log);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
        }
        exit;
    }

    public function add() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (empty($input['content'])) {
            echo json_encode(['success' => false, 'message' => 'Content is required']);
            exit;
        }
        
        $result = $this->model->create($input);
        echo json_encode(['success' => $result]);
        exit;
    }

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
        
        $result = $this->model->update($id, $input);
        echo json_encode(['success' => $result]);
        exit;
    }

    public function delete() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Missing id parameter']);
            exit;
        }
        
        $result = $this->model->delete($id);
        echo json_encode(['success' => $result]);
        exit;
    }

    public function getTours() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getTours());
        exit;
    }

    public function getGuides() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getGuides());
        exit;
    }
}

