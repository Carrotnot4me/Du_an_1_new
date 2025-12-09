<?php
require_once './models/ScheduleModel.php';

class ScheduleController {
    private $model;

    public function __construct() {
        $this->model = new ScheduleModel();
    }

    public function index() {
        $departures = $this->model->getAllDepartures();
        $tours = $this->model->getAllTours();
        $guides = $this->model->getGuides();
        $drivers = $this->model->getDrivers();

        include './views/admin/schedule-assign.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $depId = $_POST['depId'] ?? null;
            $tourId = $_POST['tourId'] ?? null;
            $date = $_POST['date'] ?? null;
            $guideId = $_POST['guideId'] ?? null;
            $driverId = $_POST['driverId'] ?? null;

            if (isset($_POST['delete']) && $depId) {
                $this->model->deleteDeparture($depId);
            } elseif ($depId) {
                $this->model->updateDeparture($depId, $tourId, $date, $guideId, $driverId);
            } else {
                $this->model->addDeparture($tourId, $date, $guideId, $driverId);
            }

            header("Location: index.php?action=schedule-assign");
            exit;
        }
    }
}
