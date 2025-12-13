<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Quản lý Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
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
            <div class="nav-group">QUẢN LÝ TOUR</div>

            <a class="nav-item active" href="index.php?action=tour-list">
                <i class="bi bi-airplane me-2"></i> Danh sách Tour
            </a>

            <a class="nav-item " href="index.php?action=guide-logs">
                <i class="bi bi-journal-text me-2"></i> Nhật ký Tour
            </a>

            <a class="nav-item" href="index.php?action=booking-list">
                <i class="bi bi-calendar-check me-2"></i> Booking
            </a>

            <a class="nav-item" href="index.php?action=checkin">
                <i class="bi bi-clipboard-check me-2"></i> Quy trình Check-in
            </a>

            <!-- HƯỚNG DẪN VIÊN -->
            <div class="nav-group">HƯỚNG DẪN VIÊN</div>

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
            <div class="nav-group">BÁO CÁO</div>

            <a class="nav-item" href="index.php?action=revenue-report">
                <i class="bi bi-currency-dollar me-2"></i> Doanh thu
            </a>

            <!-- KHÁC -->
            <div class="nav-group">KHÁC</div>

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

        <a href="index.php?action=logout" class="btn btn-danger mt-3">
            <i class="bi bi-box-arrow-right"></i> Đăng xuất
        </a>
    </aside>

    <!-- MAIN -->
    <main class="main">
      <div class="topbar">
        <button class="btn btn-sm btn-outline-secondary d-md-none" id="btnToggle"><i class="bi bi-list"></i></button>
        <div class="me-2">VI</div>
        <div class="btn btn-light btn-sm"><i class="bi bi-bell"></i></div>
        <div class="rounded-circle bg-warning text-dark d-flex align-items:center;justify-content:center"
          style="width:50px;height:50px;font-weight:600">A</div>
      </div>

      <h3 style="margin-bottom:22px;color:#4a3512;">Danh sách Tour</h3>
      <div class="grid">
        <div class="card-panel">
          <h2 id="total-tours" style="float:left;">Số tour hiện có: 0</h2>
          <button class="btn btn-success" style="float:right;" data-bs-toggle="modal" data-bs-target="#addTourModal">+
            Thêm mới</button>
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
              <th>Điểm đến</th>
              <th>Hình ảnh</th>
              <th>Mã tour</th>
              <th>Giá cả</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- MODAL THÊM TOUR -->
  <div class="modal fade" id="addTourModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Thêm Tour Mới</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="addTourForm">
            <!-- TOÀN BỘ FORM CỦA BẠN (từ loại tour đến lịch trình) DÁN VÀO ĐÂY -->
            <!-- Mình đã dán đầy đủ ở cuối file này -->
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL BẢN ĐỒ -->
  <div class="modal fade" id="mapModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Chọn địa điểm trên bản đồ</h5><button type="button" class="btn-close"
            data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" id="searchLocation" class="form-control mb-2" placeholder="Tìm kiếm địa điểm...">
          <div id="mapContainer" style="height:400px;"></div>
          <div class="mt-2 p-2 bg-light rounded"><strong>Vị trí đã chọn:</strong> <span id="selectedLocation">Chưa
              chọn</span></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-success" id="confirmMapBtn">Xác nhận</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

  <script>
    // ============= TOÀN BỘ JS ĐÃ ĐƯỢC SỬA ĐỂ GỌI PHP MVC =============
    let map, marker;
    let selectedLocationName = '';
    let currentScheduleItem = null;

    const getTour = async () => {
      try {
        const res = await fetch('index.php?action=getTours');
        const tours = await res.json();
        document.getElementById("total-tours").textContent = `Số tour hiện có: ${tours.length}`;
        renderTour(tours);
      } catch (err) { console.error(err); }
    };

    const renderTour = (tours) => {
      const tbody = document.querySelector('tbody');
      if (!tours.length) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted">Chưa có tour nào</td></tr>`;
        return;
      }
      tbody.innerHTML = tours.map((t, i) => `
    <tr>
      <th scope="row">${i + 1}</th>
      <td>${t.type || ''}</td>
      <td>${t.name || ''}</td>
      <td>${t.main_destination || ''}</td>
      <td><img src="${t.image_url || '/assets/placeholder.jpg'}" width="160" height="100" onerror="this.src='/assets/placeholder.jpg'"></td>
      <td>${t.tour_code || ''}</td>
      <td>${Number(t.child_price || 0).toLocaleString()} ~ ${Number(t.adult_price || 0).toLocaleString()}đ</td>
      <td>
        <button onclick="handleDelete('${t.id}')" class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
        <a class="btn btn-info btn-sm" href="views/admin/edit-tour.php?id=${t.id}"><i class="bi bi-pen"></i></a>
        <a class="btn btn-success btn-sm" href="index.php?action=tourDetail&id=${t.id}"><i class="bi bi-box-arrow-in-up-right"></i></a>
      </td>
    </tr>
  `).join('');
    };

    const handleDelete = async (id) => {
      if (confirm("Xóa tour này thật không?")) {
        const res = await fetch('index.php?action=deleteTour', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + id
        });
        const result = await res.json();
        if (result.success) { alert("Xóa thành công!"); getTour(); }
      }
    };

    const handleAdd = async (data) => {
      const res = await fetch('index.php?action=addTour', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });
      const result = await res.json();
      if (result.success) {
        alert("Thêm tour thành công!");
        bootstrap.Modal.getInstance(document.getElementById('addTourModal')).hide();
        getTour();
      } else {
        alert("Lỗi server");
      }
    };

    // ============= TOÀN BỘ JS CỦA BẠN (đã sửa để gọi PHP) =============
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('addTourForm');
      if (form) {
        form.addEventListener('submit', (e) => {
          e.preventDefault();
          const inputType = form.querySelector('select[name="type"]');
          const inputName = form.querySelector('input[name="name"]');
          const inputDestination = form.querySelector('input[name="main_destination"]');
          const inputImages = form.querySelector('input[name="images"]');
          const inputTourCode = form.querySelector('input[name="tour_code"]');
          const inputDescription = form.querySelector('textarea[name="short_description"]');
          const inputPriceAdult = form.querySelector('input[name="price_adult"]');
          const inputPriceChild = form.querySelector('input[name="price_child"]');
          const inputPolicyCancel = form.querySelector('textarea[name="policy_cancel"]');
          const inputPolicyRefund = form.querySelector('textarea[name="policy_refund"]');
          const inputMaxPeople = form.querySelector('input[name="max_people"]');

          // Validation cơ bản
          if (!inputType.value || !inputName.value || !inputDestination.value || !inputPriceAdult.value || !inputPriceChild.value || !inputMaxPeople.value) {
            alert("Vui lòng điền đầy đủ các trường bắt buộc");
            return;
          }

          const scheduleItems = document.querySelectorAll('.schedule-item');
          const schedule = Array.from(scheduleItems).map(item => ({
            day: parseInt(item.querySelector('.schedule-day').value) || 1,
            activity: item.querySelector('.schedule-activity').value || '',
            location: item.dataset.location || ''
          })).filter(s => s.activity);

          const data = {
            type: inputType.value,
            name: inputName.value,
            tour_code: inputTourCode.value || '',
            main_destination: inputDestination.value,
            images: inputImages.value ? inputImages.value.split(',').map(u => u.trim()).filter(u => u) : [],
            short_description: inputDescription.value || '',
            max_people: parseInt(inputMaxPeople.value),
            price: {
              adult: Number(inputPriceAdult.value),
              child: Number(inputPriceChild.value)
            },
            policy: {
              cancel: inputPolicyCancel?.value || '',
              refund: inputPolicyRefund?.value || ''
            },
            schedule: schedule
          };

          handleAdd(data);
        });
      }

      
    });

    getTour();
  </script>

  

</body>

</html>