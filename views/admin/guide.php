<?php
// Kết nối CSDL
require_once './commons/function.php';
$conn = connectDB();

// Lấy dữ liệu từ database
$tours = $conn->query("SELECT * FROM tours")->fetchAll();
$staffs = $conn->query("SELECT * FROM staffs")->fetchAll();
$departures = $conn->query("SELECT * FROM departures")->fetchAll();

// Lọc ra hướng dẫn viên, chuẩn hóa chữ hoa/chữ thường và loại bỏ khoảng trắng
$guides = array_filter($staffs, function($s) {
    $type = strtolower(trim($s['type'])); // chuẩn hóa
    return $type === 'guide' || $type === 'quoc_te' || $type === 'noi_dia';
});

// Reset key mảng 
$guides = array_values($guides);
?>



<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Danh sách Hướng dẫn viên</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/guide.css">
  <style>
    
</style>
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
        <div class="nav-item"><i class="bi bi-calendar-check"></i> Đợt khởi hành</div>
        <div class="nav-item"><i class="bi bi-bookmark-check"></i> Đặt chỗ</div>
        <div class="nav-item"><i class="bi bi-people-fill"></i> Khách hàng</div>
        <div class="nav-item active"><i class="bi bi-person-badge"></i> Hướng dẫn viên</div>
      </nav>
      <div style="margin-top:auto;font-size:13px;opacity:.9">
        <div>Người dùng: <strong>Admin</strong></div>
        <div style="margin-top:6px">Email: <small>admin@example.com</small></div>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="app">

      <!-- MAIN CONTENT -->
      <main class="container py-4">
    <h3 class="mb-4">Danh sách Hướng dẫn viên</h3>

    <div class="d-flex mb-4">
        <div class="dropdown me-2">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Tất cả trạng thái
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Đang trống lịch</a></li>
                <li><a class="dropdown-item" href="#">Đang dẫn tour</a></li>
            </ul>
        </div>
        <input type="text" class="form-control me-2" placeholder="Tìm theo tên HDV...">
        <button class="btn btn-primary" style="min-width: 100px;">Tìm kiếm</button>
    </div>
 
<div class="row">
    <?php foreach ($guides as $g): ?>
        <?php
        // Trích xuất dữ liệu và logic trạng thái
        $toursLed = $g['toursLed'] ?? 0;
        $isBusy = $toursLed > 0;
        $statusText = $isBusy ? 'Đang dẫn tour' : 'Đang trống lịch';
        $statusClass = $isBusy ? 'status-busy' : 'status-available';
        
        // Dùng ID để liên kết nút với Modal tương ứng
        $modalId = 'guideDetailModal' . $g['id']; 
        ?>

        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card-guide new-style">
                <div class="guide-info">
                    <div class="guide-name">
                        <strong><?= $g['name'] ?></strong> 
                    </div>

                    <p>SĐT: <span class="phone-number"><?= $g['phone'] ?></span></p>
                    <p>Số tour đã dẫn: **<?= $toursLed ?>**</p>
                    <p>Trạng thái: <span class="badge <?= $statusClass ?>"><?= $statusText ?></span></p>
                </div>

                <div class="guide-actions">
                    <?php if (!$isBusy): ?>
                        <button class="btn btn-sm btn-primary">Phân lịch</button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>Không thể phân lịch</button>
                    <?php endif; ?>
                    
                    <button class="btn btn-sm btn-outline-info mt-1" 
                            data-bs-toggle="modal" 
                            data-bs-target="#<?= $modalId ?>">
                        Chi tiết
                    </button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalId ?>Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?= $modalId ?>Label">Chi tiết Hướng dẫn viên: <?= $g['name'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ID:</strong> <?= $g['id'] ?></p>
                        <p><strong>Giới tính:</strong> <?= $g['sex'] ?></p>
                        <p><strong>Email:</strong> <?= $g['email'] ?></p>
                        <p><strong>Phone:</strong> <?= $g['phone'] ?></p>
                        <p><strong>Loại:</strong> <?= $g['type'] ?></p>
                        <p><strong>Chứng chỉ:</strong> <?= $g['certificate'] ?></p>
                        <p><strong>Kinh nghiệm:</strong> <?= $g['experience'] ?> năm</p>
                        <p><strong>Sức khỏe:</strong> <?= $g['health'] ?></p>
                        <p><strong>Số tour đã dẫn:</strong> <?= $toursLed ?></p>
                        <p><strong>Đánh giá:</strong> <?= $g['rating'] ?>/5</p>
                        <p><strong>Trạng thái:</strong> <span class="badge <?= $statusClass ?>"><?= $statusText ?></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
</div>
</main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>