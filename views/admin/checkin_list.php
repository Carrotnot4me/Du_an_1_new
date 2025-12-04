<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quy trình Check-in Tour</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./assets/list.css">

</head>

<body>
    <div class="app">

        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <div
                    style="width:44px;height:44px;background:#f5c542;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3b2a0a;font-weight:700">
                    AD</div>
                <div>
                    <div>AdminPanel</div><small style="opacity:.8">v1.0</small>
                </div>
            </div>
            <nav>
                <a class="nav-item " href="index.php?action=dashboard"><i class="bi bi-house-door-fill me-2"></i> Trang
                    quản trị</a>

                <a class="nav-item " href="index.php?action=tour-list"><i class="bi bi-airplane me-2"></i> Quản lý
                    Tour</a>
                
                <a class="nav-item " href="index.php?action=guide-logs"><i class="bi bi-journal-text me-2"></i> Nhật ký Tour</a>
                <a class="nav-item" href="index.php?action=booking-list"><i class="bi bi-caendar-clheck me-2"></i> Quản lý Booking</a>

                <a class="nav-item active " href="index.php?action=checkin"><i
                        class="bi bi-calendar-check-fill me-2"></i> Quy trình Check-in</a>

                <a class="nav-item " href="index.php?action=revenue-report"><i class="bi bi-currency-dollar me-2"></i>
                    Báo cáo Doanh thu</a>
                    <a class="nav-item" href="index.php?action=guide-special"><i class="bi bi-heart-pulse me-2"></i> Yêu cầu đặc biệt</a>
                    <a class="nav-item" href="index.php?action=special-notes"><i class="bi bi-sticky me-2"></i> Ghi chú </a>

                <a class="nav-item" href="index.php?action=guides"><i class="bi bi-person-badge-fill me-2"></i> Hướng
                    dẫn viên</a>

                <a class="nav-item" href="index.php?action=schedule-assign"><i
                        class="bi bi-calendar-event-fill me-2"></i> Phân công lịch Tour</a>

                <a class="nav-item" href="index.php?action=guide-schedule"><i class="bi bi-list-check me-2"></i> Lịch
                    theo Hướng dẫn viên</a>
            </nav>

            <div style="margin-top:auto;font-size:13px;opacity:.9">
                <div>Người dùng: <strong><?php echo htmlspecialchars($user['username'] ?? 'Admin'); ?></strong></div>
                <div style="margin-top:6px">Email:
                    <small><?php echo htmlspecialchars($user['email'] ?? 'admin@example.com'); ?></small></div>
            </div>
            <a href="index.php?action=logout" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </aside>

        <main class="main flex-grow-1 p-4">
            <h2 class="mb-4 text-dark"><i class="bi bi-calendar-check-fill me-2"></i> Quy trình Check-in Tour</h2>

            <div id="message_area">
                <?php echo $message; ?>
            </div>

            <div class="p-4 bg-white rounded shadow-sm mb-4">
                <form method="GET" class="d-flex" action="index.php">
                    <input type="hidden" name="action" value="checkin">
                    <input type="hidden" name="sub_action" value="list">
                    <input type="text" name="keyword" class="form-control me-2"
                        placeholder="Tìm kiếm theo Tên Tour, Code Tour, hoặc ID Booking"
                        value="<?php echo htmlspecialchars($keyword); ?>">
                    <button type="submit" class="btn btn-info"><i class="bi bi-search"></i> Tìm kiếm</button>
                    <a href="index.php?action=checkin" class="btn btn-secondary ms-2">Xem tất cả</a>
                </form>
            </div>

            <h4 class="mt-4 mb-3 text-dark">Danh sách Booking cần Check-in</h4>
            <div class="table-responsive bg-white rounded shadow-sm">
                <table class="table table-hover mb-0">
                    <thead class="table-warning text-dark">
                        <tr>
                            <th class="text-center text-nowrap">ID Booking</th>
                            <th class="text-start text-nowrap">Tour (Code)</th>
                            <th class="text-center text-nowrap">Ngày khởi hành</th>
                            <th class="text-center text-nowrap">Số lượng</th>
                            <th class="text-start text-nowrap">Email KH</th>
                            <th class="text-center text-nowrap">SĐT KH</th>
                            <th class="text-center text-nowrap">Trạng thái Check-in</th>
                            <th class="text-center text-nowrap">Thao tác</th>
                        </tr>
                    </thead>
                    <div class="table-scroll-body">
                        <tbody id="checkinListBody">
                            <?php if (!empty($bookings)): ?>
                                <?php foreach ($bookings as $booking):
                                    $is_checked_in = $booking['is_checked_in'] ?? 0;
                                    $checkin_time = $booking['checkin_time'];
                                    $booking_id = htmlspecialchars($booking['booking_id']);
                                    ?>
                                    <tr>
                                        <td class="text-center"><strong><?php echo $booking_id; ?></strong></td>
                                        <td class="text-start text-truncate" style="max-width: 180px;">
                                            <?php echo htmlspecialchars($booking['tour_name']) . ' (' . htmlspecialchars($booking['tour_code']) . ')'; ?>
                                        </td>
                                        <td class="text-center">
                                            <strong><?php echo date('d-m-Y', strtotime($booking['departureDate'])); ?></strong>
                                        </td>
                                        <td class="text-center"><?php echo $booking['quantity']; ?></td>
                                        <td class="text-start text-truncate" style="max-width: 150px;">
                                            <?php echo htmlspecialchars($booking['customer_email']); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($booking['customer_phone']); ?></td>
                                        <td class="text-center">
                                            <?php if ($is_checked_in): ?>
                                                <span class="badge bg-success">Đã Check-in</span>
                                                <small
                                                    class="d-block text-muted"><?php echo date('H:i d-m', strtotime($checkin_time)); ?></small>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Chưa Check-in</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!$is_checked_in): ?>
                                                <a href="index.php?action=checkin&sub_action=checkin&booking_id=<?php echo $booking_id; ?>"
                                                    onclick="return confirm('Xác nhận Check-in cho Booking ID <?php echo $booking_id; ?>?');"
                                                    class="btn btn-sm btn-primary text-nowrap">Check-in Ngay</a>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-secondary" disabled>Hoàn tất</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted p-3">Không tìm thấy Booking cần Check-in
                                        nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </div>
                </table>
            </div>
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>