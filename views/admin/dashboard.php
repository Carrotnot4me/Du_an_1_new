<?php
function formatCurrency($amount) {
    if ($amount === null) return '0đ';
    return number_format($amount, 0, ',', '.') . 'đ';
}

$dashboardData = $dashboardData ?? [];

// Compute revenue/expense/profit from `payment_history` if available
require_once __DIR__ . '/../../commons/function.php';
$db = connectDB();

$last12MonthsLabels = [];
$last12MonthsRevenue = [];
$last12MonthsExpenses = [];

$totalRevenueAcc = 0;
$totalExpenseAcc = 0;
$totalProfitAcc = 0;

try {
    // total revenue (sum of all amounts)
    $stmt = $db->query("SELECT COALESCE(SUM(amount),0) AS total FROM payment_history");
    $row = $stmt->fetch();
    $totalRevenueAcc = (int)($row['total'] ?? 0);

    // expenses = 70% of revenue, profit = 30%
    $totalExpenseAcc = (int)round($totalRevenueAcc * 0.7);
    $totalProfitAcc = $totalRevenueAcc - $totalExpenseAcc;

    // prepare last 12 months labels and sums
    $now = new DateTime();
    // build months from 11 months ago to this month
    $months = [];
    for ($i = 11; $i >= 0; $i--) {
        $d = (clone $now)->modify("-{$i} months");
        $months[] = $d;
        $last12MonthsLabels[] = 'Tháng ' . $d->format('m');
    }

    // fetch sums grouped by year/month in one query
    $start = $months[0]->format('Y-m-01 00:00:00');
    $end = $months[count($months)-1]->format('Y-m-t 23:59:59');
    $gstmt = $db->prepare("SELECT YEAR(created_at) AS y, MONTH(created_at) AS m, COALESCE(SUM(amount),0) AS s FROM payment_history WHERE created_at BETWEEN :start AND :end GROUP BY y, m");
    $gstmt->execute([':start' => $start, ':end' => $end]);
    $groups = $gstmt->fetchAll(PDO::FETCH_ASSOC);
    $map = [];
    foreach ($groups as $g) {
        $key = sprintf('%04d-%02d', $g['y'], $g['m']);
        $map[$key] = (int)$g['s'];
    }

    foreach ($months as $m) {
        $k = $m->format('Y-m');
        $rev = $map[$k] ?? 0;
        $last12MonthsRevenue[] = $rev;
        $last12MonthsExpenses[] = (int)round($rev * 0.7);
    }
} catch (Exception $e) {
    // fallback to provided data or zeros
    $last12MonthsLabels = $dashboardData['last_12_months_labels'] ?? array_fill(0, 12, 'T');
    $last12MonthsRevenue = $dashboardData['last_12_months_revenue'] ?? array_fill(0, 12, 0);
    $last12MonthsExpenses = $dashboardData['last_12_months_expenses'] ?? array_fill(0, 12, 0);
    $totalRevenueAcc = $dashboardData['total_revenue_acc'] ?? 0;
    $totalExpenseAcc = $dashboardData['total_expense_acc'] ?? 0;
    $totalProfitAcc = $dashboardData['total_profit_acc'] ?? 0;
}

// Fetch totals and lists for tours, bookings, guides and departures
try {
    // total tours
    $tstmt = $db->query("SELECT COUNT(*) AS cnt FROM tours");
    $trow = $tstmt->fetch();
    $totalTours = (int)($trow['cnt'] ?? 0);

    // total bookings
    $bstmt = $db->query("SELECT COUNT(*) AS cnt FROM bookings");
    $brow = $bstmt->fetch();
    $totalBookings = (int)($brow['cnt'] ?? 0);

    // recent guides: pick staff rows (limit 10)
    $gstmt = $db->query("SELECT id, name, experience, COALESCE(toursLed,0) AS toursLed FROM staffs ORDER BY toursLed DESC, name ASC LIMIT 10");
    $recent_guides = $gstmt->fetchAll(PDO::FETCH_ASSOC);

    // upcoming departures: join departures -> tours and guide name
    $dstmt = $db->prepare("SELECT d.id, d.tourId, d.dateStart, d.dateEnd, d.meetingPoint, t.name AS tour_name, s.name AS guide FROM departures d LEFT JOIN tours t ON d.tourId = t.id LEFT JOIN staffs s ON d.guideId = s.id WHERE d.dateStart >= CURDATE() ORDER BY d.dateStart ASC LIMIT 10");
    $dstmt->execute();
    $upcoming_departures = $dstmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $totalTours = $dashboardData['total_tours'] ?? 0;
    $totalBookings = $dashboardData['total_bookings'] ?? 0;
    $recent_guides = $dashboardData['recent_guides'] ?? [];
    $upcoming_departures = $dashboardData['upcoming_departures'] ?? [];
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Trang quản trị - Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <link rel="stylesheet" href="./assets/list.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .table-scroll-body {
            max-height: 250px;
            overflow-y: auto;
            display: block;
        }
        
        .table-scroll-body table {
            width: 100%;
        }

        .table-responsive table thead {
            display: table-header-group;
        }

        
    </style>
</head>

<body style="display:flex; background:#fdf8e7;">

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

        <?php $user = $_SESSION['user'] ?? null; ?>
        <div style="margin-top:auto;font-size:13px;opacity:.9">
            <div>Người dùng: <strong><?php echo $user['username'] ?? 'Admin'; ?></strong></div>
            <div style="margin-top:6px">Email: <small><?php echo $user['email'] ?? 'admin@example.com'; ?></small></div>
        </div>

        
    </aside>

    <main class="main flex-grow-1 p-4">
        <h2 class="mb-4 text-dark">Trang quản trị</h2>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="p-4 bg-white rounded shadow-sm text-center">
                    <h4 class="text-dark" id="totalTours"><?php echo $totalTours ?? ($dashboardData['total_tours'] ?? 0); ?></h4>
                    <div>Tổng số Tour</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-white rounded shadow-sm text-center">
                    <h4 class="text-dark" id="totalBookings"><?php echo $totalBookings ?? ($dashboardData['total_bookings'] ?? 0); ?></h4>
                    <div>Tổng số Booking</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-white rounded shadow-sm text-center">
                    <h4 class="text-dark" id="totalRevenue"><?php echo formatCurrency($totalRevenueAcc); ?></h4>
                    <div>Tổng doanh thu </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 bg-white rounded shadow-sm text-center">
                    <h4 class="text-dark" id="totalExpenses"><?php echo formatCurrency($totalExpenseAcc); ?></h4>
                    <div>Tổng chi phí </div>
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <div class="p-4 bg-white rounded shadow-sm text-center">
                    <h4 class="text-dark" id="netProfit"><?php echo formatCurrency($totalProfitAcc); ?></h4>
                    <div>Lợi nhuận </div>
                </div>
            </div>
        </div>

        <h4 class="mt-5 mb-3 text-dark">Thống kê chi phí và doanh thu (12 tháng gần nhất)</h4>
        <div class="p-4 bg-white rounded shadow-sm">
            <canvas id="revenueChart"></canvas>
        </div>

        <h4 class="mt-5 mb-3 text-dark">Danh sách hướng dẫn viên</h4>
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="table-warning text-dark">
                    <tr>
                        <th>STT</th>
                        <th>Tên HDV</th>
                        <th>Kinh nghiệm</th>
                        <th>Số tour đã dẫn</th>
                    </tr>
                </thead>
                <div class="table-scroll-body">
                    <tbody id="guideListBody">
                        <?php if (!empty($recent_guides)): ?>
                            <?php foreach ($recent_guides as $index => $guide): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $guide['name']; ?></td>
                                    <td><?php echo $guide['experience'] ?? 'N/A'; ?></td>
                                    <td><?php echo $guide['toursLed'] ?? 0; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted">Chưa có dữ liệu hướng dẫn viên</td></tr>
                        <?php endif; ?>
                    </tbody>
                </div>
            </table>
        </div>

        <h4 class="mt-5 mb-3 text-dark">Lịch khởi hành sắp tới</h4>
        <div class="table-responsive bg-white rounded shadow-sm">
            <table class="table table-hover mb-0">
                <thead class="table-warning text-dark">
                    <tr>
                        <th>STT</th>
                        <th>Tên Tour</th>
                        <th>Ngày khởi hành</th>
                        <th>Ngày kết thúc</th>
                        <th>Hướng dẫn viên</th>
                    </tr>
                </thead>
                <div class="table-scroll-body">
                    <tbody id="upcomingDeparturesBody">
                        <?php if (!empty($upcoming_departures)): ?>
                            <?php foreach ($upcoming_departures as $index => $departure): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo $departure['tour_name']; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($departure['dateStart'])); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($departure['dateEnd'])); ?></td>
                                    <td><?php echo $departure['guide'] ?? 'Chưa chỉ định'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center text-muted">Chưa có lịch khởi hành sắp tới</td></tr>
                        <?php endif; ?>
                    </tbody>
                </div>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const chartLabels = <?php echo json_encode($last12MonthsLabels); ?>;
        const chartRevenueData = <?php echo json_encode($last12MonthsRevenue); ?>;
        const chartExpensesData = <?php echo json_encode($last12MonthsExpenses); ?>;

        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Doanh thu',
                    data: chartRevenueData,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                }, {
                    label: 'Chi phí',
                    data: chartExpensesData,
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Thống kê Doanh thu và Chi phí'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>