<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Quản lý Booking</title>
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
        <a class="nav-item" href="index.php?action=dashboard">
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

        <a class="nav-item active" href="index.php?action=booking-list">
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

  <!-- MAIN -->
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
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item text-danger" href="?action=logout">🚪 Đăng xuất</a></li>
          </ul>
        </div>
      </div>

    <h3 style="margin-bottom:22px;color:#4a3512;">Quản lý Booking & Trạng thái</h3>
    <div class="grid">
      <div class="card-panel">
          <h2 style="float:left;">Tổng số booking: <?= htmlspecialchars($bookingsCount ?? 0) ?></h2>
          <a href="index.php?action=booking-add" class="btn btn-primary" style="float:right;">+ Tạo booking</a>
          <div style="clear:both;"></div>
        </div>
    </div>

    <?php
    function formatCurrency($amount) {
        if (!$amount) return '0đ';
        return number_format($amount, 0, ',', '.') . 'đ';
    }
    
    function formatDate($dateString) {
        if (!$dateString) return '';
        return date('d/m/Y', strtotime($dateString));
    }
    
    function getStatusBadge($status) {
        $colors = [
            'Đang xử lý' => 'warning',
            'Đã xác nhận' => 'success',
            'Đã cọc' => 'info',
            'Hoàn thành' => 'success',
        'Đã hủy' => 'danger',
        'Sắp đi' => 'info',
        'Đang đi' => 'success',
        'Đã kết thúc' => 'secondary'
        ];
        $color = $colors[$status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$status}</span>";
    }
    ?>

    <div style="margin-top:22px" class="card-panel">
      <table class="table">
        <thead>
          <tr>
            <th>STT</th>
            <th>Loại Tour</th>
            <th>Tên Tour</th>
            <th>Ngày khởi hành</th>
            <th>Hình ảnh</th>
            <th>Số lượng</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody id="tourTableBody">
          <?php if (empty($tours) || !is_array($tours)): ?>
            <tr>
              <td colspan="7" style="text-align:center;color:#999;">Không có tour nào</td>
            </tr>
          <?php else: ?>
            <?php foreach ($tours as $i => $t): ?>
              <?php
                // $t may be associative array from getTourSummaries()
                $img = $t['image_url'] ?? '';
                $totalQty = $t['total_quantity'] ?? 0;
                $statuses = $t['statuses'] ?? '';
              ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($t['type'] ?? '') ?></td>
                <td><?= htmlspecialchars($t['name'] ?? '') ?></td>
                <td>
                  <?php
                    $ds = $t['departure_start'] ?? $t['departureDate'] ?? null;
                    $de = $t['departure_end'] ?? null;
                    if ($ds) {
                      echo htmlspecialchars(date('d/m/Y', strtotime($ds)));
                      if ($de) echo ' - ' . htmlspecialchars(date('d/m/Y', strtotime($de)));
                    } else {
                      echo '-';
                    }
                  ?>
                </td>
                <td><?= $img ? '<img src="' . htmlspecialchars($img) . '" style="width:80px;height:50px;object-fit:cover;border-radius:4px;">' : '—' ?></td>
                <td>
                  <?php
                    $max = isset($t['max_people']) ? (int)$t['max_people'] : 0;
                    if ($max > 0) {
                      echo (int)$totalQty . ' / ' . $max;
                    } else {
                      echo (int)$totalQty;
                    }
                  ?>
                </td>
                <td>
                  <?= htmlspecialchars($t['computed_status'] ?? $statuses ?? '-') ?>
                </td>
                <td>
                  <?php
                    // Prefer departure_id when present (summary grouped by departure)
                    $depId = $t['departure_id'] ?? null;
                    $tourId = $t['tour_id'] ?? ($t['id'] ?? null);
                  ?>
                  <?php if ($depId): ?>
                    <a href="index.php?action=booking-detail&departure_id=<?= urlencode($depId) ?>" class="btn btn-sm btn-info me-1" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
                  <?php elseif ($tourId): ?>
                    <a href="index.php?action=booking-detail&tour_id=<?= urlencode($tourId) ?>" class="btn btn-sm btn-info me-1" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
                  <?php else: ?>
                    <a href="index.php?action=booking-list" class="btn btn-sm btn-info me-1" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
                  <?php endif; ?>
                  <form method="POST" action="index.php?action=deleteBooking" style="display:inline">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($linkId) ?>">
                    <input type="hidden" name="mode" value="<?= $depId ? 'by_departure' : 'by_tour' ?>">
                    <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Bạn muốn xóa tất cả booking cho mục này?')"><i class="bi bi-trash3"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- MODAL CẬP NHẬT TRẠNG THÁI -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cập nhật trạng thái booking</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="updateStatusForm">
          <input type="hidden" id="bookingId" name="bookingId">
          <div class="mb-3">
            <label for="bookingStatus" class="form-label">Trạng thái</label>
            <select class="form-select" id="bookingStatus" name="status" required>
              <option value="">-- Chọn trạng thái --</option>
              <?php foreach ($statuses ?? [] as $status): ?>
                <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Toggle sidebar
document.addEventListener('DOMContentLoaded', () => {
  const btnToggle = document.getElementById('btnToggle');
  const sidebar = document.getElementById('sidebar');
  if (btnToggle) {
    btnToggle.addEventListener('click', () => {
      sidebar.classList.toggle('active');
    });
  }
});

// Mở modal cập nhật trạng thái
function openUpdateStatusModal(bookingId, currentStatus) {
  document.getElementById('bookingId').value = bookingId;
  document.getElementById('bookingStatus').value = currentStatus;
  const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
  modal.show();
}

// Cập nhật trạng thái
document.getElementById('updateStatusForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  const bookingId = document.getElementById('bookingId').value;
  const status = document.getElementById('bookingStatus').value;
  
  if (!bookingId || !status) {
    alert('Vui lòng chọn trạng thái');
    return;
  }
  
  try {
    const res = await fetch('index.php?action=updateBookingStatus', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: bookingId, status: status })
    });
    
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    
    const result = await res.json();
    
    if (result.success) {
      alert('Cập nhật trạng thái thành công!');
      bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
      window.location.reload();
    } else {
      alert(result.message || 'Có lỗi xảy ra khi cập nhật trạng thái');
    }
  } catch (err) {
    console.error('Error:', err);
    alert('Có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại.');
  }
});
</script>
</body>
</html>

