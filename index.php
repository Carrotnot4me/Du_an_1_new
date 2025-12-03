<?php
session_start();
require_once './commons/env.php';
require_once './commons/function.php';

// Controllers
require_once './controllers/GuideController.php';
require_once './controllers/ScheduleController.php';
require_once './controllers/GuideScheduleController.php';

// Lấy action từ URL, mặc định là 'guides'
$action = $_GET['action'] ?? 'guides';

// Khởi tạo controller
$guideController = new GuideController();
$scheduleController = new ScheduleController();
$guideScheduleController = new GuideScheduleController();

// Điều hướng theo action
match ($action) {
    // Hướng dẫn viên
    'guides' => $guideController->index(),

    // Phân công lịch khởi hành
    'schedule-assign' => $scheduleController->index(),
    'schedule-save'   => $scheduleController->save(),

    // Lịch theo HDV cụ thể
    'guide-schedule'  => $guideScheduleController->index(),

    // Mặc định: quay về danh sách HDV
    '' => $guideController->index(),
    default => $guideController->index(),
};
