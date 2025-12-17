<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Quản lý Nhà Cung Cấp</title>
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
        <a class="nav-item active" href="index.php?action=dashboard">
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

        <a class="nav-item" href="index.php?action=guide-special">
          <i class="bi bi-heart-pulse me-2"></i> Yêu cầu đặc biệt
        </a>

        <a class="nav-item" href="index.php?action=special-notes">
          <i class="bi bi-sticky me-2"></i> Ghi chú
        </a>

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

    <h3 style="margin-bottom:22px;color:#4a3512;">Quản lý Nhà Cung Cấp</h3>
    <div class="grid">
      <div class="card-panel">
        <h2 id="total-suppliers" style="float:left;">Số nhà cung cấp: 0</h2>
        <button class="btn btn-success" style="float:right;" data-bs-toggle="modal" data-bs-target="#addSupplierModal" onclick="resetSupplierForm()">
          <i class="bi bi-plus-circle me-1"></i> Thêm mới
        </button>
        <div style="clear:both;"></div>
      </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card-panel mt-3">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Lọc theo loại dịch vụ:</label>
          <select class="form-select" id="filterServiceType" onchange="loadSuppliers()">
            <option value="">Tất cả</option>
            <?php foreach ($serviceTypes ?? [] as $key => $label): ?>
              <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div style="margin-top:22px" class="card-panel">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>STT</th>
            <th>Logo</th>
            <th>Tên nhà cung cấp</th>
            <th>Loại dịch vụ</th>
            <th>Địa chỉ</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Website</th>
            <th>Hành động</th>
          </tr>
        </thead>
        <tbody id="suppliersTableBody">
          <tr>
            <td colspan="9" class="text-center text-muted">Đang tải dữ liệu...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- MODAL THÊM/ SỬA NHÀ CUNG CẤP -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="supplierModalTitle">Thêm Nhà Cung Cấp Mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="supplierForm" enctype="multipart/form-data">
          <input type="hidden" id="supplierId" name="id">
          
          <div class="mb-3">
            <label for="supplierName" class="form-label">Tên nhà cung cấp <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="supplierName" name="name" required>
          </div>

          <div class="mb-3">
            <label for="supplierServiceType" class="form-label">Loại dịch vụ <span class="text-danger">*</span></label>
            <select class="form-select" id="supplierServiceType" name="service_type" required>
              <option value="">-- Chọn loại dịch vụ --</option>
              <?php foreach ($serviceTypes ?? [] as $key => $label): ?>
                <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="supplierAddress" class="form-label">Địa chỉ</label>
            <textarea class="form-control" id="supplierAddress" name="address" rows="2"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="supplierEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="supplierEmail" name="email">
            </div>
            <div class="col-md-6 mb-3">
              <label for="supplierPhone" class="form-label">Số điện thoại</label>
              <input type="text" class="form-control" id="supplierPhone" name="phone">
            </div>
          </div>

          <div class="mb-3">
            <label for="supplierWebsite" class="form-label">Website</label>
            <input type="url" class="form-control" id="supplierWebsite" name="website" placeholder="https://example.com">
          </div>

          <div class="mb-3">
            <label for="supplierDescription" class="form-label">Mô tả ngắn</label>
            <textarea class="form-control" id="supplierDescription" name="description" rows="3"></textarea>
          </div>

          <div class="mb-3">
            <label for="supplierLogo" class="form-label">Logo / Hình ảnh</label>
            <input type="file" class="form-control" id="supplierLogo" name="logo" accept="image/*">
            <small class="form-text text-muted">Chấp nhận: JPG, PNG, GIF (tùy chọn)</small>
            <div id="logoPreview" class="mt-2" style="display:none;">
              <img id="logoPreviewImg" src="" alt="Logo preview" style="max-width:200px;max-height:200px;border-radius:8px;">
            </div>
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

<!-- MODAL XEM CHI TIẾT -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Chi tiết Nhà Cung Cấp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="supplierDetailContent">
        <!-- Nội dung sẽ được load động -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let suppliers = [];

// Load danh sách nhà cung cấp
function loadSuppliers() {
  const serviceType = document.getElementById('filterServiceType').value;
  let url = 'index.php?action=getSuppliers';
  if (serviceType) {
    url += '&service_type=' + encodeURIComponent(serviceType);
  }
  
  fetch(url)
    .then(response => response.json())
    .then(data => {
      suppliers = data || [];
      renderSuppliers();
      document.getElementById('total-suppliers').textContent = 'Số nhà cung cấp: ' + suppliers.length;
    })
    .catch(error => {
      console.error('Error loading suppliers:', error);
      document.getElementById('suppliersTableBody').innerHTML = 
        '<tr><td colspan="9" class="text-center text-danger">Lỗi khi tải dữ liệu</td></tr>';
    });
}

// Render bảng nhà cung cấp
function renderSuppliers() {
  const tbody = document.getElementById('suppliersTableBody');
  
  if (suppliers.length === 0) {
    tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Chưa có nhà cung cấp nào</td></tr>';
    return;
  }
  
  tbody.innerHTML = suppliers.map((supplier, index) => `
    <tr>
      <th scope="row">${index + 1}</th>
      <td>
        ${supplier.logo ? 
          `<img src="${supplier.logo}" alt="Logo" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">` : 
          '<i class="bi bi-building" style="font-size:24px;color:#ccc;"></i>'
        }
      </td>
      <td><strong>${escapeHtml(supplier.name)}</strong></td>
      <td><span class="badge bg-info">${escapeHtml(supplier.service_type_name || supplier.service_type)}</span></td>
      <td>${supplier.address ? escapeHtml(supplier.address.substring(0, 50)) + (supplier.address.length > 50 ? '...' : '') : '<span class="text-muted">-</span>'}</td>
      <td>${supplier.email ? escapeHtml(supplier.email) : '<span class="text-muted">-</span>'}</td>
      <td>${supplier.phone ? escapeHtml(supplier.phone) : '<span class="text-muted">-</span>'}</td>
      <td>${supplier.website ? `<a href="${supplier.website}" target="_blank" rel="noopener">${escapeHtml(supplier.website.substring(0, 30))}${supplier.website.length > 30 ? '...' : ''}</a>` : '<span class="text-muted">-</span>'}</td>
      <td>
        <button onclick="viewSupplier('${supplier.id}')" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
          <i class="bi bi-eye"></i>
        </button>
        <button onclick="editSupplier('${supplier.id}')" class="btn btn-sm btn-primary me-1" title="Sửa">
          <i class="bi bi-pencil"></i>
        </button>
        <button onclick="deleteSupplier('${supplier.id}', '${escapeHtml(supplier.name)}')" class="btn btn-sm btn-danger" title="Xóa">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>
  `).join('');
}

// Xem chi tiết
function viewSupplier(id) {
  fetch(`index.php?action=getSupplier&id=${id}`)
    .then(response => response.json())
    .then(supplier => {
      if (supplier.success === false) {
        alert('Không tìm thấy nhà cung cấp');
        return;
      }
      
      const content = `
        <div class="row">
          <div class="col-md-4 text-center mb-3">
            ${supplier.logo ? 
              `<img src="${supplier.logo}" alt="Logo" class="img-fluid" style="max-height:200px;border-radius:8px;">` : 
              '<i class="bi bi-building" style="font-size:100px;color:#ccc;"></i>'
            }
          </div>
          <div class="col-md-8">
            <h5>${escapeHtml(supplier.name)}</h5>
            <p><strong>Loại dịch vụ:</strong> <span class="badge bg-info">${escapeHtml(supplier.service_type_name || supplier.service_type)}</span></p>
            ${supplier.address ? `<p><strong>Địa chỉ:</strong> ${escapeHtml(supplier.address)}</p>` : ''}
            ${supplier.email ? `<p><strong>Email:</strong> <a href="mailto:${supplier.email}">${escapeHtml(supplier.email)}</a></p>` : ''}
            ${supplier.phone ? `<p><strong>SĐT:</strong> <a href="tel:${supplier.phone}">${escapeHtml(supplier.phone)}</a></p>` : ''}
            ${supplier.website ? `<p><strong>Website:</strong> <a href="${supplier.website}" target="_blank" rel="noopener">${escapeHtml(supplier.website)}</a></p>` : ''}
            ${supplier.description ? `<p><strong>Mô tả:</strong> ${escapeHtml(supplier.description)}</p>` : ''}
          </div>
        </div>
      `;
      
      document.getElementById('supplierDetailContent').innerHTML = content;
      new bootstrap.Modal(document.getElementById('viewSupplierModal')).show();
    })
    .catch(error => {
      console.error('Error loading supplier:', error);
      alert('Lỗi khi tải thông tin nhà cung cấp');
    });
}

// Sửa nhà cung cấp
function editSupplier(id) {
  const supplier = suppliers.find(s => s.id === id);
  if (!supplier) {
    alert('Không tìm thấy nhà cung cấp');
    return;
  }
  
  document.getElementById('supplierModalTitle').textContent = 'Sửa Nhà Cung Cấp';
  document.getElementById('supplierId').value = supplier.id;
  document.getElementById('supplierName').value = supplier.name || '';
  document.getElementById('supplierServiceType').value = supplier.service_type || '';
  document.getElementById('supplierAddress').value = supplier.address || '';
  document.getElementById('supplierEmail').value = supplier.email || '';
  document.getElementById('supplierPhone').value = supplier.phone || '';
  document.getElementById('supplierWebsite').value = supplier.website || '';
  document.getElementById('supplierDescription').value = supplier.description || '';
  
  // Hiển thị logo preview nếu có
  if (supplier.logo) {
    document.getElementById('logoPreview').style.display = 'block';
    document.getElementById('logoPreviewImg').src = supplier.logo;
  } else {
    document.getElementById('logoPreview').style.display = 'none';
  }
  
  new bootstrap.Modal(document.getElementById('addSupplierModal')).show();
}

// Xóa nhà cung cấp
function deleteSupplier(id, name) {
  if (!confirm(`Bạn có chắc chắn muốn xóa nhà cung cấp "${name}"?`)) {
    return;
  }
  
  fetch('index.php?action=deleteSupplier', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id: id })
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Xóa nhà cung cấp thành công');
        loadSuppliers();
      } else {
        alert('Lỗi: ' + (data.message || 'Không thể xóa nhà cung cấp'));
      }
    })
    .catch(error => {
      console.error('Error deleting supplier:', error);
      alert('Lỗi khi xóa nhà cung cấp');
    });
}

// Reset form
function resetSupplierForm() {
  document.getElementById('supplierModalTitle').textContent = 'Thêm Nhà Cung Cấp Mới';
  document.getElementById('supplierForm').reset();
  document.getElementById('supplierId').value = '';
  document.getElementById('logoPreview').style.display = 'none';
}

// Xử lý submit form
document.getElementById('supplierForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const id = formData.get('id');
  const isEdit = !!id;
  
  // Nếu là edit và không có file mới, gửi JSON
  if (isEdit && !formData.get('logo').name) {
    const data = {
      id: id,
      name: formData.get('name'),
      service_type: formData.get('service_type'),
      address: formData.get('address'),
      email: formData.get('email'),
      phone: formData.get('phone'),
      website: formData.get('website'),
      description: formData.get('description')
    };
    
    fetch('index.php?action=' + (isEdit ? 'updateSupplier' : 'addSupplier'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          alert(result.message || 'Thành công');
          bootstrap.Modal.getInstance(document.getElementById('addSupplierModal')).hide();
          loadSuppliers();
          resetSupplierForm();
        } else {
          alert('Lỗi: ' + (result.message || 'Không thể lưu'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Lỗi khi lưu nhà cung cấp');
      });
  } else {
    // Có file upload, dùng FormData
    fetch('index.php?action=' + (isEdit ? 'updateSupplier' : 'addSupplier'), {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(result => {
        if (result.success) {
          alert(result.message || 'Thành công');
          bootstrap.Modal.getInstance(document.getElementById('addSupplierModal')).hide();
          loadSuppliers();
          resetSupplierForm();
        } else {
          alert('Lỗi: ' + (result.message || 'Không thể lưu'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Lỗi khi lưu nhà cung cấp');
      });
  }
});

// Preview logo
document.getElementById('supplierLogo').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('logoPreview').style.display = 'block';
      document.getElementById('logoPreviewImg').src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});

// Utility function
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Toggle sidebar
document.getElementById('btnToggle')?.addEventListener('click', function() {
  document.getElementById('sidebar').classList.toggle('collapsed');
});

// Load dữ liệu khi trang được tải
document.addEventListener('DOMContentLoaded', function() {
  loadSuppliers();
});
</script>
</body>
</html>

