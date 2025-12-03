<?php
require_once '../../models/TourModel.php'; // đường dẫn tới model Tour của m

if (!isset($_GET['id'])) {
    die('ID tour không tồn tại');
}

$id = intval($_GET['id']); // bảo vệ kiểu số
$tourModel = new TourModel();
$tour = $tourModel->getTourById($id);

if (!$tour) {
    die('Tour không tồn tại');
}

// Xử lý POST khi submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'type' => $_POST['type'],
        'main_destination' => $_POST['main_destination'],
        // các trường khác cũng lấy từ $_POST
    ];

    $updated = $tourModel->updateTour($id, $data);
    if ($updated) {
        header("Location: ../../views/admin/tourlist.php"); // quay lại trang danh sách
        exit;
    } else {
        $error = "Cập nhật thất bại";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <link rel="stylesheet" href="/assets/list.css">
</head>

<body>
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
            <div class="nav-item active"><i class="bi bi-airplane"></i> Quản lý Tour</div>
            <div class="nav-item"><i class="bi bi-calendar-check"></i> Đợt khởi hành</div>
            <div class="nav-item"><i class="bi bi-bookmark-check"></i> Đặt chỗ</div>
            <div class="nav-item"><i class="bi bi-people-fill"></i> Khách hàng</div>
            <div class="nav-item"><i class="bi bi-person-badge"></i> Hướng dẫn viên</div>
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

        <h3 style="margin-bottom:22px;color:#4a3512;">Danh sách Tour</h3>
        <div class="grid">
            <div class="card-panel">
                <h2 id="total-tours" style="float:left;">Số tour hiện có: 0</h2>
                <button class="btn btn-success" style="float:right;" data-bs-toggle="modal"
                    data-bs-target="#addTourModal">+
                    Thêm mới</button>
                <div style="clear:both;"></div>
            </div>
        </div>

        <div style="margin-top:22px" class="card-panel">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Loại Tour</th>
                        <th>Tên Tour</th>
                        <th>Điểm đến</th>
                        <th>Hình ảnh</th>
                        <th>Mã tour</th>
                        <th>Giá cả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </main>
    </div>

    <form method="POST" id="editTourForm">
    <label>Tên Tour:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($tour['name']) ?>" class="form-control">

    <label>Loại Tour:</label>
    <select name="type" class="form-control">
        <option value="Nội địa" <?= $tour['type'] == 'Nội địa' ? 'selected' : '' ?>>Nội địa</option>
        <option value="Nước ngoài" <?= $tour['type'] == 'Nước ngoài' ? 'selected' : '' ?>>Nước ngoài</option>
    </select>

    <label>Điểm đến:</label>
    <input type="text" name="main_destination" value="<?= htmlspecialchars($tour['main_destination']) ?>" class="form-control">

    <!-- các input khác tương tự -->

    <button type="submit" class="btn btn-success">Lưu thay đổi</button>
</form>


</body>




</html>

<script>
    // Đặt đoạn code này vào file <script> trong edittour.html
    const getUrlParameter = (name) => {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    const tourId = getUrlParameter('id');
    console.log('Tour ID cần sửa:', tourId);

    if (tourId) {
        // Gọi hàm load dữ liệu của tour
        loadTourData(tourId);
    }
</script>