<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ghi chú đặc biệt</title>
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
        <a class="nav-item " href="index.php?action=dashboard">
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

        

        <a class="nav-item active" href="index.php?action=special-notes">
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
    function getNoteTypeLabel($type) {
        $labels = [
            'an_chay' => 'Ăn chay',
            'di_ung' => 'Dị ứng',
            'suc_khoe' => 'Sức khỏe',
            'yeu_cau_dac_biet' => 'Yêu cầu đặc biệt',
            'khac' => 'Khác'
        ];
        return $labels[$type] ?? $type ?? 'Khác';
    }
    ?>

    <h3 style="margin-bottom:22px;color:#4a3512;">Danh sách Khách hàng (Ghi chú)</h3>
    <?php
      // fetch customers that have non-empty notes and include the tour they belong to (if available)
      require_once __DIR__ . '/../../commons/function.php';
      $db = connectDB();
      $cstmt = $db->prepare("SELECT c.id, c.name, c.note, br.email AS email, t.name AS tour_name FROM customers c LEFT JOIN booking_registrants br ON c.registrants_id = br.id LEFT JOIN bookings b ON br.booking_id = b.id LEFT JOIN tours t ON b.tourId = t.id WHERE c.note IS NOT NULL AND TRIM(c.note) <> '' ORDER BY c.id ASC");
      $cstmt->execute();
      $customers = $cstmt->fetchAll(PDO::FETCH_ASSOC);
      $specialCount = count($customers);
    ?>

    <div class="grid" style="gap:12px;grid-template-columns:1fr 360px;align-items:center;">
      <div class="card-panel">
        <h2 style="margin:0;color:#4a3512">Khách có yêu cầu đặc biệt</h2>
        <div style="font-size:28px;font-weight:700;margin-top:8px;color:#d35400"><?= $specialCount ?></div>
      </div>

      <div class="card-panel" style="display:flex;align-items:center;gap:8px;">
        <div style="flex:1">
          <label class="form-label" for="filterEmail">Lọc theo ghi chú</label>
          <input id="filterEmail" class="form-control" placeholder="Nhập nội dung ghi chú cần tìm">
        </div>
        <div style="display:flex;flex-direction:column;gap:6px;height:100%">
          <button id="btnFilter" class="btn btn-primary" style="height:40px;">Lọc</button>
          <button id="btnClearFilter" class="btn btn-outline-secondary" style="height:40px;">Xóa lọc</button>
        </div>
      </div>
    </div>

    <div style="margin-top:22px" class="card-panel">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th>
            <th>Tên khách</th>
            <th>Ghi chú</th>
            <th>Xem</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($customers)): ?>
            <tr>
              <td colspan="4" class="text-center text-muted">Chưa có khách nào</td>
            </tr>
          <?php else: ?>
            <?php foreach ($customers as $index => $c): ?>
              <tr data-note="<?= htmlspecialchars(strtolower($c['note'] ?? '')) ?>">
                <th scope="row"><?= $index + 1 ?></th>
                <td>
                  <?= htmlspecialchars($c['name'] ?? 'N/A') ?>
                  <?php if (!empty($c['email'])): ?>
                    <div class="text-muted small">Email: <?= htmlspecialchars($c['email']) ?></div>
                  <?php endif; ?>
                </td>
                <td class="note-cell"><?= htmlspecialchars($c['note'] ?? '') ?></td>
                <td>
                  <i class="bi bi-eye" style="cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= htmlspecialchars($c['tour_name'] ?? 'Chưa có tour') ?>"></i>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- MODAL THÊM/CHỈNH SỬA GHI CHÚ -->
<div class="modal fade" id="addNoteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noteModalTitle">Thêm ghi chú mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="noteForm">
          <input type="hidden" id="noteId" name="noteId">
          <div class="mb-3">
            <label for="customerEmail" class="form-label">Email khách hàng</label>
            <input type="email" class="form-control" id="customerEmail" name="customerEmail" required>
            <small class="form-text text-muted" id="emailHelp">Nhập email của khách hàng để liên kết ghi chú</small>
          </div>
          <div class="mb-3">
            <label for="noteType" class="form-label">Loại ghi chú</label>
            <select class="form-select" id="noteType" name="noteType" required>
              <option value="">-- Chọn loại --</option>
              <?php foreach ($noteTypes ?? [] as $key => $label): ?>
                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="noteContent" class="form-label">Nội dung</label>
            <textarea class="form-control" id="noteContent" name="noteContent" rows="4" required placeholder="Ví dụ: Khách ăn chay trường, dị ứng hải sản..."></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Lưu</button>
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

  // Xử lý nút sửa ghi chú
  document.querySelectorAll('.btn-edit-note').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-note-id');
      const email = this.getAttribute('data-note-email');
      const type = this.getAttribute('data-note-type');
      const content = this.getAttribute('data-note-content');
      editNote(id, email, type, content);
    });
  });

  // Xử lý nút xóa ghi chú
  document.querySelectorAll('.btn-delete-note').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.getAttribute('data-note-id');
      deleteNote(id);
    });
  });
});

// Mở modal thêm/chỉnh sửa
function openAddNoteModal() {
  document.getElementById('noteForm').reset();
  document.getElementById('noteId').value = '';
  document.getElementById('noteModalTitle').textContent = 'Thêm ghi chú mới';
  
  // Reset email field về trạng thái thêm mới
  const emailInput = document.getElementById('customerEmail');
  const emailHelp = document.getElementById('emailHelp');
  emailInput.readOnly = false;
  emailInput.classList.remove('bg-light');
  emailHelp.textContent = 'Nhập email của khách hàng để liên kết ghi chú';
  emailHelp.className = 'form-text text-muted';
}

function editNote(id, email, type, content) {
  // Điền thông tin vào form
  document.getElementById('noteId').value = id || '';
  document.getElementById('customerEmail').value = email || '';
  document.getElementById('noteType').value = type || '';
  document.getElementById('noteContent').value = content || '';
  
  // Set readonly cho email và cập nhật UI
  const emailInput = document.getElementById('customerEmail');
  const emailHelp = document.getElementById('emailHelp');
  emailInput.readOnly = true;
  emailInput.classList.add('bg-light');
  emailHelp.textContent = 'Email không thể thay đổi khi chỉnh sửa ghi chú';
  emailHelp.className = 'form-text text-warning';
  
  // Cập nhật tiêu đề modal
  document.getElementById('noteModalTitle').textContent = 'Chỉnh sửa ghi chú';
  
  // Mở modal
  const modal = new bootstrap.Modal(document.getElementById('addNoteModal'));
  modal.show();
}

// Lưu ghi chú
document.getElementById('noteForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const noteId = document.getElementById('noteId').value;
  const email = document.getElementById('customerEmail').value;
  const type = document.getElementById('noteType').value;
  const content = document.getElementById('noteContent').value;
  
  if (!email || !type || !content) {
    alert('Vui lòng điền đầy đủ thông tin');
    return;
  }
  
  try {
    const action = noteId ? 'updateNote' : 'addNote';
    const requestData = noteId 
      ? { id: noteId, type: type, content: content }
      : { email: email, type: type, content: content };
    
    const res = await fetch(`index.php?action=${action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(requestData)
    });
    
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    
    const result = await res.json();
    
    if (result.success) {
      alert(noteId ? 'Cập nhật ghi chú thành công!' : 'Thêm ghi chú thành công!');
      bootstrap.Modal.getInstance(document.getElementById('addNoteModal')).hide();
      window.location.reload();
    } else {
      alert(result.message || 'Có lỗi xảy ra khi lưu ghi chú');
    }
  } catch (err) {
    console.error('Error:', err);
    alert('Có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại.');
  }
});

// Xóa ghi chú
async function deleteNote(id) {
  if (!confirm('Bạn có chắc chắn muốn xóa ghi chú này?')) {
    return;
  }
  
  try {
    const res = await fetch('index.php?action=deleteNote', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    });
    
    if (!res.ok) {
      throw new Error(`HTTP error! status: ${res.status}`);
    }
    
    const result = await res.json();
    
    if (result.success) {
      alert('Xóa ghi chú thành công!');
      window.location.reload();
    } else {
      alert(result.message || 'Có lỗi xảy ra khi xóa ghi chú');
    }
  } catch (err) {
    console.error('Error:', err);
    alert('Có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại.');
  }
}

// Reset form khi đóng modal
const modal = document.getElementById('addNoteModal');
if (modal) {
  modal.addEventListener('hidden.bs.modal', () => {
    document.getElementById('noteForm').reset();
    document.getElementById('noteId').value = '';
    
    // Reset email field
    const emailInput = document.getElementById('customerEmail');
    const emailHelp = document.getElementById('emailHelp');
    emailInput.readOnly = false;
    emailInput.classList.remove('bg-light');
    emailHelp.textContent = 'Nhập email của khách hàng để liên kết ghi chú';
    emailHelp.className = 'form-text text-muted';
  });
  
  modal.addEventListener('show.bs.modal', (e) => {
    // Nếu click vào nút "Thêm ghi chú" (không có noteId), reset form
    if (!e.relatedTarget || !e.relatedTarget.hasAttribute('data-bs-target')) {
      if (!document.getElementById('noteId').value) {
        openAddNoteModal();
      }
    }
  });
}
</script>
<script>
// Client-side filter for special notes table
document.addEventListener('DOMContentLoaded', () => {
  const btnFilter = document.getElementById('btnFilter');
  const btnClear = document.getElementById('btnClearFilter');
  const input = document.getElementById('filterEmail');
  const tbody = document.querySelector('table.table tbody');
  let noRow = null;

  function showNoResult() {
    if (!noRow) {
      noRow = document.createElement('tr');
      noRow.id = 'noFilterResult';
      noRow.innerHTML = '<td colspan="4" class="text-center text-muted">Không có kết quả phù hợp</td>';
      tbody.appendChild(noRow);
    }
  }

  function clearNoResult() {
    if (noRow && noRow.parentNode) {
      noRow.parentNode.removeChild(noRow);
      noRow = null;
    }
  }

  function filterCustomers() {
    const q = (input.value || '').trim().toLowerCase();
    clearNoResult();
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    let visible = 0;
    rows.forEach(r => {
      if (r.id === 'noFilterResult') return;
      const noteAttr = (r.getAttribute('data-note') || '').toLowerCase();
      const noteCell = r.querySelector('.note-cell');
      const noteText = (noteCell ? noteCell.textContent : '').toLowerCase();
      const nameCell = r.querySelector('td');
      const name = (nameCell ? nameCell.textContent : '').toLowerCase();
      if (!q || noteAttr.includes(q) || noteText.includes(q) || name.includes(q)) {
        r.style.display = '';
        visible++;
      } else {
        r.style.display = 'none';
      }
    });
    if (visible === 0) showNoResult();
  }

  btnFilter?.addEventListener('click', () => filterCustomers());
  btnClear?.addEventListener('click', () => {
    if (input) input.value = '';
    clearNoResult();
    (Array.from(document.querySelectorAll('table.table tbody tr'))).forEach(r => r.style.display = '');
  });

  input?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      filterCustomers();
    }
  });
});
</script>
<script>
// Initialize Bootstrap tooltips for eye icons so the tour name shows on hover/click
document.addEventListener('DOMContentLoaded', () => {
  const eyeEls = Array.from(document.querySelectorAll('.bi-eye'));
  eyeEls.forEach(el => {
    try {
      new bootstrap.Tooltip(el, { placement: 'top', trigger: 'hover focus' });
    } catch (e) {
      // ignore if bootstrap not available
      console.warn('Tooltip init failed', e);
    }
  });
});
</script>
</body>
</html>

