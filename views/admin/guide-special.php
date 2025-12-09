<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Yêu cầu đặc biệt của khách</title>
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
        <a class="nav-item" href="index.php?action=customer-list"><i class="bi bi-people me-2"></i> Quản lý Khách hàng</a>
        <a class="nav-item" href="index.php?action=supplier-list"><i class="bi bi-building me-2"></i> Quản lý Nhà Cung Cấp</a>
        <a class="nav-item" href="index.php?action=guide-logs"><i class="bi bi-journal-text me-2"></i> Nhật ký Tour</a>
        <a class="nav-item active" href="index.php?action=guide-special"><i class="bi bi-heart-pulse me-2"></i> Yêu cầu đặc biệt</a>
        <a class="nav-item" href="index.php?action=special-notes"><i class="bi bi-sticky me-2"></i> Ghi chú (API)</a>
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
    function getSpecialTypeLabel($type) {
        $labels = [
            'an_chay' => 'Ăn chay',
            'di_ung' => 'Dị ứng',
            'suc_khoe' => 'Sức khỏe',
            'yeu_cau_dac_biet' => 'Yêu cầu đặc biệt',
            'khac' => 'Khác'
        ];
        return $labels[$type] ?? $type ?? 'Khác';
    }

    $specialRequests = $specialRequests ?? [];
    $noteTypes = $noteTypes ?? [];
    $customersWithRequests = $customersWithRequests ?? [];
    ?>

    <h3 style="margin-bottom:22px;color:#4a3512;">Yêu cầu đặc biệt của khách</h3>

    <!-- Summary cards -->
    <div class="grid">
      <div class="card-panel">
        <h5>Tổng số yêu cầu</h5>
        <p class="fs-4 fw-bold mb-1"><?= count($specialRequests) ?></p>
        <small class="text-muted">Tất cả yêu cầu đang được lưu trong hệ thống</small>
      </div>
      <div class="card-panel">
        <h5>Khách có yêu cầu đặc biệt</h5>
        <p class="fs-4 fw-bold mb-1"><?= count($customersWithRequests) ?></p>
        <small class="text-muted">Tính theo email khách hàng</small>
      </div>
      <div class="card-panel">
        <h5>Lọc theo khách</h5>
        <form class="d-flex gap-2" method="get" action="index.php">
          <input type="hidden" name="action" value="guide-special">
          <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>" placeholder="Nhập email khách cần xem">
          <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-search"></i> Lọc</button>
          <a href="index.php?action=guide-special" class="btn btn-sm btn-outline-secondary">Xóa lọc</a>
        </form>
      </div>
    </div>

    <!-- Danh sách yêu cầu -->
    <div style="margin-top:22px" class="card-panel">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Danh sách yêu cầu đặc biệt</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#specialModal" onclick="openAddSpecialModal()">+ Thêm yêu cầu</button>
      </div>

      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Tour</th>
            <th>Loại yêu cầu</th>
            <th>Nội dung</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($specialRequests)): ?>
            <tr>
              <td colspan="7" class="text-center text-muted">Chưa có yêu cầu nào</td>
            </tr>
          <?php else: ?>
            <?php foreach ($specialRequests as $index => $req): ?>
              <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= htmlspecialchars($req->customer_email ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($req->customer_phone ?? 'N/A') ?></td>
                <td>
                  <?= htmlspecialchars($req->tour_name ?? 'N/A') ?>
                  <?php if (!empty($req->tour_type)): ?>
                    <small class="text-muted">(<?= htmlspecialchars($req->tour_type) ?>)</small>
                  <?php endif; ?>
                </td>
                <td><span class="badge bg-info"><?= getSpecialTypeLabel($req->type ?? '') ?></span></td>
                <td><?= htmlspecialchars($req->content ?? '') ?></td>
                <td>
                  <button
                    class="btn btn-sm btn-primary"
                    onclick="editSpecial(
                      '<?= htmlspecialchars($req->id, ENT_QUOTES, 'UTF-8') ?>',
                      '<?= htmlspecialchars($req->customer_email ?? '', ENT_QUOTES, 'UTF-8') ?>',
                      '<?= htmlspecialchars($req->type ?? '', ENT_QUOTES, 'UTF-8') ?>',
                      '<?= htmlspecialchars($req->content ?? '', ENT_QUOTES, 'UTF-8') ?>'
                    )"
                    title="Chỉnh sửa"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button
                    class="btn btn-sm btn-danger"
                    onclick="deleteSpecial('<?= htmlspecialchars($req->id, ENT_QUOTES, 'UTF-8') ?>')"
                    title="Xóa"
                  >
                    <i class="bi bi-trash3"></i>
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

<!-- MODAL THÊM / SỬA YÊU CẦU ĐẶC BIỆT -->
<div class="modal fade" id="specialModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="specialModalTitle">Thêm yêu cầu đặc biệt</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="specialForm">
          <input type="hidden" id="specialId" name="specialId">
          <div class="mb-3">
            <label for="specialEmail" class="form-label">Email khách hàng</label>
            <input type="email" class="form-control" id="specialEmail" name="email" required>
            <small id="specialEmailHelp" class="form-text text-muted">
              Nhập email trùng với booking của khách
            </small>
          </div>
          <div class="mb-3">
            <label for="specialType" class="form-label">Loại yêu cầu</label>
            <select class="form-select" id="specialType" name="type" required>
              <option value="">-- Chọn loại --</option>
              <?php foreach ($noteTypes as $key => $label): ?>
                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="specialContent" class="form-label">Nội dung yêu cầu</label>
            <textarea class="form-control" id="specialContent" name="content" rows="4" required
              placeholder="Ví dụ: Ăn chay trường, dị ứng hải sản, cần phòng tầng thấp..."></textarea>
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
});

function openAddSpecialModal() {
  const form = document.getElementById('specialForm');
  form.reset();
  document.getElementById('specialId').value = '';
  document.getElementById('specialModalTitle').textContent = 'Thêm yêu cầu đặc biệt';

  const emailInput = document.getElementById('specialEmail');
  const emailHelp = document.getElementById('specialEmailHelp');
  emailInput.readOnly = false;
  emailInput.classList.remove('bg-light');
  emailHelp.textContent = 'Nhập email trùng với booking của khách';
  emailHelp.className = 'form-text text-muted';
}

function editSpecial(id, email, type, content) {
  document.getElementById('specialId').value = id || '';
  document.getElementById('specialEmail').value = email || '';
  document.getElementById('specialType').value = type || '';
  document.getElementById('specialContent').value = content || '';

  const emailInput = document.getElementById('specialEmail');
  const emailHelp = document.getElementById('specialEmailHelp');
  emailInput.readOnly = true;
  emailInput.classList.add('bg-light');
  emailHelp.textContent = 'Email không thể thay đổi khi chỉnh sửa yêu cầu';
  emailHelp.className = 'form-text text-warning';

  document.getElementById('specialModalTitle').textContent = 'Chỉnh sửa yêu cầu đặc biệt';
  const modal = new bootstrap.Modal(document.getElementById('specialModal'));
  modal.show();
}

// Submit form (add / update)
document.getElementById('specialForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();

  const id = document.getElementById('specialId').value;
  const email = document.getElementById('specialEmail').value;
  const type = document.getElementById('specialType').value;
  const content = document.getElementById('specialContent').value;

  if (!email || !type || !content) {
    alert('Vui lòng điền đầy đủ thông tin');
    return;
  }

  const data = { email, type, content };
  if (id) data.id = id;

  try {
    const action = id ? 'updateGuideSpecial' : 'addGuideSpecial';
    const res = await fetch(`index.php?action=${action}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);
    const result = await res.json();

    if (result.success) {
      alert(id ? 'Cập nhật yêu cầu thành công!' : 'Thêm yêu cầu thành công!');
      bootstrap.Modal.getInstance(document.getElementById('specialModal')).hide();
      window.location.reload();
    } else {
      alert(result.message || 'Có lỗi xảy ra khi lưu yêu cầu');
    }
  } catch (err) {
    console.error(err);
    alert('Lỗi kết nối tới server, vui lòng thử lại.');
  }
});

// Delete special request
async function deleteSpecial(id) {
  if (!confirm('Bạn có chắc chắn muốn xóa yêu cầu này?')) return;

  try {
    const formData = new FormData();
    formData.append('id', id);

    const res = await fetch('index.php?action=deleteGuideSpecial', {
      method: 'POST',
      body: formData
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);
    const result = await res.json();

    if (result.success) {
      alert('Xóa yêu cầu thành công!');
      window.location.reload();
    } else {
      alert(result.message || 'Có lỗi xảy ra khi xóa yêu cầu');
    }
  } catch (err) {
    console.error(err);
    alert('Lỗi kết nối tới server, vui lòng thử lại.');
  }
}

// Reset modal when closed
const specialModal = document.getElementById('specialModal');
if (specialModal) {
  specialModal.addEventListener('hidden.bs.modal', () => {
    openAddSpecialModal();
  });
}
</script>
</body>
</html>


