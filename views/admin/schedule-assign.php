<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Phân Bổ Lịch Khởi Hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/list.css">
    <style>
        <?php 
        // Khởi tạo các biến nếu chưa được định nghĩa
        $rowHeight = $timelineConfig['row_height_px'] ?? 60;
        $monthNames = $timelineConfig['month_names'] ?? ['Thg 1', 'Thg 2', 'Thg 3', 'Thg 4', 'Thg 5', 'Thg 6', 'Thg 7', 'Thg 8', 'Thg 9', 'Thg 10', 'Thg 11', 'Thg 12'];
        $columns = $timelineConfig['columns'] ?? [];
        $todayVirtualLeft = $timelineConfig['todayVirtualLeft'] ?? 0;
        ?>
        :root {
            --bg-light: #f5f5dc; 
            --bg-header: #968c5b; 
            --bg-grid: #fffef2; 
            --color-tour-upcoming: #3f789d; /* Xanh dương */
            --color-tour-ongoing: #414141ff; /* Hồng neon */
            --color-tour-ended: #808080; /* Xám */
            --row-height: <?php echo $rowHeight; ?>px;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Rajdhani', sans-serif;
            margin: 0;
            padding: 0;
        }

        .timeline-container {
            width: 100%;
            overflow-x: auto; 
        }

        .timeline-header, .timeline-grid {
            width: calc(100% + 150px); 
            position: relative;
            user-select: none;
        }
        
        /* -------------------------- HEADER -------------------------- */
        .timeline-header {
            display: flex;
            font-weight: bold;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            overflow-x: hidden; 
        }

        .year-band {
            background-color: var(--bg-header);
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            box-sizing: border-box;
            font-size: 1rem;
        }

        .month-band {
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #55523a; 
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            box-sizing: border-box;
            font-size: 0.9rem;
        }
        
        /* -------------------------- GRID & ITEMS -------------------------- */
        .timeline-grid {
            background-color: var(--bg-grid);
            border-top: 1px solid #ccc;
            padding-left: 150px; 
            box-sizing: border-box;
            min-height: 400px; 
            overflow-x: hidden; 
        }
        
        .month-divider {
            position: absolute;
            top: 0;
            height: 100%;
            border-left: 1px solid #ccc;
        }

        .tour-name-label {
            position: absolute;
            width: 150px;
            left: 0;
            padding: 5px;
            box-sizing: border-box;
            font-weight: bold;
            text-align: right;
            border-right: 1px solid #ccc;
            transform: translateX(-100%);
        }

        .tour-item {
            position: absolute;
            height: calc(var(--row-height) - 10px);
            margin-top: 5px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            overflow: hidden;
            cursor: pointer;
            transition: opacity 0.3s, transform 0.2s;
            z-index: 1;
        }

        .tour-item:hover {
            opacity: 0.9;
            z-index: 5;
            transform: scaleY(1.05);
        }

        .tour-item.upcoming {
            background-color: var(--color-tour-upcoming);
        }

        .tour-item.ongoing {
            background-color: var(--color-tour-ongoing);
            border: 2px solid #4a3c0973;
            box-shadow: 0 0 20px #686868ff;
        }

        .tour-item.ended {
            background-color: var(--color-tour-ended);
            opacity: 0.7;
            filter: grayscale(50%);
        }

        .tour-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 300px;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            -webkit-mask-image: linear-gradient(to right, #000 0px, #000 150px, transparent 200px);
            mask-image: linear-gradient(to right, #000 0px, #000 150px, transparent 200px);
            z-index: 1;
        }

        .tour-content {
            position: absolute;
            top: 5px;
            left: 160px;
            z-index: 2;
            color: white;
            text-shadow: 1px 1px 2px black;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: calc(100% - 170px);
        }

        .tour-content h3 {
            font-size: 1rem;
            font-weight: bold;
            float: right;
        }

        .tour-content p {
            margin: 0;
            font-size: 0.8rem;
            float: right;
        }

        .tour-content .status-ended {
            font-size: 0.7rem;
            margin-left: 5px;
        }

        .current-day-line {
            position: absolute;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: red;
            z-index: 100;
        }
        
        .current-day-label {
            position: absolute;
            top: 0;
            right: -30px;
            color: red;
            font-weight: bold;
            font-size: 0.8rem;
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
        <div class="logo">
            <div style="width:44px;height:44px;background:#f5c542;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3b2a0a;font-weight:700">AD</div>
            <div><div>AdminPanel</div><small style="opacity:.8">v1.0</small></div>
        </div>

        <nav>

        <!-- TRANG CHÍNH -->
        <a class="nav-item " href="index.php?action=dashboard">
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

        <a class="nav-item active" href="index.php?action=schedule-assign">
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

        <!-- MAIN CONTENT -->
        <main class="main">
            <div class="topbar">
        <button class="btn btn-sm btn-outline-secondary d-md-none" id="btnToggle"><i class="bi bi-list"></i></button>
        <div class="me-2">VI</div>
        <div class="btn btn-light btn-sm"><i class="bi bi-bell"></i></div>
        <div class="dropdown" style="position:relative;">
<?php
    $avatar = $_SESSION['user_avatar'] ?? '';
    if (empty($avatar)) {
        $avatar = 'https://ui-avatars.com/api/?name=User&background=random';
    }
?>
          <img src="<?= htmlspecialchars($avatar) ?>"
            alt="Avatar"
            id="avatarBtn"
            style="width:50px;height:50px;border-radius:50%;cursor:pointer;object-fit:cover;border:2px solid #f5c542;"
            onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=User&background=random'"
            data-bs-toggle="dropdown"
            aria-expanded="false">
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="avatarBtn" style="min-width:150px;">
            <li><a class="dropdown-item" href="?action=profile">📋 Hồ sơ</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="?action=logout">🚪 Đăng xuất</a></li>
          </ul>
        </div>
      </div>
            <h3>Phân Bổ Lịch Khởi Hành</h3>

            <div class="card-panel mt-3">
                

            <div class="timeline-container">
        <div class="timeline-header">
            <?php 
            $currentYearBand = [];
            foreach ($columns as $i => $col) {
                if (!isset($currentYearBand[$col['year']])) {
                    $currentYearBand[$col['year']] = ['width' => 0];
                }
                $currentYearBand[$col['year']]['width'] += $col['width_percent']; 
            }
            
            // Dòng hiển thị Năm
            $yearBandHtml = '';
            foreach ($currentYearBand as $year => $data) {
                $yearBandWidth = round($data['width'], 2); 
                $yearBandHtml .= "<div class='year-band' style='width: {$yearBandWidth}%;'>Năm {$year}</div>";
            }
            echo "<div style='width:150px;' class='year-band'>Tên tour</div>" . $yearBandHtml;
            ?>
        </div>
        <div class="timeline-header" style="height:50px; background-color:#55523a;">
            <div style='width:150px;' class='month-band'></div>
            <?php foreach ($columns as $i => $col): ?>
                <div class="month-band" style="width: <?php echo $col['width_percent']; ?>%;"><?php echo $monthNames[$col['month'] - 1]; ?></div>
            <?php endforeach; ?>
        </div>
        
        <div class="timeline-grid" style="height: <?php 
            $maxRow = 0;
            if (!empty($departures) && is_array($departures)) {
                $rows = array_column($departures, 'style_row');
                if (!empty($rows)) {
                    $maxRow = max($rows);
                }
            }
            echo $rowHeight * ($maxRow + 2); 
        ?>px;">
            
            <?php 
            // Vị trí các đường kẻ chia tháng
            $cumulativeWidth = 0;
            foreach ($columns as $col) {
                $cumulativeWidth += $col['width_percent'];
                if ($cumulativeWidth < 100) {
                    echo "<div class='month-divider' style='left: calc({$cumulativeWidth}% + 150px);'></div>";
                }
            }
            
            // Tính toán vị trí đường chỉ ngày hiện tại
            if (!empty($todayVirtualLeft)) {
                echo "<div class='current-day-line' style='left: calc({$todayVirtualLeft}% + 150px);'>";
                echo "<span class='current-day-label'>Hôm nay</span>";
                echo "</div>";
            }
            ?>

            <?php foreach ($departures as $tour): ?>
                <?php
                $topPosition = $tour['style_row'] * $rowHeight;
                $tourName = htmlspecialchars($tour['tour_name']);
                $tourID = htmlspecialchars($tour['tour_id']);
                $dateRange = date('m-d', strtotime($tour['dateStart'])) . ' - ' . date('m-d', strtotime($tour['dateEnd']));
                $statusText = isset($tour['status_text']) ? $tour['status_text'] : '';
                $image = isset($tour['image']) && $tour['image'] ? $tour['image'] : '';
                ?>

                <div 
                    class="tour-item <?php echo $tour['status_class']; ?>" 
                    style="
                        left: calc(<?php echo $tour['style_left']; ?>% + 150px);
                        width: <?php echo $tour['style_width']; ?>%;
                        top: <?php echo $topPosition; ?>px;"
                    
                    title="<?php echo $tourName . ' (' . $dateRange . ') - ' . $statusText; ?>"
                >
                    <?php if ($image): ?>
                        <div class="tour-image" style="background-image: url('<?php echo $image; ?>');"></div>
                    <?php endif; ?>
                    <div class="tour-content">
                        <p><?php echo $dateRange; ?>
                            <?php if ($tour['status_class'] == 'ended'): ?>
                                <span class="status-ended">(ĐÃ KẾT THÚC)</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                
                <div class="tour-name-label" style="top: <?php echo $topPosition; ?>px; height: <?php echo $rowHeight; ?>px; line-height: <?php echo $rowHeight; ?>px; color: #888;">
                    <?php echo $tourName; ?>
                </div>

            <?php endforeach; ?>
        </div>
    </div>



            </div>
        </main>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html> 