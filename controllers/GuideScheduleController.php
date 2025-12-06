<?php
require_once './models/GuideScheduleModel.php';

class GuideScheduleController {
    private $model;

    public function __construct() {
        $this->model = new GuideScheduleModel();
    }

    public function index() {
        // Lấy guideId từ URL (nếu có)
        $guideId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        // Lấy thông tin HDV (nếu guideId > 0)
        $staff = $guideId ? $this->model->getStaffById($guideId) : null;

        // Lấy lịch làm việc
        $schedules = $this->model->getSchedules($guideId);

        // Gọi view
        include './views/guide/guide-schedule.php';
    }
}
