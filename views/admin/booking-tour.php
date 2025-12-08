<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Đặt Tour</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/list.css">
</head>

<body>
  <div class="app">
    <aside class="sidebar" id="sidebar">
      <div class="logo">
        <img src="<?= htmlspecialchars($_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=User&background=random') ?>" style="width:44px;height:44px;border-radius:8px;object-fit:cover;" />
        <div>
          <div>AdminPanel</div>
          <small style="opacity:.8">v1.0</small>
        </div>
      </div>
      <nav>
        <div class="nav-item"><i class="bi bi-house-door-fill"></i><a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=trangchu">Trang quản trị</a></div>
        <div class="nav-item"><i class="bi bi-airplane"></i><a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=tour-list"> Quản lý Tour</a></div>
        <div class="nav-item active"><i class="bi bi-calendar-check"></i><a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=booking"> Đặt chỗ</a></div>
      </nav>
    </aside>

    <main class="main">
      <div class="topbar">
        <button class="btn btn-sm btn-outline-secondary d-md-none" id="btnToggle"><i class="bi bi-list"></i></button>
        <div class="me-2">VI</div>
      </div>

      <div class="container-fluid">
        <h3 class="my-3">Tạo booking mới</h3>

        <?php if (!empty($_SESSION['flash_ok'])): ?>
          <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_ok']) ?></div>
          <?php unset($_SESSION['flash_ok']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['flash_error'])): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
          <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div class="card mb-3 p-3">
          <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?action=addBooking">
            <div class="row">
              <div class="col-md-6">
                <label class="form-label">Chọn Tour</label>
                <select id="tourSelect" name="tourId" class="form-select" required>
                  <option value="">-- Chọn tour --</option>
                  <?php if (!empty($tours)): foreach ($tours as $t): ?>
                    <option value="<?= htmlspecialchars($t['id']) ?>"><?= htmlspecialchars($t['name']) ?> (<?= htmlspecialchars($t['tour_code']) ?>)</option>
                  <?php endforeach; endif; ?>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Chọn Hướng dẫn viên</label>
                <select id="staffSelect" name="guideId" class="form-select">
                  <option value="">-- Không chọn --</option>
                  <?php if (!empty($staffs)): foreach ($staffs as $s): ?>
                    <option value="<?= htmlspecialchars($s['id']) ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['type']) ?>)</option>
                  <?php endforeach; endif; ?>
                </select>
              </div>
            </div>

            

            <div class="mt-4">
              <h5>Danh sách người đăng ký</h5>
              <p class="text-muted">Người đứng ra đăng ký = liên hệ ở trên.</p>

              


<div class="row mt-3">
              <div class="col-md-4">
                <label class="form-label">Email người liên hệ</label>
                <input type="email" name="contact_email" id="contact_email" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">Phone người liên hệ</label>
                <input type="text" name="contact_phone" id="contact_phone" class="form-control" required>
              </div>
              <div class="col-md-2">
                <label class="form-label">Số lượng</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control">
              </div>
              <div class="col-md-2">
                <label class="form-label">Loại chuyến</label>
                <select name="travelType" class="form-select">
                  <option value="Gia đình">Gia đình</option>
                  <option value="Bạn bè">Bạn bè</option>
                  <option value="Cá nhân">Cá nhân</option>
                </select>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-4">
                <label class="form-label">Loại khách</label>
                <select name="type" class="form-select">
                  <option value="Khách lẻ">Khách lẻ</option>
                  <option value="Đoàn">Đoàn</option>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label">Đợt khởi hành</label>
                <select id="departureSelect" name="departureDate" class="form-select">
                  <option value="">-- Chọn đợt (nếu có) --</option>
                </select>
              </div>
<div class="col-md-4">
  <label class="form-label">Ghi chú khách hàng</label>
  <input type="text" name="customer_note" id="customer_note" class="form-control" placeholder="Nhập ghi chú...">
</div>


              <div id="registrantsContainer"></div>

              <button type="button" id="btnAddRegistrant" class="btn btn-sm btn-outline-primary">+ Thêm người</button>
            </div>

            <div class="mt-4">
              <h5>Lịch trình</h5>
              <div id="scheduleContainer" class="p-2" style="background:#f9f7f0;border-radius:6px;min-height:60px;">Chưa chọn tour</div>
            </div>

            <div class="mt-4 text-end">
              <button type="submit" class="btn btn-success">Lưu booking</button>
            </div>

          </form>
        </div>
      </div>

    </main>
  </div>

  <script>
    const tours = <?= json_encode($tours ?? [], JSON_UNESCAPED_UNICODE) ?>;

    const tourSelect = document.getElementById('tourSelect');
    const departureSelect = document.getElementById('departureSelect');
    const scheduleContainer = document.getElementById('scheduleContainer');
   

    function findTour(id) {
      return tours.find(x => String(x.id) === String(id));
    }

    function renderSchedule(t) {
      if (!t || !t.schedule || t.schedule.length === 0) {
        scheduleContainer.innerHTML = '<small style="color:#666">Không có lịch trình.</small>';
        return;
      }
      let html = '<ol style="margin-left:18px;">';
      t.schedule.forEach((s, i) => {
        html += '<li>' + (s.title ? ('<strong>' + (s.title) + ':</strong> ') : '') + (s.activity || '') + '</li>';
      });
      html += '</ol>';
      scheduleContainer.innerHTML = html;
    }

    function populateDepartureOptions(t) {
      departureSelect.innerHTML = '<option value="">-- Chọn đợt (nếu có) --</option>';
      if (!t || !t.departures) return;
      t.departures.forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.dateStart || '';
        opt.textContent = d.dateStart ? (d.dateStart + (d.meetingPoint ? (' — ' + d.meetingPoint) : '')) : ('Đợt ' + (d.id || ''));
        departureSelect.appendChild(opt);
      });
    }

   
    tourSelect && tourSelect.addEventListener('change', () => {
      const t = findTour(tourSelect.value);
      renderSchedule(t);
      populateDepartureOptions(t);
      
    });


    // Registrants UI
    // Registrants UI
const registrantsContainer = document.getElementById('registrantsContainer');
const btnAddRegistrant = document.getElementById('btnAddRegistrant');

function createRegistrantRow(data = {}) {
  const row = document.createElement('div');
  row.className = 'registrant-row border rounded p-3 mb-3';
  row.innerHTML = `
    <div class="row g-3">

      <div class="col-md-3">
        <label class="form-label">Họ tên</label>
        <input type="text" name="reg_name[]" class="form-control"
               placeholder="Nhập họ tên..."
               value="${data.name ?? ''}">
      </div>

      <div class="col-md-3">
        <label class="form-label">Email</label>
        <input type="email" name="reg_email[]" class="form-control"
               placeholder="Nhập email..."
               value="${data.email ?? ''}">
      </div>

      <div class="col-md-3">
        <label class="form-label">SĐT</label>
        <input type="text" name="reg_phone[]" class="form-control"
               placeholder="Nhập số điện thoại..."
               value="${data.phone ?? ''}">
      </div>

      <div class="col-md-2">
        <label class="form-label">Ghi chú</label>
        <input type="text" name="reg_note[]" class="form-control"
               placeholder="Ghi chú..."
               value="${data.note ?? ''}">
      </div>

      <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-danger w-100 btnRemoveRegistrant">–</button>
      </div>

    </div>
  `;

  // nút xoá
  row.querySelector('.btnRemoveRegistrant').addEventListener('click', () => row.remove());
  return row;
}

// thêm người mới
btnAddRegistrant.addEventListener('click', () => {
  registrantsContainer.appendChild(createRegistrantRow());
});

// copy email + phone của người liên hệ vào người đầu tiên
const contactEmail = document.getElementById('contact_email');
const contactPhone = document.getElementById('contact_phone');

function syncContactToFirstRegistrant() {
  const first = registrantsContainer.querySelector('.registrant-row');
  if (!first) return;

  const emailField = first.querySelector('input[name="reg_email[]"]');
  const phoneField = first.querySelector('input[name="reg_phone[]"]');

  if (emailField && !emailField.value) emailField.value = contactEmail.value;
  if (phoneField && !phoneField.value) phoneField.value = contactPhone.value;
}
gggg
contactEmail.addEventListener('input', syncContactToFirstRegistrant);
contactPhone.addEventListener('input', syncContactToFirstRegistrant);

// Khi load trang → tự tạo mẫu đầy đủ
document.addEventListener('DOMContentLoaded', () => {
  if (!registrantsContainer.querySelector('.registrant-row')) {
    registrantsContainer.appendChild(
      createRegistrantRow({
        email: contactEmail.value,
        phone: contactPhone.value
      })
    );
  }
  syncContactToFirstRegistrant();
});


  </script>

</body>

</html>
