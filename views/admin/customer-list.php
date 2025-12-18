<?php
// Early AJAX endpoint: return customers as JSON before any HTML output
require_once __DIR__ . '/../../commons/function.php';
$db = connectDB();
if (!empty($_GET['ajax_customers'])) {
  $departureId = $_GET['departure_id'] ?? null;
  $tourId = $_GET['tour_id'] ?? null;
  try {
    if ($departureId) {
      $sql = "SELECT c.* , br.booking_id, br.id AS registrant_id, ch.id AS checkin_id, ch.checkin_time
          FROM customers c
          JOIN booking_registrants br ON c.registrants_id = br.id
          JOIN bookings b ON br.booking_id = b.id
          LEFT JOIN checkins ch ON ch.customer_id = c.id
          WHERE b.departuresId = :dep
          ORDER BY c.id ASC";
      $stmt = $db->prepare($sql);
      $stmt->execute([':dep' => $departureId]);
    } elseif ($tourId) {
      $sql = "SELECT c.* , br.booking_id, br.id AS registrant_id, ch.id AS checkin_id, ch.checkin_time
          FROM customers c
          JOIN booking_registrants br ON c.registrants_id = br.id
          JOIN bookings b ON br.booking_id = b.id
          LEFT JOIN checkins ch ON ch.customer_id = c.id
          WHERE b.tourId = :tour
          ORDER BY c.id ASC";
      $stmt = $db->prepare($sql);
      $stmt->execute([':tour' => $tourId]);
    } else {
      header('Content-Type: application/json; charset=utf-8');
      echo json_encode(['success' => true, 'customers' => []], JSON_UNESCAPED_UNICODE);
      exit;
    }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'customers' => $rows], JSON_UNESCAPED_UNICODE);
  } catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
  }
  exit;
}

?>
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

        <!-- TRANG CHÍNH -->
        <a class="nav-item" href="index.php?action=dashboard">
          <i class="bi bi-house-door-fill me-2"></i> Trang quản trị
        </a>

        <!-- QUẢN LÝ TOUR -->

        <a class="nav-item" href="index.php?action=tour-list">
          <i class="bi bi-airplane me-2"></i> Danh sách Tour
        </a>
        <a class="nav-item active" href="index.php?action=customer-list"><i class="bi bi-people me-2"></i> Quản lý Khách hàng</a>

        <a class="nav-item" href="index.php?action=supplier-list">
          <i class="bi bi-building me-2"></i> Quản lý Nhà Cung Cấp
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

    <?php
    function formatCurrency($amount) { if (!$amount) return '0đ'; return number_format($amount, 0, ',', '.') . 'đ'; }
    function formatDate($dateString) { if (!$dateString) return ''; return date('d/m/Y', strtotime($dateString)); }

     // build tours summary: only tours that have at least one booking
     $sql = "SELECT t.id, t.type, t.name, t.max_people,
        COALESCE((SELECT image_url FROM tour_images WHERE tour_id = t.id LIMIT 1),'') AS image_url,
        MIN(d.dateStart) AS depart_start, MAX(d.dateEnd) AS depart_end,
        COUNT(DISTINCT b.id) AS bookings_count,
        COALESCE(SUM(CAST(br.quantity AS UNSIGNED)),0) AS registrants_total
      FROM tours t
      INNER JOIN bookings b ON b.tourId = t.id
      LEFT JOIN departures d ON d.tourId = t.id
      LEFT JOIN booking_registrants br ON br.booking_id = b.id
      GROUP BY t.id
      ORDER BY CAST(t.id AS UNSIGNED) ASC";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h3 style="margin-bottom:22px;color:#4a3512;">Danh sách Tour & Booking (khách)</h3>
    <div class="grid">
      <div class="card-panel">
        <h2 style="float:left;">Tổng số tour: <?= count($tours) ?></h2>
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
            <th>Ngày khởi hành</th>
            <th>Hình ảnh</th>
            <th>Số lượng</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tours)): ?>
            <tr><td colspan="7" class="text-center text-muted">Chưa có tour nào</td></tr>
          <?php else: ?>
            <?php foreach ($tours as $index => $row): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($row['type'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                <td>
                  <?php if ($row['depart_start']):
                    echo htmlspecialchars(formatDate($row['depart_start']));
                    if ($row['depart_end']) echo ' - ' . htmlspecialchars(formatDate($row['depart_end']));
                  else echo '-'; endif; ?>
                </td>
                <td><?= $row['image_url'] ? '<img src="' . htmlspecialchars($row['image_url']) . '" style="width:80px;height:50px;object-fit:cover;border-radius:4px;">' : '—' ?></td>
                <td><?= (int)$row['registrants_total'] ?><?= isset($row['max_people']) && $row['max_people']>0 ? ' / ' . (int)$row['max_people'] : '' ?></td>
                <td>
                  <button type="button" class="btn btn-sm btn-light btnToggleCustomers" data-tour-id="<?= htmlspecialchars($row['id']) ?>" title="Xem khách"><i class="bi bi-caret-down"></i></button>
                </td>
              </tr>
              <tr class="customer-row d-none"><td colspan="7" class="p-0 border-0"><div class="p-3 bg-white customer-list-container" style="display:none"></div></td></tr>
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
    const res = await fetch(`index.php?action=getCustomerDetail&email=${encodeURIComponent(email)}`);
    
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
<script>
// Toggle customers list under a tour row
document.addEventListener('DOMContentLoaded', function(){
  function escapeHtml(s) { return String(s).replace(/[&<>"']/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[m]; }); }
  function renderCustomersHtml(customers) {
    if (!customers || customers.length === 0) return '<div class="text-muted p-2">Không có khách hàng</div>';
    let html = '<div class="table-responsive"><table class="table table-sm mb-0"><thead class="table-light"><tr><th>#</th><th>Tên</th><th>Ngày sinh</th><th>Giới tính</th><th>Booking ID</th></tr></thead><tbody>';
    customers.forEach((c, idx) => {
      html += `<tr><td>${idx+1}</td><td>${escapeHtml(c.name||'')}</td><td>${escapeHtml(c.date||'')}</td><td>${escapeHtml(c.gender||'')}</td><td>${escapeHtml(c.booking_id||'')}</td></tr>`;
    });
    html += '</tbody></table></div>';
    return html;
  }

  document.querySelectorAll('.btnToggleCustomers').forEach(btn => {
    btn.addEventListener('click', async function(){
      const tr = btn.closest('tr');
      const next = tr.nextElementSibling;
      if (!next || !next.classList.contains('customer-row')) return;
      const container = next.querySelector('.customer-list-container');
      const tourId = btn.getAttribute('data-tour-id') || '';

      if (container.dataset.loaded === '1') {
        const shown = container.style.display !== 'none';
        container.style.display = shown ? 'none' : '';
        next.classList.toggle('d-none', shown);
        return;
      }

      try {
        const params = new URLSearchParams();
        if (tourId) params.set('tour_id', tourId);
        params.set('ajax_customers', '1');
        const res = await fetch('index.php?action=customer-list&' + params.toString());
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        if (data.success) {
          container.innerHTML = renderCustomersHtml(data.customers || []);
          container.dataset.loaded = '1';
          container.style.display = '';
          next.classList.remove('d-none');
        } else {
          container.innerHTML = '<div class="p-2 text-danger">Lỗi khi tải khách hàng</div>';
          container.style.display = '';
          next.classList.remove('d-none');
        }
      } catch (err) {
        container.innerHTML = '<div class="p-2 text-danger">' + escapeHtml(err.message) + '</div>';
        container.style.display = '';
        next.classList.remove('d-none');
      }
    });
  });
});
</script>
</body>
</html>

