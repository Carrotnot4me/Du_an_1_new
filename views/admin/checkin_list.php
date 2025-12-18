<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quy trình Check-in Tour</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./assets/list.css">
</head>

<body>
    <div class="app">

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

                <a class="nav-item active" href="index.php?action=checkin">
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

        <main class="main flex-grow-1 p-4">
            <h2 class="mb-4 text-dark"><i class="bi bi-calendar-check-fill me-2"></i> Quy trình Check-in Tour</h2>

            <div id="message_area">
                <?php echo $message; ?>
            </div>

            <div class="p-4 bg-white rounded shadow-sm mb-4">
                <form method="GET" class="d-flex" action="index.php">
                    <input type="hidden" name="action" value="checkin">
                    <input type="hidden" name="sub_action" value="list">
                    <input type="text" name="keyword" class="form-control me-2"
                        placeholder="Tìm kiếm theo Tên Tour, Code Tour, hoặc ID Booking"
                        value="<?php echo htmlspecialchars($keyword); ?>">
                    <button type="submit" class="btn btn-info"><i class="bi bi-search"></i> Tìm kiếm</button>
                    <a href="index.php?action=checkin" class="btn btn-secondary ms-2">Xem tất cả</a>
                </form>
            </div>

            <?php
            // Ensure DB connection is available
            require_once __DIR__ . '/../../commons/function.php';
            $db = connectDB();

            // Build tours summary: only tours that have at least one booking and an ongoing departure (current date between dateStart and dateEnd)
            $sql = "SELECT t.id, t.type, t.name, t.max_people,
               COALESCE((SELECT image_url FROM tour_images WHERE tour_id = t.id LIMIT 1),'') AS image_url,
               MIN(d.dateStart) AS depart_start, MAX(d.dateEnd) AS depart_end,
               COUNT(DISTINCT b.id) AS bookings_count,
               COALESCE(SUM(CAST(br.quantity AS UNSIGNED)),0) AS registrants_total
             FROM tours t
             INNER JOIN bookings b ON b.tourId = t.id
             LEFT JOIN departures d ON d.tourId = t.id
             LEFT JOIN booking_registrants br ON br.booking_id = b.id
             WHERE d.dateStart <= CURDATE() AND d.dateEnd >= CURDATE()
             GROUP BY t.id
             ORDER BY CAST(t.id AS UNSIGNED) ASC";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <h4 class="mt-4 mb-3 text-dark">Danh sách Tour đang diễn ra (Check-in theo khách)</h4>
            <div class="table-responsive bg-white rounded shadow-sm">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Loại Tour</th>
                            <th>Tên Tour</th>
                            <th>Ngày khởi hành</th>
                            <th>Hình ảnh</th>
                            <th>Số lượng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tours)): ?>
                            <tr><td colspan="7" class="text-center text-muted">Không có tour nào đang diễn ra</td></tr>
                        <?php else: ?>
                            <?php foreach ($tours as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['type'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                                    <td>
                                        <?php if ($row['depart_start']):
                                            echo htmlspecialchars(date('d/m/Y', strtotime($row['depart_start'])));
                                            if ($row['depart_end']) echo ' - ' . htmlspecialchars(date('d/m/Y', strtotime($row['depart_end'])));
                                        else echo '-'; endif; ?>
                                    </td>
                                    <td><?= $row['image_url'] ? '<img src="' . htmlspecialchars($row['image_url']) . '" style="width:80px;height:50px;object-fit:cover;border-radius:4px;">' : '—' ?></td>
                                    <td><?= (int)$row['registrants_total'] ?><?= isset($row['max_people']) && $row['max_people']>0 ? ' / ' . (int)$row['max_people'] : '' ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light btnToggleCustomers" data-tour-id="<?= htmlspecialchars($row['id']) ?>" title="Xem khách"><i class="bi bi-caret-down"></i></button>
                                    </td>
                                </tr>
                                <tr class="customer-row d-none"><td colspan="7" class="p-0 border-0"><div class="p-3 bg-white customer-list-container" style="display:none"></div></td></tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function escapeHtml(s) { return String(s).replace(/[&<>"']/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[m]; }); }

        function renderCustomersHtml(customers) {
            if (!customers || customers.length === 0) return '<div class="text-muted p-2">Không có khách hàng</div>';
            let html = '<div class="table-responsive"><table class="table table-sm mb-0"><thead class="table-light"><tr><th>#</th><th>Tên</th><th>Ngày sinh</th><th>Giới tính</th><th>Booking ID</th><th>Check-in</th><th>Hành động</th></tr></thead><tbody>';
            customers.forEach((c, idx) => {
                const checked = c.checkin_id ? true : false;
                const time = c.checkin_time ? c.checkin_time : '';
                html += `<tr data-customer-id="${escapeHtml(c.id||'')}">`;
                html += `<td>${idx+1}</td>`;
                html += `<td>${escapeHtml(c.name||'')}</td>`;
                html += `<td>${escapeHtml(c.date||'')}</td>`;
                html += `<td>${escapeHtml(c.gender||'')}</td>`;
                html += `<td>${escapeHtml(c.booking_id||'')}</td>`;
                html += `<td>${checked ? '<span class="badge bg-success">Đã Check-in</span><br><small class="text-muted">' + escapeHtml(time) + '</small>' : '<span class="badge bg-warning text-dark">Chưa Check-in</span>'}</td>`;
                html += `<td class="text-center">${checked ? `<button class="btn btn-sm btn-danger undoCheckBtn">Hoàn tác</button>` : `<button class="btn btn-sm btn-primary checkBtn">Check-in</button>`}</td>`;
                html += `</tr>`;
            });
            html += '</tbody></table></div>';
            return html;
        }

        async function loadCustomersForTour(btn) {
            const tr = btn.closest('tr');
            const next = tr.nextElementSibling;
            if (!next || !next.classList.contains('customer-row')) return;
            const container = next.querySelector('.customer-list-container');
            const tourId = btn.getAttribute('data-tour-id') || '';

            if (container.dataset.loaded === '1') {
                const shown = container.style.display !== 'none';
                container.style.display = shown ? 'none' : '';
                next.classList.toggle('d-none', shown);
                return;
            }

            try {
                const params = new URLSearchParams();
                if (tourId) params.set('tour_id', tourId);
                params.set('ajax_customers', '1');
                const res = await fetch('index.php?action=customer-list&' + params.toString());
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                if (data.success) {
                    container.innerHTML = renderCustomersHtml(data.customers || []);
                    container.dataset.loaded = '1';
                    container.style.display = '';
                    next.classList.remove('d-none');
                    attachCustomerHandlers(container);
                } else {
                    container.innerHTML = '<div class="p-2 text-danger">Lỗi khi tải khách hàng</div>';
                    container.style.display = '';
                    next.classList.remove('d-none');
                }
            } catch (err) {
                container.innerHTML = '<div class="p-2 text-danger">' + escapeHtml(err.message) + '</div>';
                container.style.display = '';
                next.classList.remove('d-none');
            }
        }

        async function postJson(url, data) {
            const res = await fetch(url, {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body: new URLSearchParams(data)});
            return res.json();
        }

        function attachCustomerHandlers(container) {
            container.querySelectorAll('.checkBtn').forEach(b => {
                b.addEventListener('click', async function(){
                    const row = this.closest('tr');
                    const cid = row.dataset.customerId;
                    if (!cid) return;
                    this.disabled = true;
                    const r = await postJson('index.php?action=checkin&sub_action=check_customer', {customer_id: cid});
                    if (r && r.success) {
                        const parentBtn = container.parentElement.parentElement.previousElementSibling.querySelector('.btnToggleCustomers');
                        container.dataset.loaded = '';
                        loadCustomersForTour(parentBtn);
                    } else {
                        alert('Check-in thất bại');
                        this.disabled = false;
                    }
                });
            });
            container.querySelectorAll('.undoCheckBtn').forEach(b => {
                b.addEventListener('click', async function(){
                    const row = this.closest('tr');
                    const cid = row.dataset.customerId;
                    if (!cid) return;
                    this.disabled = true;
                    const r = await postJson('index.php?action=checkin&sub_action=undo_check_customer', {customer_id: cid});
                    if (r && r.success) {
                        const parentBtn = container.parentElement.parentElement.previousElementSibling.querySelector('.btnToggleCustomers');
                        container.dataset.loaded = '';
                        loadCustomersForTour(parentBtn);
                    } else {
                        alert('Hoàn tác thất bại');
                        this.disabled = false;
                    }
                });
            });
        }

        document.querySelectorAll('.btnToggleCustomers').forEach(btn => btn.addEventListener('click', function(){ loadCustomersForTour(this); }));
    </script>
</body>
</html>