<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Lịch HDV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="/mvc-oop-basic-duanmau/assets/list.css">

</head>

<body>
  <div class="app">
    <!-- SIDEBAR (copy từ tour-list nếu cần) -->
    <aside class="sidebar" id="sidebar">
      <div class="logo">
        <div
          style="width:44px;height:44px;background:#f5c542;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3b2a0a;font-weight:700">
          AD</div>
        <div>
          <div>AdminPanel</div><small style="opacity:.8">v1.0</small>
        </div>
      </div>
      <nav>
        <div class="nav-item"><a href="tour-list.php"><i class="bi bi-airplane"></i> Quản lý Tour</a></div>
        <div class="nav-item"><a href="departures.php"><i class="bi bi-calendar-check"></i> Đợt khởi hành</a></div>
        <div class="nav-item"><a href="#"><i class="bi bi-bookmark-check"></i> Đặt chỗ</a></div>
        <div class="nav-item"><a href="#"><i class="bi bi-people-fill"></i> Khách hàng</a></div>
        <div class="nav-item active"><i class="bi bi-person-badge"></i> Hướng dẫn viên</div>
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
            <div class="rounded-circle bg-warning text-dark d-flex align-items:center;justify-content:center"
                 style="width:50px;height:50px;font-weight:600">A</div>
        </div>

        <h3 style="margin-bottom:15px;color:#4a3512;">
            📅 Lịch làm việc <?= $staff ? 'của HDV: ' . htmlspecialchars($staff['name']) : 'tất cả HDV' ?>
        </h3>

        <div class="card-panel">
            <?php if (empty($schedules)): ?>
                <div class="alert alert-info text-center">Chưa có lịch nào <?= $staff ? 'cho HDV này' : '' ?>.</div>
            <?php else: ?>
                <table class="table table-hover align-middle table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Tour</th>
                            <th>Ngày bắt đầu</th>
                            <th>Ngày kết thúc</th>
                            <th>Điểm gặp</th>
                            <th>Hướng dẫn viên</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($schedules as $i => $s): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($s['tour_name'] ?? '') ?></td>
                            <td><?= !empty($s['dateStart']) ? date("d/m/Y", strtotime($s['dateStart'])) : '' ?></td>
                            <td><?= !empty($s['dateEnd']) ? date("d/m/Y", strtotime($s['dateEnd'])) : '' ?></td>
                            <td><?= htmlspecialchars($s['meetingPoint'] ?? '') ?></td>
                            <td><?= htmlspecialchars($s['guide_name'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>