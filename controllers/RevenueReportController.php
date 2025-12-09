<?php
require_once './models/RevenueReportModel.php';

class RevenueReportController {
    private $model;

    public function __construct() {
        $this->model = new RevenueReportModel();
    }

    public function index() {
        $availableYears = $this->model->getAvailableYears();
        
        $defaultYear = !empty($availableYears) ? end($availableYears) : date('Y');
        
        $currentYear = $_GET['year'] ?? $defaultYear;
        
        $currentMonth = (int)($_GET['month'] ?? 0); 
        $currentYear = (int)$currentYear; 

        $reportData = $this->model->getRevenueReport($currentYear, $currentMonth);

        $data = [
            'report' => $reportData,
            'currentYear' => $currentYear, 
            'currentMonth' => $currentMonth, 
            'availableYears' => $availableYears,
        ];
        
        extract($data); 
        
        include './views/admin/revenue-report.php';
    }
}