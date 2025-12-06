<?php
function formatCurrency($amount) {
    if ($amount === null || $amount === '') return '0đ';
    return number_format($amount, 0, ',', '.') . 'đ';
}

function formatDate($dateString) {
    if (!$dateString) return 'Chưa có';
    return date('d/m/Y', strtotime($dateString));
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Chi tiết Tour - <?php echo htmlspecialchars($tour->name ?? ''); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/list.css">
  <style>
    .tour-image {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 10px;
    }
    .info-section {
      background: #fffdfa;
      border-radius: 10px;
      padding: 22px;
      margin-bottom: 22px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      border: 1px solid #f1e2b5;
    }
    .info-item {
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #f1e2b5;
    }
    .info-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    .info-label {
      font-weight: 600;
      color: #7b5e2a;
      margin-bottom: 5px;
    }
    .info-value {
      color: #3c2e17;
    }
    .schedule-day {
      font-weight: 700;
      color: #f5c542;
      font-size: 18px;
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
        <a class="nav-item" href="index.php?action=dashboard"><i class="bi bi-house-door-fill me-2"></i> Trang quản trị</a>
        <a class="nav-item active" href="index.php?action=tour-list"><i class="bi bi-airplane me-2"></i> Quản lý Tour</a>
        <a class="nav-item" href="index.php?action=booking-list"><i class="bi bi-calendar-check me-2"></i> Quản lý Booking</a>
        <a class="nav-item" href="index.php?action=customer-list"><i class="bi bi-people me-2"></i> Quản lý Khách hàng</a>
        <a class="nav-item" href="index.php?action=guide-logs"><i class="bi bi-journal-text me-2"></i> Nhật ký Tour</a>
        <a class="nav-item" href="index.php?action=guide-special"><i class="bi bi-heart-pulse me-2"></i> Yêu cầu đặc biệt</a>
        <a class="nav-item" href="index.php?action=special-notes"><i class="bi bi-sticky me-2"></i> Ghi chú đặc biệt</a>
        <a class="nav-item" href="index.php?action=revenue-report"><i class="bi bi-currency-dollar me-2"></i> Báo cáo Doanh thu</a>
    </nav>
    <div style="margin-top:auto;font-size:13px;opacity:.9">
        <div>Người dùng: <strong>Admin</strong></div>
        <div style="margin-top:6px">Email: <small>admin@example.com</small></div>
    </div>
</aside>

  <!-- MAIN -->
  <main class="main">
    <div class="topbar">
      <button class="btn btn-sm btn-outline-secondary d-md-none" id="btnToggle"><i class="bi bi-list"></i></button>
      <div class="me-2">VI</div>
      <div class="btn btn-light btn-sm"><i class="bi bi-bell"></i></div>
      <div class="rounded-circle bg-warning text-dark d-flex align-items:center;justify-content:center" style="width:50px;height:50px;font-weight:600">A</div>
    </div>

    <div style="margin-bottom: 22px; display: flex; justify-content: space-between; align-items: center;">
      <h3 style="color:#4a3512; margin: 0;">Chi tiết Tour: <?php echo htmlspecialchars($tour->name ?? ''); ?></h3>
      <a href="index.php?action=editTour&id=<?php echo $tour->id; ?>" class="btn btn-warning">
        <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa Tour
      </a>
    </div>

    <!-- Hình ảnh Tour -->
    <?php if (!empty($tour->images)): ?>
    <div class="info-section">
      <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-images me-2"></i>Hình ảnh Tour</h5>
      <div class="row g-3">
        <?php foreach ($tour->images as $image): ?>
        <div class="col-md-4">
          <img src="<?php echo htmlspecialchars($image); ?>" alt="Tour image" class="tour-image" onerror="this.src='/assets/placeholder.jpg'">
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Thông tin cơ bản -->
    <div class="info-section">
      <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h5>
      <div class="row">
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">Tên Tour</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->name ?? 'N/A'); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Loại Tour</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->type ?? 'N/A'); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Mã Tour</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->tour_code ?? 'N/A'); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Điểm đến chính</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->main_destination ?? 'N/A'); ?></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-item">
            <div class="info-label">Số lượng khách tối đa</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->max_people ?? 'N/A'); ?> người</div>
          </div>
          <div class="info-item">
            <div class="info-label">Giá người lớn</div>
            <div class="info-value"><?php echo formatCurrency($tour->adult_price ?? 0); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Giá trẻ em</div>
            <div class="info-value"><?php echo formatCurrency($tour->child_price ?? 0); ?></div>
          </div>
        </div>
      </div>
      <?php if (!empty($tour->short_description)): ?>
      <div class="info-item">
        <div class="info-label">Mô tả ngắn</div>
        <div class="info-value"><?php echo nl2br(htmlspecialchars($tour->short_description)); ?></div>
      </div>
      <?php endif; ?>
    </div>

    <!-- Nhà cung cấp -->
    <?php if ($tour->hotel || $tour->restaurant || $tour->transport): ?>
    <div class="info-section">
      <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-shop me-2"></i>Nhà cung cấp</h5>
      <div class="row">
        <div class="col-md-4">
          <div class="info-item">
            <div class="info-label">Khách sạn</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->hotel ?? 'Chưa có'); ?></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-item">
            <div class="info-label">Nhà hàng</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->restaurant ?? 'Chưa có'); ?></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="info-item">
            <div class="info-label">Phương tiện vận chuyển</div>
            <div class="info-value"><?php echo htmlspecialchars($tour->transport ?? 'Chưa có'); ?></div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Lịch trình -->
    <?php if (!empty($tour->schedules)): ?>
    <div class="info-section">
      <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-calendar-event me-2"></i>Lịch trình</h5>
      <?php foreach ($tour->schedules as $schedule): ?>
      <div class="info-item">
        <div class="schedule-day">Ngày <?php echo htmlspecialchars($schedule->day); ?></div>
        <div class="info-value mt-2"><?php echo nl2br(htmlspecialchars($schedule->activity)); ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Chuyến khởi hành -->
    <?php if (!empty($tour->departures)): ?>
    <div class="info-section">
      <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-airplane me-2"></i>Chuyến khởi hành</h5>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-warning">
            <tr>
              <th>Ngày khởi hành</th>
              <th>Ngày kết thúc</th>
              <th>Điểm hẹn</th>
              <th>Hướng dẫn viên</th>
              <th>Tài xế</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tour->departures as $departure): ?>
            <tr>
              <td><?php echo formatDate($departure->dateStart); ?></td>
              <td><?php echo formatDate($departure->dateEnd); ?></td>
              <td><?php echo htmlspecialchars($departure->meetingPoint ?? 'Chưa có'); ?></td>
              <td><?php echo htmlspecialchars($departure->guide_name ?? 'Chưa chỉ định'); ?></td>
              <td><?php echo htmlspecialchars($departure->driver ?? 'Chưa có'); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- Chính sách -->
    <?php if (!empty($tour->cancel_policy) || !empty($tour->refund_policy)): ?>
    <div class="info-section">
      <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-file-text me-2"></i>Chính sách</h5>
      <?php if (!empty($tour->cancel_policy)): ?>
      <div class="info-item">
        <div class="info-label">Chính sách hủy</div>
        <div class="info-value"><?php echo nl2br(htmlspecialchars($tour->cancel_policy)); ?></div>
      </div>
      <?php endif; ?>
      <?php if (!empty($tour->refund_policy)): ?>
      <div class="info-item">
        <div class="info-label">Chính sách hoàn tiền</div>
        <div class="info-value"><?php echo nl2br(htmlspecialchars($tour->refund_policy)); ?></div>
      </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <div style="margin-top: 22px; display: flex; gap: 10px;">
      <a href="index.php?action=tour-list" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
      </a>
      <a href="index.php?action=editTour&id=<?php echo $tour->id; ?>" class="btn btn-warning">
        <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa Tour
      </a>
    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

