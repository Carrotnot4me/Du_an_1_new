<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Quản lý Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- ✅ THÊM LEAFLET CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
  <link rel="stylesheet" href="./assets/list.css">
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

      <?php $user = $_SESSION['user'] ?? null; ?>
      <div style="margin-top:auto;font-size:13px;opacity:.9">
        <div>Người dùng: <strong><?php echo $user['username'] ?? 'Admin'; ?></strong></div>
        <div style="margin-top:6px">Email: <small><?php echo $user['email'] ?? 'admin@example.com'; ?></small></div>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
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

      <h3 style="margin-bottom:22px; color:#4a3512;">Danh sách Tour</h3>

      <div class="grid">
        <div class="card-panel">
          <h2 id="total-departures" style="float:left;">Số tour hiện có: <?= count($tours) ?></h2> 
          <button class="btn btn-success" style="float: right;" data-bs-toggle="modal" data-bs-target="#addTourModal"> + Thêm mới</button>
          <div style="clear: both;"></div>
        </div>
        <div class="card-panel">
          <button id="btnToggleFilter" class="btn btn-outline-primary" style="width:100%; margin-bottom:12px;">▼ Hiện bộ lọc</button>
          <div id="filterPanel" style="display:none;">
            <h5>Lọc</h5>
            <div style="margin-top:15px;">
              <label style="font-weight:600; margin-bottom:8px; display:block;">Loại Tour</label>
              <div style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:15px;">
                <label style="display:flex; align-items:center;">
                  <input type="checkbox" class="filterTypeCheckbox" value="Nội địa" style="margin-right:6px;">
                  Nội địa
                </label>
                <label style="display:flex; align-items:center;">
                  <input type="checkbox" class="filterTypeCheckbox" value="Quốc tế" style="margin-right:6px;">
                  Quốc tế
                </label>
                <label style="display:flex; align-items:center;">
                  <input type="checkbox" class="filterTypeCheckbox" value="Theo yêu cầu" style="margin-right:6px;">
                  Theo yêu cầu
                </label>
              </div>

              <label style="font-weight:600; margin-bottom:8px; display:block;">Giá (VNĐ)</label>
              <div style="display:flex; gap:10px; align-items:end; margin-bottom:15px;">
                <div style="flex:1;">
                  <label style="font-size:0.9rem; display:block; margin-bottom:4px;">Giá tối thiểu</label>
                  <input type="number" id="priceMin" class="form-control" placeholder="0" min="0">
                </div>
                <div style="flex:1;">
                  <label style="font-size:0.9rem; display:block; margin-bottom:4px;">Giá tối đa</label>
                  <input type="number" id="priceMax" class="form-control" placeholder="999999999" min="0">
                </div>
              </div>

              <button id="btnFilter" class="btn btn-primary" style="width:100%;">🔍 Tìm kiếm</button>
              <button id="btnResetFilter" class="btn btn-secondary" style="width:100%; margin-top:8px;">↻ Xoá bộ lọc</button>
            </div>
          </div>
        </div>
      </div>

      <div style="margin-top:22px" class="card-panel">
        <table class="table">
          <thead>
            <tr>
              <th>STT</th>
              <th>Loại Tour</th>
              <th>Tên Tour</th>
              <th>Điểm đến</th>
              <th>Hình ảnh</th>
              <th>Mã tour</th>
              <th>Giá cả</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody id="tourTableBody">
            <?php
            if (!empty($tours) && is_array($tours)):
              $i = 1;
              foreach ($tours as $t):
                $img = '';
                if (!empty($t['images']) && is_array($t['images'])) $img = $t['images'][0] ?? '';
                $priceText = isset($t['price']['adult']) ? number_format($t['price']['adult'], 0, ',', '.') . ' đ' : '—';
            ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><?= htmlspecialchars($t['type'] ?? '') ?></td>
                  <td><?= htmlspecialchars($t['name'] ?? '') ?></td>
                  <td><?= htmlspecialchars($t['main_destination'] ?? '') ?></td>
                  <td><?= $img ? '<img src="' . htmlspecialchars($img) . '" style="width:80px;height:50px;object-fit:cover;border-radius:4px;">' : '—' ?></td>
                  <td><?= htmlspecialchars($t['tour_code'] ?? '') ?></td>
                  <td><?= $priceText ?></td>
                  <td>
                    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=deleteTour" style="display:inline">
                      <input type="hidden" name="id" value="<?= htmlspecialchars($t['id'] ?? '') ?>">
                      <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Bạn muốn xóa?')"><i class="bi bi-trash3"></i></button>
                    </form>
                    <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=editTour&id=<?= htmlspecialchars($t['id'] ?? '') ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                  </td>
                </tr>
              <?php endforeach;
            else: ?>
              <tr>
                <td colspan="8" style="text-align:center;color:#999;">Không có tour nào</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- MODAL THÊM TOUR -->
  <div class="modal fade" id="addTourModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Thêm Tour Mới</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addTourForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=addTour">
            <!-- THÔNG TIN CƠ BẢN -->
            <h6
              style="color:#3b2a0a; font-weight:700; margin-bottom:15px; border-left:5px solid #f5c542; padding-left:10px;">
              Thông tin cơ bản</h6>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Loại Tour <span style="color:red;">*</span></label>
                <select class="form-select" name="type" required>
                  <option value="">-- Chọn loại tour --</option>
                  <option value="Nội địa">Nội địa</option>
                  <option value="Quốc tế">Quốc tế</option>
                  <option value="Theo yêu cầu">Theo yêu cầu</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tên Tour <span style="color:red;">*</span></label>
                <input type="text" class="form-control" name="name" placeholder="VD: Tour Miền Trung 5N4Đ" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4">
                <label class="form-label">Mã Tour (Tour Code) <span style="color:red;">*</span></label>
                <input type="text" class="form-control" name="tour_code" placeholder="VD: MT-001" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Điểm đến chính <span style="color:red;">*</span></label>
                <input type="text" class="form-control" name="main_destination" placeholder="VD: Đà Lạt, Hạ Long..."
                  required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Ảnh (URL, cách nhau bằng dấu phẩy)</label>
                <input type="text" class="form-control" name="images" placeholder="https://a.jpg, https://b.jpg, ...">
              </div>
            </div>

            <div class="col-md-4 mt-3">
              <label class="form-label">Số người tối đa</label>
              <input type="number" class="form-control" name="max_people" placeholder="VD: 20">
            </div>

            <div class="mb-3">
              <label class="form-label">Mô tả ngắn</label>
              <textarea class="form-control" name="short_description" rows="2"
                placeholder="Nhập mô tả tour..."></textarea>
            </div>


            <!-- GIÁ TIỀN -->
            <h6
              style="color:#3b2a0a; font-weight:700; margin-bottom:15px; margin-top:25px; border-left:5px solid #f5c542; padding-left:10px;">
              Giá tiền</h6>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Giá người lớn (VNĐ) <span style="color:red;">*</span></label>
                <input type="number" class="form-control" name="price_adult" placeholder="5500000" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Giá trẻ em (VNĐ) <span style="color:red;">*</span></label>
                <input type="number" class="form-control" name="price_child" placeholder="3000000" required>
              </div>
            </div>

            <!-- CHÍNH SÁCH -->
            <h6
              style="color:#3b2a0a; font-weight:700; margin-bottom:15px; margin-top:25px; border-left:5px solid #f5c542; padding-left:10px;">
              Chính sách</h6>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Chính sách hủy</label>
                <textarea class="form-control" name="policy_cancel" rows="2"
                  placeholder="VD: Hủy trước 5 ngày hoàn 80%"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Chính sách hoàn tiền</label>
                <textarea class="form-control" name="policy_refund" rows="2"
                  placeholder="VD: Hoàn tiền trong 7 ngày làm việc"></textarea>
              </div>
            </div>

            <!-- LỊCH TRÌNH -->
            <h6
              style="color:#3b2a0a; font-weight:700; margin-bottom:15px; margin-top:25px; border-left:5px solid #f5c542; padding-left:10px;">
              Lịch trình</h6>
            <div id="scheduleContainer">
              <div class="schedule-item mb-3 p-3" style="background:#f9f7f0; border-radius:6px;">
                <div class="row mb-2">
                  <div class="col-md-4">
                    <label class="form-label">Ngày thứ</label>
                    <input type="number" name="schedule_day[]" class="form-control schedule-day" placeholder="1" min="1">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Hoạt động</label>
                    <input type="text" name="schedule_activity[]" class="form-control schedule-activity"
                      placeholder="VD: Tham quan phố cổ, ăn trưa tại nhà hàng...">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Bản đồ</label>
                    <button type="button" class="btn btn-sm btn-primary w-100 btnMapLocation" data-bs-toggle="modal"
                      data-bs-target="#mapModal">📍 Chọn</button>
                  </div>
                </div>
              </div>
            </div>

            <button type="button" class="btn btn-sm btn-info" id="addScheduleBtn" style="margin-bottom:20px;">+ Thêm
              ngày</button>
            <div class="modal-footer">


              <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Hủy</button>
              <button type="submit" class="btn btn-save" id="saveTourBtn">Lưu Tour</button>
            </div>
          </form>
        </div>


      </div>
    </div>
  </div>

  <!-- MODAL GOOGLE MAPS -->
  <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Chọn địa điểm trên bản đồ</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Input tìm kiếm -->
          <div style="margin-bottom: 10px;">
            <input type="text" id="searchLocation" class="form-control" placeholder="Tìm kiếm địa điểm...">
          </div>

          <!-- ✅ CONTAINER BẢN ĐỒ - CẦN CÓ STYLE HEIGHT -->
          <div id="mapContainer" style="width: 100%; height: 400px; border-radius: 6px; border: 2px solid #f1e2b5;">
          </div>

          <!-- Hiển thị vị trí đã chọn -->
          <div style="margin-top: 10px; padding: 10px; background: #f9f7f0; border-radius: 6px;">
            <strong>Vị trí đã chọn:</strong> <span id="selectedLocation" style="color: #2db06b;">Chưa chọn</span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-success" id="confirmMapBtn">✓ Xác nhận</button>

        </div>
      </div>
    </div>
  </div>

  <!-- BOOTSTRAP JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ✅ LEAFLET JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

  <!-- APP JS -->
  <script>
    // Cache all tours from server
    const allTours = <?= json_encode($tours, JSON_UNESCAPED_UNICODE) ?>;

    // Toggle filter visibility
    const btnToggleFilter = document.getElementById('btnToggleFilter');
    const filterPanel = document.getElementById('filterPanel');
    let filterVisible = false;

    btnToggleFilter.addEventListener('click', () => {
      filterVisible = !filterVisible;
      filterPanel.style.display = filterVisible ? 'block' : 'none';
      btnToggleFilter.textContent = filterVisible ? '▲ Ẩn bộ lọc' : '▼ Hiện bộ lọc';
    });

    function applyFilters() {
      const selectedTypes = Array.from(document.querySelectorAll('.filterTypeCheckbox:checked')).map(c => c.value);
      const priceMin = parseFloat(document.getElementById('priceMin').value) || 0;
      const priceMax = parseFloat(document.getElementById('priceMax').value) || Infinity;

      const filtered = allTours.filter(t => {
        const matchType = selectedTypes.length === 0 || selectedTypes.includes(t.type);
        const adult = t.price && t.price.adult ? t.price.adult : 0;
        const matchPrice = adult >= priceMin && adult <= priceMax;
        return matchType && matchPrice;
      });

      renderTours(filtered);
    }

    function renderTours(list) {
      const tbody = document.getElementById('tourTableBody');
      if (!list || list.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;color:#999;">Không có tour nào</td></tr>';
        return;
      }
      tbody.innerHTML = list.map((t, idx) => {
        const img = t.images && t.images.length ? t.images[0] : '';
        const priceText = t.price && t.price.adult ? new Intl.NumberFormat('vi-VN').format(t.price.adult) + ' đ' : '—';
        return `
          <tr>
            <td>${idx + 1}</td>
            <td>${escapeHtml(t.type || '')}</td>
            <td>${escapeHtml(t.name || '')}</td>
            <td>${escapeHtml(t.main_destination || '')}</td>
            <td>${img ? '<img src="' + escapeHtml(img) + '" style="width:80px;height:50px;object-fit:cover;border-radius:4px;">' : '—'}</td>
            <td>${escapeHtml(t.tour_code || '')}</td>
            <td>${priceText}</td>
            <td>
              <form method="POST" action="${escapeHtml(window.location.pathname)}?action=deleteTour" style="display:inline">
                <input type="hidden" name="id" value="${escapeHtml(t.id || '')}">
                <button class="btn btn-sm btn-danger" type="submit" onclick="return confirm('Bạn muốn xóa?')">Xóa</button>
              </form>
            </td>
          </tr>
        `;
      }).join('');
    }

    function escapeHtml(s) {
      if (!s) return '';
      return String(s)
        .replace(/[&<>"]/g, c => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;'
        } [c] || c));
    }

    document.getElementById('btnFilter').addEventListener('click', applyFilters);
    document.getElementById('btnResetFilter').addEventListener('click', () => {
      document.querySelectorAll('.filterTypeCheckbox').forEach(c => c.checked = false);
      document.getElementById('priceMin').value = '';
      document.getElementById('priceMax').value = '';
      renderTours(allTours);
    });

    ['priceMin', 'priceMax'].forEach(id => {
      document.getElementById(id).addEventListener('keypress', e => {
        if (e.key === 'Enter') applyFilters();
      });
    });

    // Minimal client code to allow adding schedule items (keeps UI unchanged)
    (function() {
      const addBtn = document.getElementById('addScheduleBtn');
      const container = document.getElementById('scheduleContainer');
      if (!addBtn || !container) return;
      addBtn.addEventListener('click', function() {
        const node = document.createElement('div');
        node.className = 'schedule-item mb-3 p-3';
        node.style.background = '#f9f7f0';
        node.style.borderRadius = '6px';
        node.innerHTML = `
          <div class="row mb-2">
            <div class="col-md-4">
              <label class="form-label">Ngày thứ</label>
              <input type="number" name="schedule_day[]" class="form-control schedule-day" placeholder="1" min="1">
            </div>
            <div class="col-md-6">
              <label class="form-label">Hoạt động</label>
              <input type="text" name="schedule_activity[]" class="form-control schedule-activity" placeholder="VD: ...">
            </div>
            <div class="col-md-2">
              <label class="form-label">Bản đồ</label>
              <button type="button" class="btn btn-sm btn-primary w-100 btnMapLocation" data-bs-toggle="modal" data-bs-target="#mapModal">📍 Chọn</button>
            </div>
          </div>
        `;
        container.appendChild(node);
      });
    })();
  </script>


</body>

</html>