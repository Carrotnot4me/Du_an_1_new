<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Quản lý Nhật ký (Logs) và Yêu cầu đặc biệt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/list.css">
  <style>
    .date-header {
      background: linear-gradient(135deg, #f5c542 0%, #f0b429 100%);
      color: #3b2a0a;
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 8px rgba(245, 197, 66, 0.3);
    }

    .action-buttons {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .action-btn {
      padding: 10px 20px;
      border-radius: 8px;
      border: 1px solid #ddd;
      background: white;
      color: #4a3512;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .action-btn:hover {
      background: #f5c542;
      color: #3b2a0a;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .info-section {
      background: #fffdfa;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 20px;
      border: 1px solid #f1e2b5;
    }

    .info-row {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 15px;
    }

    .info-label {
      font-weight: 600;
      color: #4a3512;
      min-width: 120px;
    }

    .member-tag,
    .due-date-tag {
      background: #f0e2b1;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .due-date-urgent {
      background: #ffc107;
      color: #3b2a0a;
      font-weight: 600;
    }

    .description-box {
      background: #fafafa;
      border-left: 4px solid #f5c542;
      padding: 15px;
      border-radius: 5px;
      margin-top: 10px;
    }

    .description-item {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
      font-size: 14px;
    }

    .description-item i {
      width: 20px;
      text-align: center;
    }

    .log-card {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border: 1px solid #f1e2b5;
    }

    .log-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 1px solid #f1e2b5;
    }

    .log-images {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 10px;
    }

    .log-images img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      border: 2px solid #f1e2b5;
    }
  </style>
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

        <a class="nav-item active" href="index.php?action=guide-logs">
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

      <!-- DATE HEADER -->
      <div class="date-header">
        <div>
          <i class="bi bi-calendar3 me-2"></i>
          <span id="currentDateDisplay"><?php
                                        $selectedDate = $_GET['date'] ?? date('Y-m-d');
                                        $dayNames = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                                        $dayName = $dayNames[date('w', strtotime($selectedDate))];
                                        echo $dayName . ' (' . date('d/m/Y', strtotime($selectedDate)) . ')';
                                        ?></span>
        </div>
        <input type="date" id="datePicker" value="<?= $selectedDate ?>" class="form-control" style="width: auto; display: inline-block;">
      </div>

      <!-- TITLE -->
      <h3 style="margin-bottom:22px;color:#4a3512;">Quản lý Nhật ký Logs</h3>

      <!-- ACTION BUTTONS -->
      <div class="action-buttons">
        <button class="action-btn" data-bs-toggle="modal" data-bs-target="#addLogModal">
          <i class="bi bi-plus-circle"></i> + Thêm
        </button>
        <button class="action-btn" onclick="showLabels()">
          <i class="bi bi-tag"></i> Nhãn
        </button>
        <button class="action-btn" onclick="showTodo()">
          <i class="bi bi-check-square"></i> Việc cần làm
        </button>
        <button class="action-btn" onclick="showAttachments()">
          <i class="bi bi-paperclip"></i> Đính kèm
        </button>
        <button class="action-btn" onclick="showLocation()">
          <i class="bi bi-geo-alt"></i> Vị trí
        </button>
      </div>

      <!-- INFO SECTION -->
      <div class="info-section">
        <div class="info-row">
          <div class="info-label">
            <i class="bi bi-person me-2"></i>Thành viên:
          </div>
          <div>
            <span class="member-tag" id="assignedMembers">Chưa gán</span>
            <button class="btn btn-sm btn-outline-primary ms-2" onclick="assignMember()">
              <i class="bi bi-plus"></i> Thêm
            </button>
          </div>
        </div>
        <div class="info-row">
          <div class="info-label">
            <i class="bi bi-clock me-2"></i>Ngày hết hạn:
          </div>
          <div>
            <span class="due-date-tag" id="dueDateDisplay">Chưa đặt</span>
            <input type="datetime-local" id="dueDateInput" class="form-control d-inline-block ms-2" style="width: auto;" onchange="updateDueDate()">
          </div>
        </div>
      </div>

      <!-- DESCRIPTION SECTION -->
      <div class="info-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
          <!-- <h5 style="margin: 0; color: #4a3512;">
            <i class="bi bi-list-ul me-2"></i>Mô tả
          </h5>
          <button class="btn btn-sm btn-primary" onclick="editDescription()">Chỉnh sửa</button>
        </div> -->
        <!-- <div class="description-box" id="descriptionBox">
          <div class="description-item">
            <i class="bi bi-folder-fill" style="color: #f5c542;"></i>
            <span><strong>File:</strong> guide-log.php</span>
          </div>
          <div class="description-item">
            <i class="bi bi-box-seam" style="color: #8b6914;"></i>
            <span><strong>Dữ liệu:</strong> guidesLogs, tours</span>
          </div>
          <div class="description-item">
            <i class="bi bi-bullseye" style="color: #e91e63;"></i>
            <span><strong>Mục đích:</strong> Ghi nhật ký tour, xem logs theo ngày</span>
          </div>
          <div class="description-item">
            <i class="bi bi-folder-fill" style="color: #f5c542;"></i>
            <span><strong>File:</strong> guide-special.php</span>
          </div>
          <div class="description-item">
            <i class="bi bi-box-seam" style="color: #8b6914;"></i>
            <span><strong>Dữ liệu:</strong> notes, customers</span>
          </div>
          <div class="description-item">
            <i class="bi bi-bullseye" style="color: #e91e63;"></i>
            <span><strong>Mục đích:</strong> Xem & cập nhật yêu cầu đặc biệt của khách</span>
          </div>
          <div class="description-item mt-3">
            <i class="bi bi-arrow-right-circle" style="color: #000;"></i>
            <span><strong>Tổng quát:</strong> Tập trung vào Logs & Yêu cầu đặc biệt</span>
          </div>
        </div> -->
      </div>

      <!-- LOGS LIST -->
      <div class="card-panel">
        <h4 style="margin-bottom: 20px; color: #4a3512;">
          Danh sách Nhật ký
          <small class="text-muted">(Tổng: <?= count($logs ?? []) ?> log<?= count($logs ?? []) > 1 ? 's' : '' ?>)</small>
        </h4>
        <div id="logsList">
          <?php if (empty($logs)): ?>
            <div class="text-center text-muted py-5">
              <i class="bi bi-journal-x" style="font-size: 48px; opacity: 0.3;"></i>
              <p class="mt-3">Chưa có nhật ký nào</p>
              <p class="text-muted small">Hãy click nút "+ Thêm" để tạo nhật ký mới</p>
            </div>
          <?php else: ?>
            <?php foreach ($logs as $index => $log): ?>
              <div class="log-card">
                <div class="log-header">
                  <div>
                    <h5 style="margin: 0; color: #4a3512;">
                      <?= htmlspecialchars($log->tour_name ?? 'Tour không xác định') ?>
                      <small class="text-muted">(Ngày <?= $log->day ?? 1 ?>)</small>
                    </h5>
                    <small class="text-muted">
                      <i class="bi bi-person"></i> <?= htmlspecialchars($log->guide_name ?? 'Chưa gán') ?>
                      <?php if ($log->dateStart): ?>
                        | <i class="bi bi-calendar"></i> <?= date('d/m/Y', strtotime($log->dateStart)) ?>
                      <?php endif; ?>
                    </small>
                  </div>
                  <div>
                    <button class="btn btn-sm btn-primary" onclick="editLog('<?= $log->id ?>')">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteLog('<?= $log->id ?>')">
                      <i class="bi bi-trash3"></i>
                    </button>
                  </div>
                </div>
                <div class="log-content">
                  <p style="color: #4a3512; white-space: pre-wrap;"><?= htmlspecialchars($log->content ?? '') ?></p>
                  <?php if (!empty($log->images)): ?>
                    <div class="log-images">
                      <?php foreach ($log->images as $img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" alt="Log image" onerror="this.src='/assets/placeholder.jpg'">
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </main>
  </div>

  <!-- MODAL THÊM/CHỈNH SỬA LOG -->
  <div class="modal fade" id="addLogModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logModalTitle">Thêm nhật ký mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="logForm">
            <input type="hidden" id="logId" name="logId">
            <div class="mb-3">
              <label for="logTourId" class="form-label">Tour</label>
              <select class="form-select" id="logTourId" name="tourId" required>
                <option value="">-- Chọn Tour --</option>
                <?php foreach ($tours ?? [] as $tour): ?>
                  <option value="<?= htmlspecialchars($tour->id) ?>">
                    <?= htmlspecialchars($tour->name) ?> (<?= htmlspecialchars($tour->type) ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="logGuideId" class="form-label">Hướng dẫn viên</label>
              <select class="form-select" id="logGuideId" name="guideId">
                <option value="">-- Chọn HDV --</option>
                <?php foreach ($guides ?? [] as $guide): ?>
                  <option value="<?= htmlspecialchars($guide->id) ?>">
                    <?= htmlspecialchars($guide->name) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="logDay" class="form-label">Ngày thứ</label>
              <input type="number" class="form-control" id="logDay" name="day" value="1" min="1" required>
            </div>
            <div class="mb-3">
              <label for="logContent" class="form-label">Nội dung nhật ký</label>
              <textarea class="form-control" id="logContent" name="content" rows="6" required placeholder="Ghi lại các hoạt động, sự kiện trong tour..."></textarea>
            </div>
            <div class="mb-3">
              <label for="logImages" class="form-label">Hình ảnh (URL, cách nhau bằng dấu phẩy)</label>
              <input type="text" class="form-control" id="logImages" name="images" placeholder="https://example.com/image1.jpg, https://example.com/image2.jpg">
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

      // Date picker change
      document.getElementById('datePicker')?.addEventListener('change', function() {
        const selectedDate = this.value;
        window.location.href = `index.php?action=guide-logs&date=${selectedDate}`;
      });
    });

    // Date display update
    function updateDateDisplay() {
      const datePicker = document.getElementById('datePicker');
      const display = document.getElementById('currentDateDisplay');
      if (datePicker && display) {
        const date = new Date(datePicker.value);
        const dayNames = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
        const dayName = dayNames[date.getDay()];
        const formatted = `${dayName} (${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()})`;
        display.textContent = formatted;
      }
    }

    // Action buttons
    function showLabels() {
      alert('Tính năng Nhãn đang được phát triển');
    }

    function showTodo() {
      alert('Tính năng Việc cần làm đang được phát triển');
    }

    function showAttachments() {
      alert('Tính năng Đính kèm đang được phát triển');
    }

    function showLocation() {
      alert('Tính năng Vị trí đang được phát triển');
    }

    function assignMember() {
      alert('Tính năng Gán thành viên đang được phát triển');
    }

    function updateDueDate() {
      const input = document.getElementById('dueDateInput');
      const display = document.getElementById('dueDateDisplay');
      if (input && display && input.value) {
        const date = new Date(input.value);
        const formatted = date.toLocaleString('vi-VN', {
          day: 'numeric',
          month: 'short',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        });
        display.textContent = formatted;
        display.classList.add('due-date-urgent');
      }
    }

    function editDescription() {
      alert('Tính năng Chỉnh sửa mô tả đang được phát triển');
    }

    // Log CRUD
    async function editLog(id) {
      try {
        const res = await fetch(`index.php?action=getGuideLog&id=${id}`);
        const log = await res.json();

        if (log) {
          document.getElementById('logId').value = log.id;
          document.getElementById('logTourId').value = log.tourId || '';
          document.getElementById('logGuideId').value = log.guideId || '';
          document.getElementById('logDay').value = log.day || 1;
          document.getElementById('logContent').value = log.content || '';
          document.getElementById('logImages').value = (log.images || []).join(', ');
          document.getElementById('logModalTitle').textContent = 'Chỉnh sửa nhật ký';

          const modal = new bootstrap.Modal(document.getElementById('addLogModal'));
          modal.show();
        }
      } catch (err) {
        console.error('Error:', err);
        alert('Không thể tải thông tin nhật ký');
      }
    }

    async function deleteLog(id) {
      if (!confirm('Bạn có chắc chắn muốn xóa nhật ký này?')) {
        return;
      }

      try {
        const formData = new FormData();
        formData.append('id', id);

        const res = await fetch('index.php?action=deleteGuideLog', {
          method: 'POST',
          body: formData
        });

        const result = await res.json();

        if (result.success) {
          alert('Xóa nhật ký thành công!');
          window.location.reload();
        } else {
          alert('Có lỗi xảy ra khi xóa nhật ký');
        }
      } catch (err) {
        console.error('Error:', err);
        alert('Có lỗi xảy ra khi kết nối đến server');
      }
    }

    // Form submit
    document.getElementById('logForm')?.addEventListener('submit', async (e) => {
      e.preventDefault();

      const logId = document.getElementById('logId').value;
      const tourId = document.getElementById('logTourId').value;
      const guideId = document.getElementById('logGuideId').value;
      const day = document.getElementById('logDay').value;
      const content = document.getElementById('logContent').value;
      const imagesInput = document.getElementById('logImages').value;

      if (!tourId || !content) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc');
        return;
      }

      const images = imagesInput ? imagesInput.split(',').map(url => url.trim()).filter(url => url) : [];

      const data = {
        tourId: tourId,
        guideId: guideId || null,
        day: parseInt(day),
        content: content,
        images: images
      };

      if (logId) {
        data.id = logId;
      }

      try {
        const action = logId ? 'updateGuideLog' : 'addGuideLog';
        const res = await fetch(`index.php?action=${action}`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(data)
        });

        const result = await res.json();

        if (result.success) {
          alert(logId ? 'Cập nhật nhật ký thành công!' : 'Thêm nhật ký thành công!');
          bootstrap.Modal.getInstance(document.getElementById('addLogModal')).hide();
          window.location.reload();
        } else {
          alert(result.message || 'Có lỗi xảy ra khi lưu nhật ký');
        }
      } catch (err) {
        console.error('Error:', err);
        alert('Có lỗi xảy ra khi kết nối đến server');
      }
    });

    // Reset form when modal is closed
    const modal = document.getElementById('addLogModal');
    if (modal) {
      modal.addEventListener('hidden.bs.modal', () => {
        document.getElementById('logForm').reset();
        document.getElementById('logId').value = '';
        document.getElementById('logModalTitle').textContent = 'Thêm nhật ký mới';
      });
    }
  </script>
</body>

</html>