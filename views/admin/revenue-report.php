<?php
$currentYear = (int)($currentYear ?? date('Y'));
$currentMonth = (int)($currentMonth ?? 0);
$details = $report['details'] ?? [];
$summary = $report['summary'] ?? ['total_revenue' => 0, 'total_expense' => 0, 'total_profit' => 0];


function formatCurrency($amount) {
    if ($amount === null) return '0đ';
    return number_format($amount, 0, ',', '.') . 'đ';
}

$months = [
    0 => 'Tất cả các tháng',
    1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4', 
    5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8', 
    9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
];

// Lấy giá trị lớn nhất để chuẩn hóa chiều cao cột (dành cho biểu đồ)
$max_value = max($summary['total_revenue'], $summary['total_expense'], $summary['total_profit']);
$chart_height = 200; // Chiều cao tối đa của biểu đồ (px)

// Hàm tính chiều cao tương đối
function getChartHeight($value, $max_value, $chart_height) {
    if ($max_value == 0 || $value == 0) return 0;
    return ($value / $max_value) * $chart_height;
}

$sidebarContent = '
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <div style="width:44px;height:44px;background:#f5c542;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3b2a0a;font-weight:700">AD</div>
            <div><div>AdminPanel</div><small style="opacity:.8">v1.0</small></div>
        </div>
        
        <nav>
            <a class="nav-item" href="index.php?action=dashboard"><i class="bi bi-house-door-fill me-2"></i> Trang quản trị</a>
            <a class="nav-item" href="index.php?action=tour-list"><i class="bi bi-airplane me-2"></i> Quản lý Tour</a>
            <a class="nav-item " href="index.php?action=checkin"><i class="bi bi-calendar-check-fill me-2"></i> Quy trình Check-in</a>  
            <a class="nav-item active" href="index.php?action=revenue-report"><i class="bi bi-currency-dollar me-2"></i> Báo cáo Doanh thu</a>
        </nav>
        
        <div style="margin-top:auto;font-size:13px;opacity:.9">
            <div>Người dùng: <strong>Admin</strong></div>
            <div style="margin-top:6px">Email: <small>admin@example.com</small></div>
        </div>
    </aside>
';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Báo cáo Doanh thu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/list.css">
    
    <style>
        .summary-card-inner {
            font-weight: 600;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .main {
            flex-grow: 1; 
            padding: 2rem;
            background-color: #fdf8e7;
        }
        /* Style cho biểu đồ cột */
        .bar-chart-container {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 250px; /* Chiều cao tổng cộng bao gồm cả label */
            padding-top: 50px;
            border-bottom: 1px solid #ccc;
        }
        .bar-chart-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100px;
            margin: 0 10px;
        }
        .bar {
            width: 100%;
            border-radius: 4px 4px 0 0;
            transition: height 0.5s ease-in-out;
            min-height: 1px; /* Đảm bảo cột hiển thị nếu giá trị khác 0 */
        }
        .bar-label {
            margin-top: 5px;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
</head>

<body style="background:#fdf8e7;">

<div class="app" style="display:flex;">
    <?php echo $sidebarContent; ?>

    <main class="main">
        <div class="topbar">
            <button class="btn btn-sm btn-outline-secondary d-md-none" id="btnToggle"><i class="bi bi-list"></i></button>
            <div class="me-2">VI</div>
            <div class="btn btn-light btn-sm"><i class="bi bi-bell"></i></div>
            <div class="rounded-circle bg-warning text-dark d-flex align-items:center;justify-content:center" style="width:50px;height:50px;font-weight:600">A</div>
        </div>
        
        <h2 class="mb-4 text-dark"><i class="bi bi-file-earmark-bar-graph me-2"></i> Báo cáo Doanh thu</h2>

        <div class="card-panel bg-white rounded shadow-sm p-4 mb-4">
            <form method="GET" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="revenue-report">
                <div class="col-md-4">
                    <label for="selectYear" class="form-label">Chọn Năm</label>
                    <select class="form-select" id="selectYear" name="year" required>
                        <?php foreach ($availableYears as $year): ?>
                            <option value="<?php echo $year; ?>" <?php echo ($currentYear == $year) ? 'selected' : ''; ?>>
                                Năm <?php echo $year; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="selectMonth" class="form-label">Chọn Tháng</label>
                    <select class="form-select" id="selectMonth" name="month">
                        <?php foreach ($months as $key => $monthName): ?>
                            <option value="<?php echo $key; ?>" <?php echo ($currentMonth == $key) ? 'selected' : ''; ?>>
                                <?php echo $monthName; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-warning w-100">Lọc Báo cáo</button>
                </div>
            </form>
        </div>

        <h4 class="mt-4 mb-3 text-dark">Tổng kết (<?php echo $months[$currentMonth]; ?> năm <?php echo $currentYear; ?>)</h4>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="summary-card-inner bg-info-subtle text-dark">
                    <div>Tổng Doanh thu</div>
                    <h3 class="mt-1 text-info"><?php echo formatCurrency($summary['total_revenue']); ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card-inner bg-danger-subtle text-dark">
                    <div>Tổng Chi phí</div>
                    <h3 class="mt-1 text-danger"><?php echo formatCurrency($summary['total_expense']); ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card-inner bg-success-subtle text-dark">
                    <div>Lợi nhuận ròng</div>
                    <h3 class="mt-1 text-success"><?php echo formatCurrency($summary['total_profit']); ?></h3>
                </div>
            </div>
        </div>

        <h4 class="mb-3 text-dark">Biểu đồ Tổng kết</h4>
        <div class="card-panel bg-white rounded shadow-sm p-4 mb-4">
            <div class="bar-chart-container">
                <div class="bar-chart-item">
                    <div style="height:<?php echo getChartHeight($summary['total_revenue'], $max_value, $chart_height); ?>px; background-color:#0dcaf0;" class="bar"></div>
                    <div class="bar-label text-info">Doanh thu</div>
                </div>

                <div class="bar-chart-item">
                    <div style="height:<?php echo getChartHeight($summary['total_expense'], $max_value, $chart_height); ?>px; background-color:#dc3545;" class="bar"></div>
                    <div class="bar-label text-danger">Chi phí</div>
                </div>

                <div class="bar-chart-item">
                    <div style="height:<?php echo getChartHeight($summary['total_profit'], $max_value, $chart_height); ?>px; background-color:#198754;" class="bar"></div>
                    <div class="bar-label text-success">Lợi nhuận</div>
                </div>
            </div>
        </div>
        
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>