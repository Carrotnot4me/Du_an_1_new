<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sửa Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
  <link rel="stylesheet" href="./assets/list.css">
</head>

<body>
  <div class="app">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <div class="logo">
        <div
          style="width:44px;height:44px;background:var(--accent);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3b2a0a;font-weight:700">
          AD</div>
        <div>
          <div>AdminPanel</div>
          <small style="opacity:.8">v1.0</small>
        </div>
      </div>

      <nav>

        <!-- TRANG CHÍNH -->
        <a class="nav-item " href="index.php?action=dashboard">
          <i class="bi bi-house-door-fill me-2"></i> Trang quản trị
        </a>

        <!-- QUẢN LÝ TOUR -->

        <a class="nav-item active" href="index.php?action=tour-list">
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

        

        <a class="nav-item" href="index.php?action=special-notes">
          <i class="bi bi-sticky me-2"></i> Ghi chú
        </a>

      </nav>

      <div style="margin-top:auto; font-size:13px; opacity:.9">
        <div>Người dùng: <strong>Admin</strong></div>
        <div style="margin-top:6px">Email: <small>tatruong@example.com</small></div>
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
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="?action=logout">🚪 Đăng xuất</a></li>
          </ul>
        </div>
      </div>

      <h3 style="margin-bottom:22px; color:#4a3512;">Sửa Tour</h3>

      <div class="card-panel" style="max-width:1350px;">
        <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=updateTour" id="editTourForm">
          <input type="hidden" name="id" value="<?= htmlspecialchars($tour['id'] ?? '') ?>">

          <!-- THÔNG TIN CƠ BẢN -->
          <h6
            style="color:#3b2a0a; font-weight:700; margin-bottom:15px; border-left:5px solid #f5c542; padding-left:10px;">
            Thông tin cơ bản</h6>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Loại Tour <span style="color:red;">*</span></label>
              <select class="form-select" name="type" required>
                <option value="">-- Chọn loại tour --</option>
                <option value="Nội địa" <?= ($tour['type'] ?? '') === 'Nội địa' ? 'selected' : '' ?>>Nội địa</option>
                <option value="Quốc tế" <?= ($tour['type'] ?? '') === 'Quốc tế' ? 'selected' : '' ?>>Quốc tế</option>
                <option value="Theo yêu cầu" <?= ($tour['type'] ?? '') === 'Theo yêu cầu' ? 'selected' : '' ?>>Theo yêu cầu</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Tên Tour <span style="color:red;">*</span></label>
              <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($tour['name'] ?? '') ?>" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Mã Tour (Tour Code) <span style="color:red;">*</span></label>
              <input type="text" class="form-control" name="tour_code" value="<?= htmlspecialchars($tour['tour_code'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Điểm đến chính <span style="color:red;">*</span></label>
              <input type="text" class="form-control" name="main_destination" value="<?= htmlspecialchars($tour['main_destination'] ?? '') ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Ảnh (URL, cách nhau bằng dấu phẩy)</label>
              <input type="text" class="form-control" name="images" value="<?= htmlspecialchars(implode(', ', $tour['images'] ?? [])) ?>">
            </div>
          </div>

          <div class="col-md-4 mt-3">
            <label class="form-label">Số người tối đa</label>
            <input type="number" class="form-control" name="max_people" value="<?= htmlspecialchars($tour['max_people'] ?? '') ?>">
          </div>

          <div class="mb-3">
            <label class="form-label">Mô tả ngắn</label>
            <textarea class="form-control" name="short_description" rows="2"><?= htmlspecialchars($tour['short_description'] ?? '') ?></textarea>
          </div>

          <!-- GIÁ TIỀN -->
          <h6
            style="color:#3b2a0a; font-weight:700; margin-bottom:15px; margin-top:25px; border-left:5px solid #f5c542; padding-left:10px;">
            Giá tiền</h6>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Giá người lớn (VNĐ) <span style="color:red;">*</span></label>
              <input type="number" class="form-control" name="price_adult" value="<?= htmlspecialchars($tour['price']['adult'] ?? 0) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Giá trẻ em (VNĐ) <span style="color:red;">*</span></label>
              <input type="number" class="form-control" name="price_child" value="<?= htmlspecialchars($tour['price']['child'] ?? 0) ?>" required>
            </div>
          </div>

          <!-- CHÍNH SÁCH -->
          <h6
            style="color:#3b2a0a; font-weight:700; margin-bottom:15px; margin-top:25px; border-left:5px solid #f5c542; padding-left:10px;">
            Chính sách</h6>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Chính sách hủy</label>
              <textarea class="form-control" name="policy_cancel" rows="2"><?= htmlspecialchars($tour['policy']['cancel'] ?? '') ?></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Chính sách hoàn tiền</label>
              <textarea class="form-control" name="policy_refund" rows="2"><?= htmlspecialchars($tour['policy']['refund'] ?? '') ?></textarea>
            </div>
          </div>

          <!-- LỊCH TRÌNH -->
          <div style="display:flex; align-items:center; gap:10px; margin-top:25px;">
            <h6 style="color:#3b2a0a; font-weight:700; margin-bottom:0; border-left:5px solid #f5c542; padding-left:10px;">Lịch trình</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="toggleScheduleBtn">Hiển thị / Ẩn lịch trình</button>
          </div>
          <div id="scheduleContainer" style="display:none; margin-top:10px;">
            <input type="hidden" name="schedule_details_json" id="scheduleDetailsJson" value='<?= htmlspecialchars(json_encode(array_map(function($s){ return $s['details'] ?? []; }, $tour['schedule'] ?? []), JSON_UNESCAPED_UNICODE)) ?>'>
            <?php if (!empty($tour['schedule'])): ?>
              <?php foreach ($tour['schedule'] as $index => $sch): ?>
                <div class="schedule-item mb-3 p-3" data-schedule-index="<?= $index ?>" style="background:#f9f7f0; border-radius:6px;">
                  <div class="row mb-2">
                    <div class="col-md-4">
                      <label class="form-label">Tuần thứ</label>
                      <input type="number" name="schedule_day[]" class="form-control schedule-day" value="<?= htmlspecialchars($sch['day'] ?? 1) ?>" min="1">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Hoạt động</label>
                      <input type="text" name="schedule_activity[]" class="form-control schedule-activity" value="<?= htmlspecialchars($sch['activity'] ?? '') ?>">
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Hành động</label>
                      <button type="button" class="btn btn-sm btn-danger w-100 btnRemoveSchedule">🗑️ Xóa</button>
                    </div>
                  </div>
                  <div class="mb-2">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                      <strong>Chi tiết lịch trình</strong>
                      <button type="button" class="btn btn-sm btn-outline-primary btnAddDetail">+ Thêm chi tiết</button>
                    </div>
                    <div class="schedule-details mt-2">
                      <?php if (!empty($sch['details'])): ?>
                        <?php foreach ($sch['details'] as $det): ?>
                          <div class="detail-row mb-2" style="display:flex;gap:8px;">
                            <input type="time" class="form-control form-control-sm detail-start" value="<?= htmlspecialchars($det['start_time'] ?? '') ?>">
                            <input type="time" class="form-control form-control-sm detail-end" value="<?= htmlspecialchars($det['end_time'] ?? '') ?>">
                            <input type="text" class="form-control form-control-sm detail-content" placeholder="Nội dung" value="<?= htmlspecialchars($det['content'] ?? '') ?>">
                            <button type="button" class="btn btn-sm btn-danger btnRemoveDetail">✖</button>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="schedule-item mb-3 p-3" data-schedule-index="0" style="background:#f9f7f0; border-radius:6px;">
                <div class="row mb-2">
                  <div class="col-md-4">
                    <label class="form-label">Tuần thứ</label>
                    <input type="number" name="schedule_day[]" class="form-control schedule-day" placeholder="1" min="1">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Hoạt động</label>
                    <input type="text" name="schedule_activity[]" class="form-control schedule-activity" placeholder="VD: Tham quan phố cổ...">
                  </div>
                  <div class="col-md-2">
                    <label class="form-label">Hành động</label>
                    <button type="button" class="btn btn-sm btn-danger w-100 btnRemoveSchedule">🗑️ Xóa</button>
                  </div>
                </div>
                <div class="mb-2">
                  <div style="display:flex; align-items:center; justify-content:space-between;">
                    <strong>Chi tiết lịch trình</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary btnAddDetail">+ Thêm chi tiết</button>
                  </div>
                  <div class="schedule-details mt-2"></div>
                </div>
              </div>
            <?php endif; ?>
          </div>

          <button type="button" class="btn btn-sm btn-info" id="addScheduleBtn" style="margin-bottom:20px; display:none;">+ Thêm ngày</button>

          <!-- DEPARTURES -->
          <div style="display:flex; align-items:center; gap:10px; margin-top:10px;">
            <h6 style="color:#3b2a0a; font-weight:700; margin-bottom:0; border-left:5px solid #f5c542; padding-left:10px;">Đợt khởi hành</h6>
            <button type="button" class="btn btn-sm btn-outline-primary" id="toggleDeparturesBtn">Hiển thị / Ẩn đợt khởi hành</button>
          </div>
          <div id="departuresContainer" style="display:none; margin-top:10px;">
            <?php if (!empty($departures)): ?>
              <div class="list-group">
                  <?php foreach ($departures as $dep): ?>
                    <div class="schedule-item mb-3 p-3" style="background:#f9f7f0; border-radius:6px;">
                      <strong><?= htmlspecialchars(isset($dep['dateStart']) ? date('d/m/Y', strtotime($dep['dateStart'])) : ($dep['dateStart'] ?? '')) ?><?php if (!empty($dep['dateEnd'])) echo ' → ' . htmlspecialchars(date('d/m/Y', strtotime($dep['dateEnd']))); ?></strong>
                      <div style="margin-top:6px;">Điểm tập trung: <?= htmlspecialchars($dep['meetingPoint'] ?? $dep['meeting_point'] ?? 'Chưa đặt') ?></div>
                      <?php if (!empty($dep['driver']) || !empty($dep['guideId'])): ?>
                        <div style="margin-top:6px; font-size:0.95rem; color:#555">Guide: <?= htmlspecialchars($dep['guideId'] ?? '') ?> — Driver: <?= htmlspecialchars($dep['driver'] ?? $dep['driverId'] ?? '') ?></div>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div class="alert alert-secondary">Chưa có đợt khởi hành nào cho tour này.</div>
              <?php endif; ?>
          </div>

          <!-- BUTTONS -->
          <div style="margin-top:25px; display:flex; gap:10px;">
            <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=tour-list" class="btn btn-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-success">✓ Cập nhật Tour</button>
          </div>
        </form>
      </div>
    </main>
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
          <div style="margin-bottom: 10px;">
            <input type="text" id="searchLocation" class="form-control" placeholder="Tìm kiếm địa điểm...">
          </div>
          <div id="mapContainer" style="width: 100%; height: 400px; border-radius: 6px; border: 2px solid #f1e2b5;"></div>
          <div style="margin-top: 10px; padding: 10px; background: #f9f7f0; border-radius: 6px;">
            <strong>Vị trí đã chọn:</strong> <span id="selectedLocation" style="color: #2db06b;">Chưa chọn</span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-success" id="confirmMapBtn">✓ Xác nhận</button>
          <button type="button" class="btn btn-primary" id="createBookingBtn">📋 Tạo booking</button>
        </div>
      </div>
    </div>
  </div>

  <!-- BOOTSTRAP JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

  <!-- APP JS -->
  <script>
    (function(){
      const addBtn = document.getElementById('addScheduleBtn');
      const container = document.getElementById('scheduleContainer');
      if (!addBtn || !container) return;
      
      addBtn.addEventListener('click', function(){
        const node = document.createElement('div');
        node.className = 'schedule-item mb-3 p-3';
        node.style.background = '#f9f7f0';
        node.style.borderRadius = '6px';
        node.innerHTML = `
          <div class="row mb-2">
            <div class="col-md-4">
              <label class="form-label">Tuần thứ</label>
              <input type="number" name="schedule_day[]" class="form-control schedule-day" placeholder="1" min="1">
            </div>
            <div class="col-md-6">
              <label class="form-label">Hoạt động</label>
              <input type="text" name="schedule_activity[]" class="form-control schedule-activity" placeholder="VD: ...">
            </div>
            <div class="col-md-2">
              <label class="form-label">Hành động</label>
              <button type="button" class="btn btn-sm btn-danger w-100 btnRemoveSchedule">🗑️ Xóa</button>
            </div>
          </div>
          <div class="mb-2">
            <div style="display:flex; align-items:center; justify-content:space-between;">
              <strong>Chi tiết lịch trình</strong>
              <button type="button" class="btn btn-sm btn-outline-primary btnAddDetail">+ Thêm chi tiết</button>
            </div>
            <div class="schedule-details mt-2"></div>
          </div>
        `;
        container.appendChild(node);
        attachRemoveHandler(node.querySelector('.btnRemoveSchedule'));
        attachDetailHandlers(node);
      });

      // Attach remove handler to existing and new buttons
      function attachRemoveHandler(btn) {
        btn.addEventListener('click', function(e){
          e.preventDefault();
          const scheduleItem = btn.closest('.schedule-item');
          if (scheduleItem) {
            scheduleItem.remove();
          }
        });
      }

      // Attach to existing remove buttons
      document.querySelectorAll('.btnRemoveSchedule').forEach(btn => {
        attachRemoveHandler(btn);
      });

      // Detail handlers: add/remove detail rows inside a schedule-item
      function attachDetailHandlers(root) {
        if (!root) return;
        // add detail button
        const addBtn = root.querySelector('.btnAddDetail');
        if (addBtn) {
          addBtn.addEventListener('click', function(e){
            e.preventDefault();
            const detailsContainer = root.querySelector('.schedule-details');
            if (!detailsContainer) return;
            const row = document.createElement('div');
            row.className = 'detail-row mb-2';
            row.style.display = 'flex'; row.style.gap = '8px';
            row.innerHTML = `<input type="time" class="form-control form-control-sm detail-start">` +
                            `<input type="time" class="form-control form-control-sm detail-end">` +
                            `<input type="text" class="form-control form-control-sm detail-content" placeholder="Nội dung">` +
                            `<button type="button" class="btn btn-sm btn-danger btnRemoveDetail">✖</button>`;
            detailsContainer.appendChild(row);
            attachRemoveDetail(row.querySelector('.btnRemoveDetail'));
          });
        }
        // attach remove handlers for existing detail remove buttons
        root.querySelectorAll('.btnRemoveDetail').forEach(b => attachRemoveDetail(b));
      }

      function attachRemoveDetail(btn) {
        if (!btn) return;
        btn.addEventListener('click', function(e){
          e.preventDefault();
          const row = btn.closest('.detail-row');
          if (row) row.remove();
        });
      }

      // attach detail handlers to existing schedule items
      document.querySelectorAll('.schedule-item').forEach(item => attachDetailHandlers(item));

      // before submit, serialize details into hidden input
      const editForm = document.getElementById('editTourForm');
      if (editForm) {
        editForm.addEventListener('submit', function(e){
          const all = [];
          document.querySelectorAll('.schedule-item').forEach(si => {
            const dets = [];
            si.querySelectorAll('.detail-row').forEach(dr => {
              const start = dr.querySelector('.detail-start') ? dr.querySelector('.detail-start').value : '';
              const end = dr.querySelector('.detail-end') ? dr.querySelector('.detail-end').value : '';
              const content = dr.querySelector('.detail-content') ? dr.querySelector('.detail-content').value : '';
              if (start || end || content) dets.push({start_time: start ? start : null, end_time: end ? end : null, content: content});
            });
            all.push(dets);
          });
          const hidden = document.getElementById('scheduleDetailsJson');
          if (hidden) hidden.value = JSON.stringify(all);
        });
      }
      
      // Toggle schedule display like filter
      const toggleScheduleBtn = document.getElementById('toggleScheduleBtn');
      const toggleDeparturesBtn = document.getElementById('toggleDeparturesBtn');
      const departuresContainer = document.getElementById('departuresContainer');
      function showSchedule(show) {
        if (show) {
          container.style.display = '';
          addBtn.style.display = '';
        } else {
          container.style.display = 'none';
          addBtn.style.display = 'none';
        }
      }
      if (toggleScheduleBtn) {
        toggleScheduleBtn.addEventListener('click', function(){
          const visible = container.style.display !== 'none';
          showSchedule(!visible);
        });
      }

      if (toggleDeparturesBtn && departuresContainer) {
        toggleDeparturesBtn.addEventListener('click', function(){
          const visible = departuresContainer.style.display !== 'none';
          departuresContainer.style.display = visible ? 'none' : '';
        });
      }
    })();
  </script>

  <script>
    (function(){
      const createBookingBtn = document.getElementById('createBookingBtn');
      if (createBookingBtn) {
        createBookingBtn.addEventListener('click', function(){
          // Submit the edit form to duplicateTour so a new tour (copy) will be created
          const form = document.getElementById('editTourForm');
          if (form) {
            form.action = '<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=duplicateTour';
            form.submit();
          }
        });
      }
    })();
  </script>

  <!-- Departure Edit Modal -->
  <div class="modal fade" id="departureEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="departureEditForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=updateDeparture">
          <div class="modal-header">
            <h5 class="modal-title">Sửa đợt khởi hành</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="departure_id" id="edit_dep_id">
            <input type="hidden" name="tour_id" value="<?= htmlspecialchars($tour['id'] ?? '') ?>">
            <div class="mb-2">
              <label class="form-label">Ngày bắt đầu</label>
              <input type="date" class="form-control" name="dateStart" id="edit_dateStart">
            </div>
            <div class="mb-2">
              <label class="form-label">Ngày kết thúc</label>
              <input type="date" class="form-control" name="dateEnd" id="edit_dateEnd">
            </div>
            <div class="mb-2">
              <label class="form-label">Điểm tập trung</label>
              <input type="text" class="form-control" name="meetingPoint" id="edit_meetingPoint">
            </div>
            <div class="mb-2">
              <label class="form-label">Guide ID</label>
              <input type="text" class="form-control" name="guideId" id="edit_guideId">
            </div>
            <div class="mb-2">
              <label class="form-label">Driver</label>
              <input type="text" class="form-control" name="driver" id="edit_driver">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-success">Lưu</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const editButtons = document.querySelectorAll('.btnEditDeparture');
      const editModalEl = document.getElementById('departureEditModal');
      const editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;
      if (!editModal) return;

      editButtons.forEach(btn => {
        btn.addEventListener('click', function(){
          document.getElementById('edit_dep_id').value = btn.getAttribute('data-id') || '';
          document.getElementById('edit_dateStart').value = btn.getAttribute('data-datestart') || '';
          document.getElementById('edit_dateEnd').value = btn.getAttribute('data-dateend') || '';
          document.getElementById('edit_meetingPoint').value = btn.getAttribute('data-meetingpoint') || '';
          document.getElementById('edit_guideId').value = btn.getAttribute('data-guideid') || '';
          document.getElementById('edit_driver').value = btn.getAttribute('data-driver') || '';
          editModal.show();
        });
      });
    })();
  </script>

</body>

</html>