<?php
require_once './commons/function.php';
$conn = connectDB();

// Lấy dữ liệu
$tours = $conn->query("SELECT * FROM tours")->fetchAll();
$staffs = $conn->query("SELECT * FROM staffs")->fetchAll();
$departures = $conn->query("SELECT * FROM departures")->fetchAll();

// Xử lý form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $depId = $_POST['depId'] ?? null;
    $tourId = $_POST['tourId'] ?? null;
    $date = $_POST['date'] ?? null;
    $guideId = $_POST['guideId'] ?? null;
    $driverId = $_POST['driver'] ?? null;

    if (isset($_POST['delete']) && $depId) {
        // Xóa
        $stmt = $conn->prepare("DELETE FROM departures WHERE id=?");
        $stmt->execute([$depId]);
    } elseif ($depId) {
        // Sửa
        $stmt = $conn->prepare("UPDATE departures SET tourId=?, date=?, assignedHDV=?, assignedDriver=? WHERE id=?");
        $stmt->execute([$tourId, $date, $guideId, $driverId, $depId]);
    } else {
        // Thêm mới
        $stmt = $conn->prepare("INSERT INTO departures (tourId, date, assignedHDV, assignedDriver) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tourId, $date, $guideId, $driverId]);
    }
    header("Location: schedule-assign.php");
    exit;
}
?>

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
                <div
                    style="width:44px;height:44px;background:#f5c542;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3b2a0a;font-weight:700">
                    AD</div>
                <div>
                    <div>AdminPanel</div><small style="opacity:.8">v1.0</small>
                </div>
            </div>
            <nav>
                <div class="nav-item"><i class="bi bi-airplane"></i> Quản lý Tour</div>
                <div class="nav-item active"><i class="bi bi-calendar-check"></i> Đợt khởi hành</div>
                <div class="nav-item"><i class="bi bi-bookmark-check"></i> Đặt chỗ</div>
                <div class="nav-item"><i class="bi bi-people-fill"></i> Khách hàng</div>
                <div class="nav-item"><i class="bi bi-person-badge"></i><a href="hdv.php?id=1">Hướng dẫn viên</a></div>
            </nav>
            <div style="margin-top:auto;font-size:13px;opacity:.9">
                <div>Người dùng: <strong>Admin</strong></div>
                <div style="margin-top:6px">Email: <small>admin@example.com</small></div>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main">
            <div class="topbar">
                <button class="btn btn-sm btn-outline-secondary d-md-none" id="btnToggle"><i
                        class="bi bi-list"></i></button>
                <div class="me-2">VI</div>
                <div class="btn btn-light btn-sm"><i class="bi bi-bell"></i></div>
                <div class="rounded-circle bg-warning text-dark d-flex align-items:center;justify-content:center"
                    style="width:50px;height:50px;font-weight:600">A</div>
            </div>

            <h3 style="margin-bottom:22px;color:#4a3512;">Phân Bổ Lịch Khởi Hành</h3>

            <div class="grid">
                <div class="card-panel">
                    <h2 id="total-departures" style="float:left;">Số lịch hiện có: <?= count($departures) ?></h2>
                    <button class="btn btn-success" style="float:right;" data-bs-toggle="modal"
                        data-bs-target="#assignModal">
                        + Thêm mới
                    </button>
                    <div style="clear:both;"></div>
                </div>
            </div>

            <div style="margin-top:22px" class="card-panel">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tour</th>
                            <th>Ngày khởi hành</th>
                            <th>Ngày kết thúc</th>
                            <th>Điểm gặp</th>
                            <th>Ms-HDV</th>
                            <th>Tài xế</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($departures as $i => $d): ?>
                            <tr>
                                <th scope="row"><?= $i + 1 ?></th>

                                <!-- Tên tour -->
                                <td>
                                    <?php
                                    $idxTour = array_search($d['tourId'], array_column($tours, 'id'));
                                    echo $idxTour !== false ? $tours[$idxTour]['name'] : '--';
                                    ?>
                                </td>

                                <!-- Ngày khởi hành -->
                                <td><?= $d['dateStart'] ?? '--' ?></td>

                                <!-- Ngày kết thúc -->
                                <td><?= $d['dateEnd'] ?? '--' ?></td>

                                <!-- Điểm gặp -->
                                <td><?= $d['meetingPoint'] ?? '--' ?></td>

                                <!-- Mã HDV -->
                                <td>
                                    <?= $d['guideId'] ?? '--' ?>
                                </td>

                                <!-- Tài xế -->
                                <td><?= $d['driver'] ?></td> <!-- hiển thị tên trực tiếp -->

                                <!-- Hành động -->
                                <td>
                                    <form method="post" style="display:inline-block">
                                        <input type="hidden" name="depId" value="<?= $d['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">Sửa</button>
                                    </form>
                                    <form method="post" style="display:inline-block"
                                        onsubmit="return confirm('Xác nhận xóa?');">
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

    <!-- MODAL THÊM / SỬA LỊCH -->
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
                                    <?php foreach ($staffs as $s):
                                        if ($s['role'] == 'guide'): ?>
                                            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                        <?php endif; endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tài xế</label>
                                <select name="driverId" class="form-select" required>
                                    <option value="">-- Chọn tài xế --</option>
                                    <?php foreach ($staffs as $s):
                                        if ($s['role'] == 'driver'): ?>
                                            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
                                        <?php endif; endforeach; ?>
                                </select>
                            </div>
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