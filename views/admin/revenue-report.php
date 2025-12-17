<?php
$currentYear = (int)($currentYear ?? date('Y'));
$currentMonth = (int)($currentMonth ?? 0);
$details = $report['details'] ?? [];
$summary = $report['summary'] ?? ['total_revenue' => 0, 'total_expense' => 0, 'total_profit' => 0];
$paymentHistory = $paymentHistory ?? [];

// Thêm placeholder cho $availableYears để tránh lỗi Undefined variable nếu không được truyền từ Controller
$availableYears = $availableYears ?? [date('Y'), date('Y') - 1]; 

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

$max_value = max($summary['total_revenue'], $summary['total_expense'], $summary['total_profit']);
$chart_height = 200;

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

        <!-- TRANG CHÍNH -->
        <a class="nav-item active" href="index.php?action=dashboard">
          <i class="bi bi-house-door-fill me-2"></i> Trang quản trị
        </a>

        <!-- QUẢN LÝ TOUR -->

        <a class="nav-item" href="index.php?action=tour-list">
          <i class="bi bi-airplane me-2"></i> Danh sách Tour
        </a>
        <a class="nav-item" href="index.php?action=customer-list"><i class="bi bi-people me-2"></i> Quản lý Khách hàng</a>

        <a class="nav-item" href="index.php?action=supplier-list">
          <i class="bi bi-building me-2"></i> Quản lý Nhà Cung Cấp
        </a>

        <a class="nav-item" href="index.php?action=guide-logs">
          <i class="bi bi-journal-text me-2"></i> Nhật ký Tour
        </a>

        <a class="nav-item" href="index.php?action=booking-list">
          <i class="bi bi-calendar-check me-2"></i> Booking
        </a>

        <a class="nav-item" href="index.php?action=checkin">
          <i class="bi bi-clipboard-check me-2"></i> Quy trình Check-in
        </a>


        <!-- HƯỚNG DẪN VIÊN -->

        <a class="nav-item" href="index.php?action=guides">
          <i class="bi bi-person-badge-fill me-2"></i> Danh sách HDV
        </a>

        <a class="nav-item" href="index.php?action=schedule-assign">
          <i class="bi bi-calendar-event me-2"></i> Phân công lịch
        </a>

        <a class="nav-item" href="index.php?action=guide-schedule">
          <i class="bi bi-list-check me-2"></i> Lịch HDV
        </a>

        <!-- BÁO CÁO -->

        <a class="nav-item" href="index.php?action=revenue-report">
          <i class="bi bi-currency-dollar me-2"></i> Doanh thu
        </a>

        <!-- KHÁC -->

        <a class="nav-item" href="index.php?action=guide-special">
          <i class="bi bi-heart-pulse me-2"></i> Yêu cầu đặc biệt
        </a>

        <a class="nav-item" href="index.php?action=special-notes">
          <i class="bi bi-sticky me-2"></i> Ghi chú
        </a>

      </nav>

        
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
        .bar-chart-container {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 250px;
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
            min-height: 1px;
        }
        .bar-label {
            margin-top: 5px;
            font-size: 14px;
            font-weight: 500;
        }
        .status-icon {
            cursor: pointer;
        }
        .status-text {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 2px;
        }
        .table-responsive {
        overflow: visible !important;
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
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-dark"><i class="bi bi-file-earmark-bar-graph me-2"></i> Báo cáo Doanh thu</h2>
            <a href="index.php?action=add-payment-form" class="btn btn-warning">
                <i class="bi bi-cash-coin me-2"></i> Ghi nhận Thanh toán
            </a>
        </div>

        <?php 
            $message = $_GET['message'] ?? null;
            $status = $_GET['status'] ?? null;
            if ($message): 
        ?>
            <div class="alert alert-<?php echo $status === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
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
        
        <h4 class="mt-5 mb-3 text-dark"><i class="bi bi-wallet-fill me-2"></i> Lịch sử Thanh toán</h4>
        <div class="card-panel bg-white rounded shadow-sm p-4 mb-4">
            <?php if (empty($paymentHistory)): ?>
                <div class="alert alert-warning" role="alert">
                    Không có giao dịch thanh toán nào được ghi nhận cho kỳ này.
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã TT</th> <th>Mã Booking</th>
                            <th>Tour</th>
                            <th>Ngày Thanh toán</th>
                            <th class="text-end">Số tiền (VNĐ)</th>
                            <th>Phương thức</th>
                            <th class="text-center">Trạng thái</th> </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paymentHistory as $payment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['id'] ?? 'N/A'); ?></td>
                            <td>#<?php echo htmlspecialchars($payment['bookingId']); ?></td>
                            <td><?php echo htmlspecialchars($payment['tour_name']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($payment['payment_date'])); ?></td>
                            <td class="text-end">
                                <strong><?php echo formatCurrency($payment['amount']); ?></strong>
                                <?php if (isset($payment['total_booking_amount']) && $payment['amount'] < $payment['total_booking_amount']): ?>
                                    <small class="text-danger"></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($payment['method']); ?></td>
                            
                            <td class="text-center text-nowrap">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle status-icon" 
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Cập nhật trạng thái">
                                        <?php 
                                            $status = htmlspecialchars($payment['status']);
                                            $icon = 'bi-question-circle-fill text-secondary';
                                            $colorClass = 'text-secondary';

                                            if ($status == 'Hoàn thành') {
                                                $icon = 'bi-check-circle-fill text-success';
                                                $colorClass = 'text-success';
                                            } else if ($status == 'Đã cọc') { // Trạng thái mới
                                                $icon = 'bi-piggy-bank-fill text-info';
                                                $colorClass = 'text-info';
                                            } else if ($status == 'Chờ xử lý') {
                                                $icon = 'bi-clock-fill text-warning';
                                                $colorClass = 'text-warning';
                                            } else if ($status == 'Đã hủy') {
                                                $icon = 'bi-x-octagon-fill text-danger';
                                                $colorClass = 'text-danger';
                                            }
                                        ?>
                                        <i class="bi <?php echo $icon; ?> fs-5"></i>
                                        <span class="status-text <?php echo $colorClass; ?>"><?php echo $status; ?></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><h6 class="dropdown-header">Cập nhật Trạng thái</h6></li>
                                        <li><a class="dropdown-item" href="index.php?action=update-payment-status&id=<?php echo htmlspecialchars($payment['id']); ?>&status=Hoàn thành">Hoàn thành <i class="bi bi-check-circle-fill text-success float-end"></i></a></li>
                                        <li><a class="dropdown-item" href="index.php?action=update-payment-status&id=<?php echo htmlspecialchars($payment['id']); ?>&status=Đã cọc">Đã cọc <i class="bi bi-piggy-bank-fill text-info float-end"></i></a></li>
                                        <li><a class="dropdown-item" href="index.php?action=update-payment-status&id=<?php echo htmlspecialchars($payment['id']); ?>&status=Chờ xử lý">Chờ xử lý <i class="bi bi-clock-fill text-warning float-end"></i></a></li>
                                        <li><a class="dropdown-item" href="index.php?action=update-payment-status&id=<?php echo htmlspecialchars($payment['id']); ?>&status=Đã hủy">Đã hủy <i class="bi bi-x-octagon-fill text-danger float-end"></i></a></li>
                                    </ul>
                                </div>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>