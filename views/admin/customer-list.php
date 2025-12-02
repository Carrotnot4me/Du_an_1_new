<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Quản lý Khách hàng</title>
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
        <a class="nav-item" href="index.php?action=booking-list"><i class="bi bi-calendar-check me-2"></i> Quản lý Booking</a>
        <a class="nav-item active" href="index.php?action=customer-list"><i class="bi bi-people me-2"></i> Quản lý Khách hàng</a>
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

    <?php
    function formatCurrency($amount) {
        if (!$amount) return '0đ';
        return number_format($amount, 0, ',', '.') . 'đ';
    }
    
    function formatDate($dateString) {
        if (!$dateString) return '';
        return date('d/m/Y', strtotime($dateString));
    }
    ?>

    <h3 style="margin-bottom:22px;color:#4a3512;">Danh sách Khách hàng</h3>
    <div class="grid">
      <div class="card-panel">
        <h2 style="float:left;">Tổng số khách hàng: <?= count($customers ?? []) ?></h2>
        <div style="clear:both;"></div>
      </div>
    </div>

    <div style="margin-top:22px" class="card-panel">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Tổng số booking</th>
            <th>Tổng chi tiêu</th>
            <th>Booking đầu tiên</th>
            <th>Booking gần nhất</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($customers)): ?>
            <tr>
              <td colspan="8" class="text-center text-muted">Chưa có khách hàng nào</td>
            </tr>
          <?php else: ?>
            <?php foreach ($customers as $index => $customer): ?>
              <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= htmlspecialchars($customer->email ?? '') ?></td>
                <td><?= htmlspecialchars($customer->phone ?? '') ?></td>
                <td><span class="badge bg-primary"><?= $customer->total_bookings ?? 0 ?></span></td>
                <td><?= formatCurrency($customer->total_spent ?? 0) ?></td>
                <td><?= formatDate($customer->first_booking_date ?? null) ?></td>
                <td><?= formatDate($customer->last_booking_date ?? null) ?></td>
                <td>
                  <button onclick="viewCustomerDetail('<?= htmlspecialchars($customer->email) ?>')" 
                          class="btn btn-sm btn-info" title="Xem chi tiết">
                    <i class="bi bi-eye"></i> Chi tiết
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

<!-- MODAL CHI TIẾT KHÁCH HÀNG -->
<div class="modal fade" id="customerDetailModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết Khách hàng</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="customerDetailContent">
          <div class="text-center">
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
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

// Xem chi tiết khách hàng
async function viewCustomerDetail(email) {
  if (!email) {
    alert('Email không hợp lệ');
    return;
  }
  
  try {
    const res = await fetch(`api/customer-list.php?action=detail&email=${encodeURIComponent(email)}`);
    
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    
    const data = await res.json();
    
    if (data.customer) {
      renderCustomerDetail(data);
      const modal = new bootstrap.Modal(document.getElementById('customerDetailModal'));
      modal.show();
    } else {
      alert('Không tìm thấy thông tin khách hàng với email: ' + email);
    }
  } catch (err) {
    console.error('Error:', err);
    alert('Có lỗi xảy ra khi tải thông tin khách hàng. Vui lòng thử lại.');
  }
}

function renderCustomerDetail(data) {
  const customer = data.customer;
  const bookings = data.bookings || [];
  const notes = data.notes || [];
  
  function formatCurrency(amount) {
    if (!amount) return '0đ';
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
  }
  
  function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
  }
  
  function getStatusColor(status) {
    const colors = {
      'Đang xử lý': 'warning',
      'Đã xác nhận': 'success',
      'Đã cọc': 'info',
      'Hoàn thành': 'success',
      'Đã hủy': 'danger'
    };
    return colors[status] || 'secondary';
  }
  
  function getNoteTypeLabel(type) {
    const labels = {
      'an_chay': 'Ăn chay',
      'di_ung': 'Dị ứng',
      'suc_khoe': 'Sức khỏe',
      'yeu_cau_dac_biet': 'Yêu cầu đặc biệt',
      'khac': 'Khác'
    };
    return labels[type] || type;
  }
  
  const content = `
    <div class="row mb-4">
      <div class="col-md-6">
        <h5>Thông tin khách hàng</h5>
        <table class="table table-bordered">
          <tr>
            <th width="40%">Email:</th>
            <td>${customer.email || ''}</td>
          </tr>
          <tr>
            <th>Số điện thoại:</th>
            <td>${customer.phone || ''}</td>
          </tr>
          <tr>
            <th>Tổng số booking:</th>
            <td><span class="badge bg-primary">${customer.total_bookings || 0}</span></td>
          </tr>
          <tr>
            <th>Tổng chi tiêu:</th>
            <td><strong>${formatCurrency(customer.total_spent)}</strong></td>
          </tr>
          <tr>
            <th>Booking đầu tiên:</th>
            <td>${formatDate(customer.first_booking_date)}</td>
          </tr>
          <tr>
            <th>Booking gần nhất:</th>
            <td>${formatDate(customer.last_booking_date)}</td>
          </tr>
        </table>
      </div>
      <div class="col-md-6">
        <h5>Ghi chú đặc biệt</h5>
        ${notes.length > 0 ? `
          <div class="list-group">
            ${notes.map(note => `
              <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                  <h6 class="mb-1">${getNoteTypeLabel(note.type)}</h6>
                </div>
                <p class="mb-1">${note.content || ''}</p>
              </div>
            `).join('')}
          </div>
        ` : '<p class="text-muted">Chưa có ghi chú</p>'}
      </div>
    </div>
    
    <div class="row">
      <div class="col-12">
        <h5>Danh sách Booking</h5>
        ${bookings.length > 0 ? `
          <div class="table-responsive">
            <table class="table table-sm table-hover">
              <thead class="table-light">
                <tr>
                  <th>Mã Booking</th>
                  <th>Tour</th>
                  <th>Số lượng</th>
                  <th>Ngày khởi hành</th>
                  <th>Trạng thái</th>
                  <th>Tổng tiền</th>
                </tr>
              </thead>
              <tbody>
                ${bookings.map(booking => `
                  <tr>
                    <td>${booking.id || ''}</td>
                    <td>${booking.tour_name || ''}</td>
                    <td>${booking.quantity || 0}</td>
                    <td>${formatDate(booking.departureDate)}</td>
                    <td><span class="badge bg-${getStatusColor(booking.status)}">${booking.status || ''}</span></td>
                    <td>${formatCurrency(booking.total_amount)}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        ` : '<p class="text-muted">Chưa có booking</p>'}
      </div>
    </div>
  `;
  
  document.getElementById('customerDetailContent').innerHTML = content;
}
</script>
</body>
</html>

