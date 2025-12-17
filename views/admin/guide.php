<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Danh sách Hướng dẫn viên</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/list.css">
  <style>
    /* Card HDV */
    .guide-card {
      background: #fffdfa;
      border-radius: 12px;
      padding: 22px;
      height: 100%;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      border: 1px solid #f1e2b5;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
    }
    .guide-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 24px rgba(0,0,0,0.14);
    }
    .guide-name {
      font-size: 1.35rem;
      font-weight: 700;
      color: #4a3512;
      margin-bottom: 14px;
      border-left: 5px solid var(--accent);
      padding-left: 12px;
    }
    .guide-info-row {
      margin-bottom: 10px;
      font-size: 0.98rem;
      color: #4a3512;
    }
    .guide-info-row strong {
      color: #3b2a0a;
    }
    .badge-available {
      background: #d4edda;
      color: #155724;
      padding: 6px 12px;
      border-radius: 6px;
    }
    .badge-busy {
      background: #f8d7da;
      color: #721c24;
      padding: 6px 12px;
      border-radius: 6px;
    }
    .guide-actions {
      margin-top: auto;
      padding-top: 18px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    /* Div trắng bao quanh toàn bộ card */
    .guides-white-container {
      background: #ffffff;
      border-radius: 16px;
      padding: 32px;
      margin-top: 24px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      border: 1px solid #e8d9b8;
    }

    /* Avatar trong modal chi tiết */
    .guide-detail-avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #f5c542;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      float: left;
      margin-right: 24px;
      margin-bottom: 16px;
    }
    .guide-detail-info {
      overflow: hidden;
    }
    .guide-detail-info p {
      margin-bottom: 14px;
      font-size: 1.05rem;
      color: #4a3512;
    }
    .guide-detail-info strong {
      color: #3b2a0a;
      min-width: 130px;
      display: inline-block;
    }
    @media (max-width: 576px) {
      .guide-detail-avatar {
        width: 100px;
        height: 100px;
        float: none;
        display: block;
        margin: 0 auto 20px auto;
      }
      .guide-detail-info {
        text-align: center;
      }
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
        <div>Người dùng: <strong><?php echo htmlspecialchars($user['username'] ?? 'Admin'); ?></strong></div>
        <div style="margin-top:6px">Email: <small><?php echo htmlspecialchars($user['email'] ?? 'admin@example.com'); ?></small></div>
      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">
      <div class="topbar">
        <button class="btn btn-sm btn-outline-secondary d-md-none" id="btnToggle"><i class="bi bi-list"></i></button>
        <div class="me-2">VI</div>
        <div class="btn btn-light btn-sm"><i class="bi bi-bell"></i></div>
        <div class="dropdown">
          <?php $avatar = $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name=User&background=random'; ?>
<img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" style="width:50px;height:50px;border-radius:50%;cursor:pointer;border:2px solid #f5c542;" data-bs-toggle="dropdown">
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="?action=profile">Hồ sơ</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="?action=logout">Đăng xuất</a></li>
          </ul>
        </div>
      </div>

      <h3 style="margin-bottom:22px; color:#4a3512;">Danh sách Hướng dẫn viên</h3>

      <div class="grid">
        <div class="card-panel">
          <h2 style="float:left;">Tổng số HDV: <?= count($guides ?? []) ?></h2>
          <div style="clear:both;"></div>
        </div>

        <div class="card-panel">
          <button id="btnToggleFilter" class="btn btn-outline-primary" style="width:100%; margin-bottom:12px;">▼ Hiện bộ lọc</button>
          <div id="filterPanel" style="display:none;">
            <h5>Lọc nhanh</h5>
            <div style="margin-top:15px;">
              <label style="font-weight:600; display:block; margin-bottom:8px;">Trạng thái</label>
              <div style="display:flex; gap:15px; flex-wrap:wrap;">
                <label><input type="checkbox" class="filterStatus" value="available"> Đang trống lịch</label>
                <label><input type="checkbox" class="filterStatus" value="busy"> Đang dẫn tour</label>
              </div>
              <input type="text" id="searchText" class="form-control mt-3" placeholder="Tìm tên hoặc SĐT...">
              <button id="btnFilter" class="btn btn-primary mt-3 w-100">🔍 Tìm kiếm</button>
              <button id="btnResetFilter" class="btn btn-secondary mt-2 w-100">↻ Xoá bộ lọc</button>
            </div>
          </div>
        </div>
      </div>

      <!-- DIV TRẮNG BAO BỌC DANH SÁCH CARD -->
      <div class="guides-white-container">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="guideContainer">
          <?php foreach ($guides ?? [] as $g):
            $toursLed = $g['toursLed'] ?? 0;
            $departuresCount = $g['departures_count'] ?? 0;
            $isBusy = !empty($g['has_departure']);
            $statusText = $isBusy ? 'Đang dẫn tour' : 'Đang trống lịch';
            $statusClass = $isBusy ? 'badge-busy' : 'badge-available';
            $modalId = 'guideDetailModal' . $g['id'];
          ?>
            <div class="col">
              <div class="guide-card">
                <div class="guide-name"><?= htmlspecialchars($g['name']) ?></div>
                
                <div class="guide-info-row">
                  <strong>SĐT:</strong> <?= htmlspecialchars($g['phone']) ?>
                </div>
                <div class="guide-info-row">
                  <strong>Số tour đã dẫn:</strong> <?= $toursLed ?>
                </div>
                <div class="guide-info-row">
                  <strong>Trạng thái:</strong>
<span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                </div>

                <div class="guide-actions">
                  <?php if (!$isBusy): ?>
                    <button class="btn btn-success btn-sm assignBtn" data-guide-id="<?= $g['id'] ?>" data-guide-name="<?= htmlspecialchars($g['name']) ?>">
                      <i class="bi bi-calendar-plus"></i> Phân lịch
                    </button>
                  <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled>Không thể phân lịch</button>
                  <?php endif; ?>
                  <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                    <i class="bi bi-eye"></i> Chi tiết
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </main>
  </div>

  <!-- MODAL CHI TIẾT HDV VỚI AVATAR TRÒN LỚN -->
  <?php foreach ($guides ?? [] as $g):
  $toursLed = $g['toursLed'] ?? 0;
  $departuresCount = $g['departures_count'] ?? 0;
  $isBusy = !empty($g['has_departure']);
  $statusText = $isBusy ? 'Đang dẫn tour' : 'Đang trống lịch';
  $statusClass = $isBusy ? 'badge-busy' : 'badge-available';
  $modalId = 'guideDetailModal' . $g['id'];

    // Avatar: ưu tiên trường avatar nếu có, không thì dùng placeholder đẹp
    $avatarUrl = !empty($g['avatar']) ? $g['avatar'] : 'https://media.istockphoto.com/id/1307064735/vector/people-a…12&w=0&k=20&c=A0Ci57tpR4U9nreJjy2PNJDRhkj5tvRv7Mcw9kqOHFA=';
  ?>
    <div class="modal fade" id="<?= $modalId ?>" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#7b5e2a; color:white;">
            <h5 class="modal-title">Chi tiết HDV: <?= htmlspecialchars($g['name']) ?></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body" style="padding:24px;">
            <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Avatar" class="guide-detail-avatar">

            <div class="guide-detail-info">
              <p><strong>ID:</strong> <?= $g['id'] ?></p>
              <p><strong>SĐT:</strong> <?= htmlspecialchars($g['phone']) ?></p>
              <p><strong>Email:</strong> <?= htmlspecialchars($g['email'] ?? '—') ?></p>
              <p><strong>Giới tính:</strong> <?= htmlspecialchars($g['sex'] ?? '—') ?></p>
              <p><strong>Kinh nghiệm:</strong> <?= $g['experience'] ?? 0 ?> năm</p>
              <p><strong>Số tour đã dẫn:</strong> <?= $toursLed ?></p>
              <p><strong>Trạng thái:</strong> <span class="badge <?= $statusClass ?>"><?= $statusText ?></span></p>
            </div>
            <div style="clear:both;"></div>
          </div>
          <div class="modal-footer">
<button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Đóng</button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- Modal Phân lịch: chọn booking chưa có HDV -->
  <div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <form method="POST" action="index.php?action=assign-guide">
          <div class="modal-header" style="background-color:#0d6efd; color:white;">
            <h5 class="modal-title">Phân lịch hướng dẫn viên: <span id="assignGuideName"></span></h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="guide_id" id="assignGuideId" value="">
            <p>Chọn một booking chưa có HDV:</p>
            <div class="table-responsive">
              <table class="table table-sm table-bordered">
                <thead class="table-light"><tr><th>Chọn</th><th>ID Booking</th><th>Tên tour</th><th>Departure</th><th>Ngày đi</th></tr></thead>
                <tbody>
                  <?php if (!empty($unassignedBookings) && is_array($unassignedBookings)): ?>
                    <?php foreach ($unassignedBookings as $b): ?>
                      <tr>
                        <td class="text-center"><input type="radio" name="booking_id" value="<?= htmlspecialchars($b['id']) ?>"></td>
                        <td><?= htmlspecialchars($b['id']) ?></td>
                        <td><?= htmlspecialchars($b['tour_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($b['departuresId'] ?? '') ?></td>
                        <td><?= !empty($b['dateStart']) ? date('d/m/Y', strtotime($b['dateStart'])) : '—' ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="5" class="text-center text-muted">Không có booking nào cần phân lịch.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Xác nhận phân lịch</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const allGuides = <?= json_encode($guides ?? [], JSON_UNESCAPED_UNICODE) ?>;

    const btnToggleFilter = document.getElementById('btnToggleFilter');
    const filterPanel = document.getElementById('filterPanel');
    let visible = false;
    btnToggleFilter.addEventListener('click', () => {
      visible = !visible;
      filterPanel.style.display = visible ? 'block' : 'none';
      btnToggleFilter.innerHTML = visible ? '▲ Ẩn bộ lọc' : '▼ Hiện bộ lọc';
    });

    function renderGuides(data) {
      const container = document.getElementById('guideContainer');
      if (!data || data.length === 0) {
        container.innerHTML = '<div class="col-12 text-center py-5 text-muted fs-5">Không tìm thấy hướng dẫn viên nào</div>';
        return;
      }

      container.innerHTML = data.map(g => {
        const toursLed = g.toursLed || 0;
        const isBusy = g.has_departure || (g.departures_count && g.departures_count > 0);
        const statusText = isBusy ? 'Đang dẫn tour' : 'Đang trống lịch';
        const statusClass = isBusy ? 'badge-busy' : 'badge-available';
        const modalId = 'guideDetailModal' + g.id;

        return `
          <div class="col">
            <div class="guide-card">
              <div class="guide-name">${escapeHtml(g.name)}</div>
              <div class="guide-info-row"><strong>SĐT:</strong> ${escapeHtml(g.phone)}</div>
              <div class="guide-info-row"><strong>Số tour đã dẫn:</strong> ${toursLed}</div>
              <div class="guide-info-row"><strong>Trạng thái:</strong> <span class="badge ${statusClass}">${statusText}</span></div>
              <div class="guide-actions">
                ${!isBusy ? `<button class="btn btn-success btn-sm assignBtn" data-guide-id="${g.id}" data-guide-name="${escapeHtml(g.name)}"><i class="bi bi-calendar-plus"></i> Phân lịch</button>` : `<button class="btn btn-secondary btn-sm" disabled>Không thể phân lịch</button>`}
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#${modalId}"><i class="bi bi-eye"></i> Chi tiết</button>
              </div>
            </div>
          </div>
        `;
      }).join('');
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    document.getElementById('btnFilter').onclick = () => {
      const statuses = Array.from(document.querySelectorAll('.filterStatus:checked')).map(c => c.value);
      const text = document.getElementById('searchText').value.toLowerCase().trim();

      const filtered = allGuides.filter(g => {
        const toursLed = g.toursLed || 0;
        const status = toursLed > 0 ? 'busy' : 'available';
const matchStatus = statuses.length === 0 || statuses.includes(status);
        const matchText = !text || g.name.toLowerCase().includes(text) || g.phone.includes(text);
        return matchStatus && matchText;
      });
      renderGuides(filtered);
    };

    document.getElementById('btnResetFilter').onclick = () => {
      document.querySelectorAll('.filterStatus').forEach(c => c.checked = false);
      document.getElementById('searchText').value = '';
      renderGuides(allGuides);
    };

    // Assign modal handling
    document.addEventListener('click', function (e) {
      const btn = e.target.closest && e.target.closest('.assignBtn');
      if (!btn) return;
      const gid = btn.dataset.guideId;
      const gname = btn.dataset.guideName || '';
      document.getElementById('assignGuideId').value = gid;
      document.getElementById('assignGuideName').textContent = gname;
      // show modal
      const modal = new bootstrap.Modal(document.getElementById('assignModal'));
      modal.show();
    });
  </script>
</body>

</html>