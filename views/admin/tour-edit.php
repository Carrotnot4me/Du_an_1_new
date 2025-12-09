<?php
function formatDate($dateString)
{
  if (!$dateString) return '';
  return date('Y-m-d', strtotime($dateString));
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Chỉnh sửa Tour - <?php echo htmlspecialchars($tour->name ?? ''); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/list.css">
  <style>
    .info-section {
      background: #fffdfa;
      border-radius: 10px;
      padding: 22px;
      margin-bottom: 22px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
      border: 1px solid #f1e2b5;
    }

    .form-label {
      font-weight: 600;
      color: #7b5e2a;
      margin-bottom: 8px;
    }

    .required {
      color: red;
    }
  </style>
</head>

<body>
  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <div class="logo">
        <div style="width:44px;height:44px;background:#f5c542;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3b2a0a;font-weight:700">AD</div>
        <div>
          <div>AdminPanel</div><small style="opacity:.8">v1.0</small>
        </div>
      </div>
      <nav>
        <a class="nav-item" href="index.php?action=dashboard"><i class="bi bi-house-door-fill me-2"></i> Trang quản trị</a>
        <a class="nav-item active" href="index.php?action=tour-list"><i class="bi bi-airplane me-2"></i> Quản lý Tour</a>
        <a class="nav-item" href="index.php?action=booking-list"><i class="bi bi-calendar-check me-2"></i> Quản lý Booking</a>
        <a class="nav-item" href="index.php?action=customer-list"><i class="bi bi-people me-2"></i> Quản lý Khách hàng</a>
        <a class="nav-item" href="index.php?action=supplier-list"><i class="bi bi-building me-2"></i> Quản lý Nhà Cung Cấp</a>
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

      <div style="margin-bottom: 22px;">
        <h3 style="color:#4a3512; margin: 0;">Chỉnh sửa Tour: <?php echo htmlspecialchars($tour->name ?? ''); ?></h3>
      </div>

      <form id="editTourForm">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($tour->id ?? ''); ?>">

        <!-- Thông tin cơ bản -->
        <div class="info-section">
          <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h5>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Số lượng khách tối đa <span class="required">*</span></label>
              <input type="number" class="form-control" name="max_people"
                value="<?php echo htmlspecialchars($tour->max_people ?? ''); ?>"
                min="1" required>
            </div>
          </div>
        </div>

        <!-- Nhà cung cấp -->
        <div class="info-section">
          <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-shop me-2"></i>Nhà cung cấp</h5>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Khách sạn</label>
              <input type="text" class="form-control" name="hotel"
                value="<?php echo htmlspecialchars($tour->hotel ?? ''); ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Nhà hàng</label>
              <input type="text" class="form-control" name="restaurant"
                value="<?php echo htmlspecialchars($tour->restaurant ?? ''); ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Phương tiện vận chuyển</label>
              <input type="text" class="form-control" name="transport"
                value="<?php echo htmlspecialchars($tour->transport ?? ''); ?>">
            </div>
          </div>
        </div>

        <!-- Chuyến khởi hành -->
        <?php
        // Lấy departure đầu tiên (sắp tới nhất)
        $firstDeparture = !empty($tour->departures) ? $tour->departures[0] : null;
        ?>
        <?php if ($firstDeparture): ?>
          <div class="info-section">
            <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-airplane me-2"></i>Chuyến khởi hành</h5>

            <div class="departure-item mb-4" style="padding: 15px; background: #fff; border-radius: 8px; border: 1px solid #f1e2b5;">
              <input type="hidden" name="departure_id" value="<?php echo htmlspecialchars($firstDeparture->id ?? ''); ?>">

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Ngày khởi hành <span class="text-muted"></span></label>
                  <input type="date" class="form-control departure-date-start"
                    value="<?php echo formatDate($firstDeparture->dateStart ?? ''); ?>"
                    disabled style="background-color: #e9ecef;">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ngày kết thúc</label>
                  <input type="date" class="form-control" name="date_end"
                    value="<?php echo formatDate($firstDeparture->dateEnd ?? ''); ?>">
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-12">
                  <small class="text-muted">
                    <strong>Điểm hẹn:</strong> <?php echo htmlspecialchars($firstDeparture->meetingPoint ?? 'Chưa có'); ?> |
                    <strong>Hướng dẫn viên:</strong> <?php echo htmlspecialchars($firstDeparture->guide_name ?? 'Chưa chỉ định'); ?> |
                    <strong>Tài xế:</strong> <?php echo htmlspecialchars($firstDeparture->driver ?? 'Chưa có'); ?>
                  </small>
                </div>
              </div>

              <?php if (count($tour->departures) > 1): ?>
                <div class="alert alert-warning mt-3">
                  <i class="bi bi-info-circle me-2"></i>Tour này có <?php echo count($tour->departures); ?> chuyến khởi hành. Bạn đang chỉnh sửa chuyến đầu tiên.
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php else: ?>
          <div class="info-section">
            <h5 class="mb-3" style="color: #7b5e2a;"><i class="bi bi-airplane me-2"></i>Chuyến khởi hành</h5>
            <div class="alert alert-info">
              <i class="bi bi-info-circle me-2"></i>Tour này chưa có chuyến khởi hành nào.
            </div>
          </div>
        <?php endif; ?>

        <div style="margin-top: 22px; display: flex; gap: 10px;">
          <a href="index.php?action=tourDetail&id=<?php echo $tour->id; ?>" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Hủy
          </a>
          <button type="submit" class="btn btn-warning">
            <i class="bi bi-check-circle me-2"></i>Lưu thay đổi
          </button>
        </div>
      </form>
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('editTourForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(this);
      const data = {
        id: formData.get('id'),
        max_people: formData.get('max_people'),
        hotel: formData.get('hotel'),
        restaurant: formData.get('restaurant'),
        transport: formData.get('transport'),
        departure_id: formData.get('departure_id'),
        date_end: formData.get('date_end')
      };

      // Validate
      if (!data.max_people || parseInt(data.max_people) < 1) {
        alert('Vui lòng nhập số lượng khách hợp lệ');
        return;
      }

      // Validate ngày kết thúc phải sau ngày khởi hành (nếu có nhập)
      if (data.departure_id && data.date_end) {
        const dateStartInput = document.querySelector('.departure-date-start');
        if (dateStartInput && data.date_end <= dateStartInput.value) {
          alert('Ngày kết thúc phải sau ngày khởi hành');
          return;
        }
      }

      try {
        const response = await fetch('index.php?action=updateTour', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams(data)
        });

        const result = await response.json();

        if (result.success) {
          alert('Cập nhật tour thành công!');
          window.location.href = 'index.php?action=tourDetail&id=' + data.id;
        } else {
          alert('Có lỗi xảy ra khi cập nhật tour. Vui lòng thử lại.');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật tour. Vui lòng thử lại.');
      }
    });
  </script>
</body>

</html>