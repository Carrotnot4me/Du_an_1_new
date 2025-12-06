<?php
require_once './models/DashboardModel.php';

class DashboardController {
    private $model;

    public function __construct() {
        $this->model = new DashboardModel();
    }

    public function index() {
        $dashboardData = $this->model->getStats();
        include './views/admin/dashboard.php';
    }

    public function getDashboardStats() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getStats());
        exit;
    }
}