<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Phân Bổ Lịch Khởi Hành</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/list.css">
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
            <a class="nav-item active" href="index.php?action=dashboard">
                <i class="bi bi-house-door-fill me-2"></i> Trang quản trị
            </a>

            <!-- QUẢN LÝ TOUR -->
            <div class="nav-group">QUẢN LÝ TOUR</div>

            <a class="nav-item" href="index.php?action=tour-list">
                <i class="bi bi-airplane me-2"></i> Danh sách Tour
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
            <div class="nav-group">HƯỚNG DẪN VIÊN</div>

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
            <div class="nav-group">BÁO CÁO</div>

            <a class="nav-item" href="index.php?action=revenue-report">
                <i class="bi bi-currency-dollar me-2"></i> Doanh thu
            </a>

            <!-- KHÁC -->
            <div class="nav-group">KHÁC</div>

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

        <a href="index.php?action=logout" class="btn btn-danger mt-3">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </aside>

        <!-- MAIN CONTENT -->
        <main class="main">
            <h3>Phân Bổ Lịch Khởi Hành</h3>

            <div class="grid">
                <div class="card-panel">
                    <h2 id="total-departures">Số lịch hiện có: <?= count($departures) ?></h2>
                    <button class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#assignModal">+
                        Thêm mới</button>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="card-panel mt-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tour</th>
                            <th>Ngày khởi hành</th>
                            <th>Ngày kết thúc</th>
                            <th>Điểm gặp</th>
                            <th>HDV</th>
                            <th>Tài xế</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($departures as $i => $d): ?>
                            <tr>
                                <th><?= $i + 1 ?></th>
                                <td>
                                    <?php
                                    $idxTour = array_search($d['tourId'], array_column($tours, 'id'));
                                    echo $idxTour !== false ? $tours[$idxTour]['name'] : '--';
                                    ?>
                                </td>
                                <td><?= $d['dateStart'] ?? '--' ?></td>
                                <td><?= $d['dateEnd'] ?? '--' ?></td>
                                <td><?= $d['meetingPoint'] ?? '--' ?></td>
                                <td>
                                    <?php
                                    $idxGuide = array_search($d['guideId'], array_column($guides, 'id'));
                                    echo $idxGuide !== false ? $guides[$idxGuide]['name'] : '--';
                                    ?>
                                </td>
                                <td>
                                    <?= $d['driver'] ?? '--' ?>
                                </td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="depId" value="<?= $d['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">Sửa</button>
                                    </form>
                                    <form method="post" class="d-inline" onsubmit="return confirm('Xác nhận xóa?');">
                                        <input type="hidden" name="depId" value="<?= $d['id'] ?>">
                                        <input type="hidden" name="delete" value="1">
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- MODAL THÊM / SỬA -->
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo / Sửa Lịch Khởi Hành</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="depId" value="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tour</label>
                                <select name="tourId" class="form-select" required>
                                    <option value="">-- Chọn tour --</option>
                                    <?php foreach ($tours as $t): ?>
                                        <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày khởi hành</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">HDV</label>
                                <select name="guideId" class="form-select" required>
                                    <option value="">-- Chọn HDV --</option>
                                    <?php foreach ($guides as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tài xế</label>
                                <select name="driver" class="form-select" required>
                                    <option value="">-- Chọn tài xế --</option>
                                    <?php foreach ($staffs as $s):
                                        $type = strtolower(trim($s['type']));
                                        if ($type === 'driver'): ?>
                                            <option value="<?= $s['name'] ?>"><?= $s['name'] ?></option>
                                        <?php endif;
                                    endforeach; ?>
                                </select>
                            </div>
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-success">Lưu</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>