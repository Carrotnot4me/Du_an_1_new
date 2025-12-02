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
        <a class="nav-item" href="index.php?action=dashboard"><i class="bi bi-house-door-fill me-2"></i> Trang quản trị</a>
        <a class="nav-item" href="index.php?action=tour-list"><i class="bi bi-airplane me-2"></i> Quản lý Tour</a>
        <a class="nav-item active" href="index.php?action=booking-list"><i class="bi bi-calendar-check me-2"></i> Quản lý Booking</a>
        <a class="nav-item" href="index.php?action=customer-list"><i class="bi bi-people me-2"></i> Quản lý Khách hàng</a>
        <a class="nav-item" href="index.php?action=guide-logs"><i class="bi bi-journal-text me-2"></i> Nhật ký Tour</a>
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

    <h3 style="margin-bottom:22px;color:#4a3512;">Quản lý Booking & Trạng thái</h3>
    <div class="grid">
      <div class="card-panel">
        <h2 style="float:left;">Tổng số booking: <?= count($bookings ?? []) ?></h2>
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
            'Đã hủy' => 'danger'
        ];
        $color = $colors[$status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$status}</span>";
    }
    ?>

    <div style="margin-top:22px" class="card-panel">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th>
            <th>Mã Booking</th>
            <th>Tour</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Số lượng</th>
            <th>Ngày khởi hành</th>
            <th>Trạng thái</th>
            <th>Tổng tiền</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($bookings)): ?>
            <tr>
              <td colspan="10" class="text-center text-muted">Chưa có booking nào</td>
            </tr>
          <?php else: ?>
            <?php foreach ($bookings as $index => $booking): ?>
              <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= htmlspecialchars($booking->id ?? '') ?></td>
                <td><?= htmlspecialchars($booking->tour_name ?? '') ?> <small class="text-muted">(<?= htmlspecialchars($booking->tour_type ?? '') ?>)</small></td>
                <td><?= htmlspecialchars($booking->email ?? '') ?></td>
                <td><?= htmlspecialchars($booking->phone ?? '') ?></td>
                <td><?= $booking->quantity ?? 0 ?></td>
                <td><?= formatDate($booking->departureDate ?? null) ?></td>
                <td><?= getStatusBadge($booking->status ?? '') ?></td>
                <td><?= formatCurrency($booking->total_amount ?? 0) ?></td>
                <td>
                  <button onclick="openUpdateStatusModal('<?= htmlspecialchars($booking->id) ?>', '<?= htmlspecialchars($booking->status ?? '') ?>')" 
                          class="btn btn-sm btn-primary" title="Cập nhật trạng thái">
                    <i class="bi bi-pencil"></i>
                  </button>
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
    const res = await fetch('api/booking-status.php', {
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

