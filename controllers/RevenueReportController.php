<?php
require_once './models/RevenueReportModel.php'; 

class RevenueReportController {
    private $model;

    public function __construct() {
        $this->model = new RevenueReportModel();
    }

    public function index() {
        $year = (int)($_GET['year'] ?? date('Y'));
        $month = (int)($_GET['month'] ?? 0);
        
        $report = $this->model->getRevenueReport($year, $month);
        $paymentHistory = $this->model->getPaymentHistory($year, $month);
        $availableYears = $this->model->getAvailableYears();

        $currentYear = $year;
        $currentMonth = $month;

        include './views/admin/revenue-report.php';
    }

    public function updatePaymentStatus() {
        $paymentId = $_GET['id'] ?? null;
        $status = $_GET['status'] ?? null;
        $year = $_GET['year'] ?? date('Y');
        $month = $_GET['month'] ?? 0;

        if (!$paymentId || !$status) {
            header("Location: index.php?action=revenue-report&year=$year&month=$month&message=Thiếu thông tin cập nhật trạng thái thanh toán.&status=danger");
            exit();
        }

        $success = $this->model->updatePaymentStatus($paymentId, $status);

        if ($success) {
            header("Location: index.php?action=revenue-report&year=$year&month=$month&message=Cập nhật trạng thái Mã TT #$paymentId thành công: $status&status=success");
            exit();
        } else {
            header("Location: index.php?action=revenue-report&year=$year&month=$month&message=Lỗi khi cập nhật trạng thái Mã TT #$paymentId.&status=danger");
            exit();
        }
    }
}