<?php
// File: controllers/ScheduleController.php

require_once './models/ScheduleModel.php';

class ScheduleController {
    private $model;
    
    // ĐỊNH NGHĨA TỈ LỆ NÉN: Nén phần quá khứ xuống 40% độ rộng ban đầu
    const PAST_COMPRESSION_FACTOR = 0.4; 

    public function __construct() {
        $this->model = new ScheduleModel();
    }

    public function index() {
        try {
            $departures = $this->model->getDepartures();
        } catch (Exception $e) {
            error_log('ScheduleController index error: ' . $e->getMessage());
            http_response_code(500);
            echo "<div style='padding:18px;background:#fff0f0;color:#900;border:1px solid #f5c2c2;margin:12px;'>" .
                 "<strong>Lỗi khi tải dữ liệu lịch:</strong> " . htmlspecialchars($e->getMessage()) .
                 "<br>Hãy kiểm tra kết nối CSDL hoặc xem log để biết chi tiết.</div>";
            return;
        }
        if (!is_array($departures)) $departures = [];
        $currentDateStr = date('Y-m-d');
        $currentDate = new DateTime($currentDateStr);
        
        // --- 1. XÁC ĐỊNH KHUNG THỜI GIAN TOÀN CỤC TỰ ĐỘNG ---
        
        if (empty($departures)) {
            $globalStart = new DateTime(date('Y-m-01'));
            $globalEnd = (new DateTime(date('Y-m-01')))->modify('+3 months');
        } else {
            $dates = array_merge(
                array_column($departures, 'dateStart'), 
                array_column($departures, 'dateEnd')
            );

            $minDate = min($dates);
            $maxDate = max($dates);
            $minDate = min($minDate, $currentDateStr);
            $maxDate = max($maxDate, $currentDateStr);
            
            $globalStart = new DateTime(date('Y-m-01', strtotime($minDate)));
            // Set global end to the first day AFTER the month that contains the last date
            $globalEnd = new DateTime(date('Y-m-01', strtotime($maxDate)));
            $globalEnd->modify('+1 month');
        }

        // --- 2. TÍNH TOÁN CHIỀU RỘNG ẢO CHO VIỆC NÉN QUÁ KHỨ ---
        
        // Treat global range as [globalStart, globalEnd) so total days is the diff in days
        $totalDaysActual = $globalStart->diff($globalEnd)->days;
        
        // Số ngày thực tế từ Global Start đến Hôm nay
        $daysPastActual = 0;
        if ($currentDate > $globalStart) {
            $endForDiff = ($currentDate < $globalEnd) ? $currentDate : $globalEnd;
            $daysPastActual = $globalStart->diff($endForDiff)->days;
            if ($daysPastActual > $totalDaysActual) $daysPastActual = $totalDaysActual;
        }
        
        // Số ngày ảo đã nén (Quá khứ nén, Tương lai giữ nguyên)
        $daysPastVirtual = $daysPastActual * self::PAST_COMPRESSION_FACTOR;
        $totalDaysVirtual = $daysPastVirtual + ($totalDaysActual - $daysPastActual); 
        if ($totalDaysVirtual == 0) {
            $totalDaysVirtual = 1; // Tránh chia 0
        }

        // --- 3. TÍNH TOÁN VỊ TRÍ VÀ CHIỀU RỘNG ẢO CỦA TỪNG TOUR ---

        $trackRows = [];
        $maxRows = 4;

        foreach ($departures as &$item) {
            $tourStart = new DateTime($item['dateStart']);
            $tourEnd = new DateTime($item['dateEnd']);
            // Use exclusive end for duration calculations (treat ranges as [start, end+1))
            $tourEndEx = (clone $tourEnd)->modify('+1 day');
            $tourDurationActual = $tourStart->diff($tourEndEx)->days;

            // --- A. Tính toán Vị trí BẮT ĐẦU ẢO (startVirtualPosition) ---
            $startVirtualPosition = 0;
            $daysToTourStartActual = $globalStart->diff($tourStart)->days;

            if ($tourStart < $currentDate) {
                // Tour bắt đầu trong Quá khứ
                $startVirtualPosition = $daysToTourStartActual * self::PAST_COMPRESSION_FACTOR;
            } else {
                // Tour bắt đầu trong Tương lai (Sau hoặc bằng Hôm nay)
                $daysFromToday = $currentDate->diff($tourStart)->days;
                $startVirtualPosition = $daysPastVirtual + $daysFromToday;
            }

            // --- B. Tính toán CHIỀU RỘNG ẢO (widthVirtual) ---
            $widthVirtual = 0;
            
            if ($tourEnd < $currentDate) {
                // Case 1: Tour hoàn toàn trong Quá khứ -> NÉN TOÀN BỘ
                $widthVirtual = $tourDurationActual * self::PAST_COMPRESSION_FACTOR;
            } elseif ($tourStart >= $currentDate) {
                // Case 2: Tour hoàn toàn trong Tương lai -> KHÔNG NÉN
                $widthVirtual = $tourDurationActual;
            } else {
                // Case 3: Tour VƯỢT QUA MỐC HÔM NAY -> NÉN 1 PHẦN
                $daysPastPart = $tourStart->diff($currentDate)->days; // days before today (exclusive)
                $daysFuturePart = $currentDate->diff($tourEndEx)->days; // future part until end (exclusive end)
                $widthVirtual = ($daysPastPart * self::PAST_COMPRESSION_FACTOR) + $daysFuturePart;
            }

            // Áp dụng tỉ lệ phần trăm cho CSS
            $item['style_left'] = round(($startVirtualPosition / $totalDaysVirtual) * 100, 2);
            $item['style_width'] = round(($widthVirtual / $totalDaysVirtual) * 100, 2);
            
            // --- C. Xử lý Vị trí Hàng và Trạng thái ---
            $foundRow = false;
            for ($i = 0; $i < $maxRows; $i++) {
                if (!isset($trackRows[$i]) || strtotime($trackRows[$i]) < strtotime($item['dateStart'])) {
                    $item['style_row'] = $i;
                    $trackRows[$i] = $item['dateEnd'];
                    $foundRow = true;
                    break;
                }
            }
            if (!$foundRow) {
                 $item['style_row'] = $maxRows;
            }
            if ($item['dateEnd'] < $currentDateStr) {
                $item['status_class'] = 'ended';
                $item['status_text'] = 'Đã kết thúc';
            } elseif ($item['dateStart'] <= $currentDateStr && $item['dateEnd'] >= $currentDateStr) {
                $item['status_class'] = 'ongoing';
                $item['status_text'] = 'Đang diễn ra';
            } else {
                $item['status_class'] = 'upcoming';
                $item['status_text'] = 'Sắp tới';
            }
        }
        
        // --- 4. TÍNH TOÁN LẠI VỊ TRÍ ĐƯỜNG KẺ "HÔM NAY" VÀ CẤU HÌNH THÁNG ---

        // Vị trí đường kẻ "Hôm nay" ảo
        $todayVirtualLeft = round(($daysPastVirtual / $totalDaysVirtual) * 100, 2);
        
        // Tính tổng số tháng (Không đổi)
        $globalEndForCalc = clone $globalEnd;
        $endMonth = $globalEndForCalc->modify('-1 day')->format('n');
        $endYear = $globalEndForCalc->format('Y');
        $startMonth = $globalStart->format('n');
        $startYear = $globalStart->format('Y');
        $totalMonths = (($endYear - $startYear) * 12) + ($endMonth - $startMonth) + 1;

        // Tạo danh sách cột tháng với chiều rộng ảo (Virtual Width)
        $columns = [];
        $tempDate = clone $globalStart;
        for ($i = 0; $i < $totalMonths; $i++) {
            $monthStart = clone $tempDate;
            $tempDate->modify('+1 month');
            $monthEnd = clone $tempDate;
            
            // monthStart..monthEnd is [monthStart, monthEnd) where monthEnd is first day of next month
            $monthDurationActual = $monthStart->diff($monthEnd)->days;
            $monthVirtualDuration = 0;
            
            if ($monthEnd <= $currentDate) {
                // Tháng hoàn toàn trong Quá khứ -> NÉN
                $monthVirtualDuration = $monthDurationActual * self::PAST_COMPRESSION_FACTOR;
            } elseif ($monthStart >= $currentDate) {
                // Tháng hoàn toàn trong Tương lai -> KHÔNG NÉN
                $monthVirtualDuration = $monthDurationActual;
            } else {
                // Tháng giao với ngày Hiện tại -> NÉN 1 PHẦN
                $daysBeforeToday = $monthStart->diff($currentDate)->days + 1; // Inclusive
                $daysAfterToday = $currentDate->diff($monthEnd)->days; // Exclusive
                $monthVirtualDuration = ($daysBeforeToday * self::PAST_COMPRESSION_FACTOR) + $daysAfterToday;
            }
            
            $monthWidthPercent = round(($monthVirtualDuration / $totalDaysVirtual) * 100, 2);

            $columns[] = [
                'month' => $monthStart->format('n'), 
                'year' => $monthStart->format('Y'), 
                'name' => $monthStart->format('M'),
                'width_percent' => $monthWidthPercent
            ];
        }


        $timelineConfig = [
            'start_month' => $startMonth,
            'start_year' => $startYear,
            'total_months' => $totalMonths,
            'month_names' => ['Thg 1', 'Thg 2', 'Thg 3', 'Thg 4', 'Thg 5', 'Thg 6', 'Thg 7', 'Thg 8', 'Thg 9', 'Thg 10', 'Thg 11', 'Thg 12'],
            'row_height_px' => 60,
            'globalStart' => $globalStart, 
            'globalEnd' => $globalEnd,
            'todayVirtualLeft' => $todayVirtualLeft, 
            'columns' => $columns 
        ];

        require_once './views/admin/schedule-assign.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $depId = $_POST['depId'] ?? null;
            $tourId = $_POST['tourId'] ?? null;
            $dateStart = $_POST['dateStart'] ?? null;
            $dateEnd = $_POST['dateEnd'] ?? null;
            $meetingPoint = $_POST['meetingPoint'] ?? '';
            $guideId = $_POST['guideId'] ?? null;
            $driver = $_POST['driver'] ?? '';

            if (isset($_POST['delete']) && $depId) {
                $this->model->deleteDeparture($depId);
            } elseif ($depId) {
                $this->model->updateDeparture($depId, $tourId, $dateStart, $guideId, $driver);
            } else {
                // Thêm mới không cần depId
                $data = [
                    'tourId' => $tourId,
                    'dateStart' => $dateStart,
                    'dateEnd' => $dateEnd,
                    'meetingPoint' => $meetingPoint,
                    'guideId' => $guideId,
                    'driver' => $driver
                ];
                // Gọi method trong model để thêm departure
            }

            header("Location: index.php?action=schedule-assign");
            exit;
        }
    }
}
