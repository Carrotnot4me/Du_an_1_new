<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chi tiết Tour Sa Pa</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background: #f8f9fa; }

    .tour-header {
      background: linear-gradient(135deg, #f5c542, #f2b705);
      color: #3b2a0a;
    }

    .tour-related-wrapper {
      position: sticky;
      top: 90px;
    }

    /* Thu nhỏ khung thanh toán bên phải: giới hạn chiều rộng và giảm padding/font */
    .tour-related-wrapper .bg-white {
      max-width: 400px;
      margin-left: auto;
      min-height: 460px; 
    }

    /* Payment boxes: gradients only on hover */
    .payment-card {
      background: #ffffff;
      color: #1f2937;
      border: 1px solid rgba(0,0,0,0.04);
      transition: background 220ms ease, color 220ms ease, transform 160ms ease;
    }
    .payment-card .bi { opacity: 0.9; transition: opacity 220ms ease; }
    .payment-card:hover { transform: translateY(-3px); color: #fff; }
    .payment-card.g-red:hover { background: linear-gradient(135deg, #ff416c, #ff8a5b); }
    .payment-card.g-orange:hover { background: linear-gradient(135deg, #ff8a5b, #ffcd3c); }
    .payment-card.g-green:hover { background: linear-gradient(135deg, #ffcd3c, #56ab2f); }
    .payment-card.g-purple:hover { background: linear-gradient(120deg, #667eea, #764ba2); }

    .tour-related-wrapper .p-4 .d-flex,
    .tour-related-wrapper .p-4 .payment-info,
    .tour-related-wrapper .p-4 .btn {
      padding: 12px !important;
    }

    .tour-related-wrapper .p-4 .fs-3 { font-size: 1.5rem !important; }
    .tour-related-wrapper .p-4 .fs-4 { font-size: 1.05rem !important; }
    .tour-related-wrapper .p-4 .small { font-size: 0.85rem !important; }

    .schedule-timeline {
      border-left: 3px solid #f5c542;
      padding-left: 20px;
    }

    .schedule-day {
      font-weight: 700;
      color: #f5c542;
      font-size: 18px;
      margin-bottom: 8px;
    }

    .status-paid {
      background: #d1e7dd;
      color: #0f5132;
      border-radius: 50px;
      padding: 6px 14px;
      font-weight: 600;
    }

    .status-unpaid {
      background: #f8d7da;
      color: #842029;
      border-radius: 50px;
      padding: 6px 14px;
      font-weight: 600;
    }
    .status-complete {
      background: #cfe2ff;
      color: #084298;
      border-radius: 50px;
      padding: 6px 14px;
      font-weight: 600;
    }
    /* ===== PAYMENT MODAL THEME ===== */
#paymentModal .modal-content {
  border-radius: 18px;
  border: none;
  background: #fffdf7;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
}

/* Header */
#paymentModal .modal-header {
  background: linear-gradient(135deg, #8b6b2f, #a8843a);
  color: #fff;
  border-top-left-radius: 18px;
  border-top-right-radius: 18px;
  padding: 16px 20px;
}

#paymentModal .modal-title {
  font-weight: 700;
}

#paymentModal .btn-close {
  filter: invert(1);
}

/* Body */
#paymentModal .modal-body {
  padding: 20px;
}

#paymentModal label {
  font-weight: 600;
  color: #5a471c;
}

#paymentModal .form-select,
#paymentModal .form-control {
  border-radius: 12px;
  border: 1px solid #ddd;
  padding: 10px 12px;
}

/* Info box */
.payment-info {
  background: #fdf7e3;
  border: 1px dashed #e0c97a;
  border-radius: 14px;
  padding: 14px;
  margin-bottom: 16px;
  font-size: 15px;
}

.payment-info div {
  margin-bottom: 6px;
}

/* Footer */
#paymentModal .modal-footer {
  border-top: none;
  padding: 16px 20px 20px;
}

#paymentModal .btn-success {
  background: linear-gradient(135deg, #8b6b2f, #a8843a);
  border: none;
  border-radius: 12px;
  padding: 8px 18px;
  font-weight: 600;
}

#paymentModal .btn-success:hover {
  opacity: 0.9;
}
.ee{
    background: #7b5e2a;
}
.cc{
    color: #cb9d45;
}

  </style>
</head>

<body>



<main class="container my-4">

  <!-- THÔNG TIN CHÍNH -->
  <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-3 mb-lg-0">
        <?php
          $img = $tourImage ?? ($booking['image_url'] ?? null);
          if (!$img) $img = 'https://images.unsplash.com/photo-1501785888041-af3ef285b470';
        ?>
        <div style="width:100%; height:280px; max-height:360px; overflow:hidden; border-radius:16px;">
          <img src="<?= htmlspecialchars($img) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
        </div>
      </div>
      <div class="col-lg-6">
        <?php
          require_once __DIR__ . '/../../models/BookingModel.php';
          $bm = new BookingModel();
          $booking = $booking ?? null;
          $registrants = $registrants ?? [];
          $tourName = $booking['tour_name'] ?? ($booking['name'] ?? 'Tour Sa Pa – Fansipan 2N1Đ');
          $adultPrice = isset($booking['adult_price']) ? (int)$booking['adult_price'] : 2890000;
          $tourCode = $booking['tour_code'] ?? 'SP-FANS-21';
          // total paid for this booking (from payment_history when available)
          $paid = 0;
          if (!empty($booking['id'])) {
              $paid = $bm->getPaymentsTotalByBooking($booking['id']);
          }
        ?>
        <h2 class="fw-bold d-flex align-items-center"><?= htmlspecialchars($tourName) ?>
          <a href="index.php?action=booking-list" class="btn btn-outline-secondary btn-sm ms-3">← Danh sách đặt chỗ</a>
        </h2>
        <p class="text-muted fs-5">Du lịch nghỉ dưỡng – khám phá núi rừng</p>
        <div class="fs-4 fw-bold text-danger mb-2"><?= number_format($adultPrice,0,',','.') ?>đ / người</div>
        <div class="text-muted">Mã tour: <strong><?= htmlspecialchars($tourCode) ?></strong></div>
      </div>
    </div>
  </div>

  <div class="row g-4">

  <!-- CỘT TRÁI: THÔNG TIN TOUR + MÔ TẢ + DANH SÁCH KHÁCH -->
  <div class="col-lg-8">

    <!-- Mô tả tour + lịch trình (giữ nguyên) -->
    <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
      <h4 class="cc fw-bold mb-3">
        <i class="bi bi-info-circle me-2"></i>Mô tả tour
      </h4>
      <p class="fs-5">
        <?= htmlspecialchars($tourDetail['short_description'] ?? ($booking['short_description'] ?? 'Tour Sa Pa – Fansipan 2 ngày 1 đêm mang đến cho du khách trải nghiệm không khí mát mẻ vùng núi Tây Bắc, tham quan bản làng dân tộc, chinh phục đỉnh Fansipan.')) ?>
      </p>

      <hr>

      <h5 class="cc fw-bold mb-3">
        <i class="bi bi-calendar2-week me-2"></i>Chuyến khởi hành
      </h5>

      <div class="card border-0 shadow-sm p-3 mb-4">
        <?php
          $ds = $booking['dateStart'] ?? $booking['date_start'] ?? null;
          $de = $booking['dateEnd'] ?? $booking['date_end'] ?? null;
          $meeting = $booking['meetingPoint'] ?? $booking['meeting_point'] ?? 'Nhà hát lớn Hà Nội';
        ?>
        <p>
          <span class="text-success fs-5"><?= $ds ? date('d/m/Y', strtotime($ds)) : '15/10/2025' ?></span> →
          <span class="text-danger fs-5"><?= $de ? date('d/m/Y', strtotime($de)) : '16/10/2025' ?></span>
        </p>
        <p class="mb-0">
          <i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($meeting) ?>
        </p>
      </div>

      <h5 class="cc fw-bold mb-3">
        <i class="bi bi-list-check me-2"></i>Lịch trình tour
      </h5>

      <div class="schedule-timeline">
        <?php if (!empty($tourSchedules) && is_array($tourSchedules)): ?>
          <?php $week = 1; foreach ($tourSchedules as $s): ?>
            <div class="mb-3">
              <div class="schedule-day">Ngày <?= $week ?></div>
              <ul style="padding-left:18px; margin:6px 0; list-style:none;">
                <?php if (!empty($s['activity'])): ?>
                  <?php
                    $act = trim($s['activity']);
                    $lines = preg_split('/\r?\n/', $act);
                    foreach ($lines as $line):
                      if (trim($line) === '') continue;
                  ?>
                    <li><?= htmlspecialchars($line) ?></li>
                  <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty($s['details']) && is_array($s['details'])): ?>
                  <?php foreach ($s['details'] as $d): ?>
                    <li style="margin-top:6px; font-size:0.95rem;"><strong><?= htmlspecialchars(substr($d['start_time'] ?? '',0,5)) ?><?php if (!empty($d['end_time'])) echo ' - ' . htmlspecialchars(substr($d['end_time'],0,5)); ?></strong> — <?= htmlspecialchars($d['content'] ?? '') ?></li>
                  <?php endforeach; ?>
                <?php endif; ?>

              </ul>
            </div>
          <?php $week++; endforeach; ?>
        <?php else: ?>
          <div class="mb-3">
            <div class="schedule-day">Ngày 1</div>
            <ul>
              <li>Khởi hành – tham quan Cát Cát</li>
              <li>Nhận phòng – nghỉ ngơi</li>
            </ul>
          </div>
          <div>
            <div class="schedule-day">Ngày 2</div>
            <ul>
              <li>Fansipan – về Hà Nội</li>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- DANH SÁCH KHÁCH HÀNG (đặt dưới mô tả tour) -->
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="cc fw-bold mb-0">
        <i class="bi bi-people-fill me-2"></i>Danh sách khách hàng
      </h5>
      <button class="btn btn-primary btn-sm px-3 py-1 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
        <i class="bi bi-person-plus-fill me-1"></i>Thêm
      </button>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-4">
      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
          <thead class="table-light">
            <tr>
              <th>Tên</th>
              <th>Email</th>
              <th>SĐT</th>
              <th>Số lượng</th>
              <th>Số tiền</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
          <tbody id="customerTableBody">
          <?php if (!empty($registrants) && is_array($registrants)): ?>
            <?php foreach ($registrants as $r): ?>
              <?php
                $ad = isset($r['adult_count']) ? (int)$r['adult_count'] : null;
                $ch = isset($r['child_count']) ? (int)$r['child_count'] : null;
                if ($ad !== null || $ch !== null) {
                    $ad = $ad ?? 0; $ch = $ch ?? 0;
                    $total = $ad * ($adultPrice ?? 0) + $ch * (($booking['child_price'] ?? 0));
                    $qty = $ad + $ch;
                } else {
                    $qty = (int)($r['quantity'] ?? 1);
                    $total = $qty * ($adultPrice ?? 0);
                }
                // determine paid amount per registrant from payment_history
                $paidFor = 0;
                if (!empty($r['id'])) {
                    $paidFor = $bm->getPaidByRegistrant($r['id']);
                }
                $statusLabel = $r['status'] ?? 'Chờ xác nhận';
                if (stripos($statusLabel, 'hoàn') !== false) {
                  $statusClass = 'status-complete';
                } elseif (stripos($statusLabel, 'cọc') !== false) {
                  $statusClass = 'status-paid';
                } else {
                  $statusClass = 'status-unpaid';
                }
              ?>
              <tr>
                <td><?= htmlspecialchars($r['name'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($r['phone'] ?? '') ?></td>
                <td><?= $qty ?></td>
                      <?php $remaining = max(0, $total - $paidFor); ?>
                      <td class="text-danger fw-bold"><?= $remaining ? number_format($remaining,0,',','.') . 'đ' : '0đ' ?></td>
                <td><span class="<?= $statusClass ?>"><?= htmlspecialchars($statusLabel) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td>Nguyễn Văn A</td>
              <td>a@gmail.com</td>
              <td>0987 654 321</td>
              <td>2</td>
              <td class="text-danger fw-bold">5.780.000đ</td>
              <td><span class="status-paid">Đã cọc</span></td>
            </tr>
            <tr>
              <td>Trần Thị B</td>
              <td>b@gmail.com</td>
              <td>0912 345 678</td>
              <td>1</td>
              <td class="text-danger fw-bold">2.890.000đ</td>
              <td><span class="status-unpaid">Chưa cọc</span></td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Button moved to header -->
    </div>

  </div>

<!-- CỘT PHẢI: THANH TOÁN - SẠCH ĐẸP, CHUYÊN NGHIỆP 2025 -->
<div class="col-lg-4">
    
  <div class="tour-related-wrapper">
    <div class="bg-white rounded-4" style="box-shadow: 0 4px 25px rgba(0,0,0,0.06); overflow: hidden;">
      
      <!-- Header nhẹ nhàng -->
      <div class="ee text-white py-4 px-4 text-center rounded-top-4">
        <h5 class="fw-bold mb-1">
          <i class="bi bi-receipt me-2"></i>Thông tin thanh toán
        </h5>
        <small class="opacity-90">Tổng quan chi phí tour</small>
      </div>

      <div class="p-4">


        <!-- 1. Tổng tiền tour - Gradient đỏ → cam (show on hover) -->
        <?php
          // compute totals
          $totalTour = 0;
          $adultPrice = $adultPrice ?? 0;
          $childPrice = $booking['child_price'] ?? 0;
          foreach ($registrants as $r) {
              $ad = isset($r['adult_count']) ? (int)$r['adult_count'] : null;
              $ch = isset($r['child_count']) ? (int)$r['child_count'] : null;
              if ($ad !== null || $ch !== null) {
                  $ad = $ad ?? 0; $ch = $ch ?? 0;
                  $totalTour += $ad * $adultPrice + $ch * $childPrice;
              } else {
                  // fallback: use quantity as adults
                  $qty = (int)($r['quantity'] ?? 0);
                  $totalTour += $qty * $adultPrice;
              }
          }
          $extraFee = round($totalTour * 0.1);
          // $paid already computed above from payment_history via BookingModel
          $remaining = $totalTour - $paid;
        ?>
        <div class="payment-card g-red d-flex align-items-center justify-content-between p-4 rounded-4 mb-3">
          <div>
            <div class="small opacity-90 fw-medium">Tổng tiền tour</div>
            <div class="fs-3 fw-bold"><?= $totalTour ? number_format($totalTour,0,',','.') . 'đ' : '0đ' ?></div>
          </div>
          <i class="bi bi-wallet2 fs-2 opacity-90"></i>
        </div>

        <!-- 2. Phí phát sinh - Gradient cam → vàng (show on hover) -->
        <div class="payment-card g-orange d-flex align-items-center justify-content-between p-4 rounded-4 mb-3">
          <div>
            <div class="small opacity-90 fw-medium">Phí phát sinh (10%)</div>
            <div class="fs-4 fw-bold"><?= number_format($extraFee,0,',','.') ?>đ</div>
          </div>
          <i class="bi bi-receipt fs-2 opacity-90"></i>
        </div>

        <!-- 3. Đã thanh toán - show actual payments -->
        <div class="payment-card g-green d-flex align-items-center justify-content-between p-4 rounded-4 mb-3">
          <div>
            <div class="small opacity-90 fw-medium">Đã thanh toán</div>
            <div class="fs-4 fw-bold"><?= $paid ? number_format($paid,0,',','.') . 'đ' : '0đ' ?></div>
          </div>
          <i class="bi bi-piggy-bank fs-2 opacity-90"></i>
        </div>

       <!-- Còn lại cần thanh toán - NỔI BẬT NHƯNG KHÔNG LỐ (show on hover) -->
        <div class="payment-card g-purple d-flex justify-content-between align-items-center p-4 rounded-4 mb-4">
          <div>
            <div class="small opacity-90">Còn lại cần thanh toán</div>
<div class="fs-3 fw-bold"><?= $remaining ? number_format($remaining,0,',','.') . 'đ' : '0đ' ?></div>
          </div>
          <i class="bi bi-credit-card fs-2 opacity-90"></i>
        </div>

        <!-- Nút thêm thanh toán -->
        <button class="btn btn-primary w-100 py-3 fw-bold rounded-4 d-flex align-items-center justify-content-center gap-2"
                data-bs-toggle="modal" data-bs-target="#paymentModal">
          <i class="bi bi-plus-circle-fill"></i>
          Thêm thanh toán mới
        </button>

      </div>
    </div>
  </div>
</div>
     
</main>

<div class="modal fade" id="paymentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">

      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-credit-card me-2"></i>Thêm thanh toán
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <form id="paymentForm" method="POST" action="index.php?action=registrant-payment">
      <input type="hidden" name="registrant_id" id="payment_registrant_id" value="">
      <div class="modal-body">

        <!-- Khách hàng -->
        <div class="mb-3">
          <label class="form-label">Khách hàng</label>
        <select class="form-select" id="customerSelect">
                    <option value="">-- Chọn khách hàng --</option>
                    <?php if (!empty($registrants) && is_array($registrants)): ?>
                      <?php foreach ($registrants as $r): ?>
                          <?php
                            $ad = isset($r['adult_count']) ? (int)$r['adult_count'] : null;
                            $ch = isset($r['child_count']) ? (int)$r['child_count'] : null;
                            if ($ad !== null || $ch !== null) {
                                $ad = $ad ?? 0; $ch = $ch ?? 0;
                                $total = $ad * ($adultPrice ?? 0) + $ch * (($booking['child_price'] ?? 0));
                            } else {
                                $qty = (int)($r['quantity'] ?? 1);
                                $total = $qty * ($adultPrice ?? 0);
                            }
                            $paidFor = 0;
                            if (!empty($r['id'])) {
                              $paidFor = $bm->getPaidByRegistrant($r['id']);
                            }
                          ?>
                          <option value="<?= htmlspecialchars($r['id'] ?? '') ?>" data-email="<?= htmlspecialchars($r['email'] ?? '') ?>" data-phone="<?= htmlspecialchars($r['phone'] ?? '') ?>" data-total="<?= $total ?>" data-paid="<?= $paidFor ?>">
                            <?= htmlspecialchars($r['name'] ?? '') ?>
                          </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <option data-email="a@gmail.com" data-phone="0987654321" data-total="5780000">
                      Nguyễn Văn A
                    </option>
                    <option data-email="b@gmail.com" data-phone="0912345678" data-total="2890000">
                      Trần Thị B
                    </option>
                    <?php endif; ?>
                  </select>
        </div>

        <!-- Thông tin tự động -->
        <div class="row g-3 mb-3">

  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="text" class="form-control" id="cusEmail" readonly>
  </div>

  <div class="col-md-6">
    <label class="form-label">Số điện thoại</label>
    <input type="text" class="form-control" id="cusPhone" readonly>
  </div>

  <div class="col-md-6">
    <label class="form-label">Tổng tiền</label>
    <input type="text" class="form-control text-danger fw-bold" id="cusTotal" readonly>
  </div>

  <div class="col-md-6">
    <label class="form-label">Tiền phải cọc (30%)</label>
    <input type="text" class="form-control text-success fw-bold" id="cusDeposit" readonly>
  </div>

</div>


        <!-- Phương thức thanh toán -->
        <div class="mb-3">
          <label class="form-label">Phương thức</label>
          <select class="form-select" name="method" id="paymentMethod">
            <option value="Tiền mặt">Tiền mặt</option>
            <option value="Chuyển khoản">Chuyển khoản</option>
            <option value="Thẻ">Thẻ</option>
          </select>
        </div>

        <!-- Nhập tiền -->
        <div class="mb-3">
          <label class="form-label">Số tiền thanh toán</label>
          <input type="number" class="form-control" name="amount" id="paymentAmount" placeholder="Nhập số tiền">
        </div>

      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-success" type="submit">
          <i class="bi bi-check-circle me-1"></i>Xác nhận
        </button>
      </div>
      </form>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('customerSelect').addEventListener('change', function () {
    const o = this.selectedOptions[0];
    if (!o || !o.dataset.total) return;

    const total = +o.dataset.total || 0;
    const paid = +o.dataset.paid || 0;
    const remainingTotal = Math.max(0, total - paid);
    const depositRequired = Math.round(total * 0.3);
    const remainingDeposit = Math.max(0, depositRequired - paid);

    document.getElementById('cusEmail').value = o.dataset.email || '';
    document.getElementById('cusPhone').value = o.dataset.phone || '';

    // If deposit already satisfied, show remaining total; otherwise show remaining total and deposit-to-pay
    document.getElementById('cusTotal').value = remainingTotal.toLocaleString('vi-VN') + 'đ';

    if (remainingDeposit > 0) {
      document.getElementById('cusDeposit').value = remainingDeposit.toLocaleString('vi-VN') + 'đ';
    } else {
      // deposit satisfied -> show remaining total (what's left to fully pay)
      document.getElementById('cusDeposit').value = remainingTotal.toLocaleString('vi-VN') + 'đ';
    }

    // set hidden registrant id for the payment form
    const rid = o.value || '';
    const hid = document.getElementById('payment_registrant_id');
    if (hid) hid.value = rid;

    // prefills payment amount with remainingDeposit (if >0) else remainingTotal
    const amtInput = document.getElementById('paymentAmount');
    if (amtInput) amtInput.value = (remainingDeposit > 0 ? remainingDeposit : remainingTotal) || '';
  });
</script>
<script>
  // Representative -> customers modal logic: run after DOM ready so elements exist
  document.addEventListener('DOMContentLoaded', function () {
    const ADULT_PRICE = <?= (int)$adultPrice ?>;
    const CHILD_PRICE = <?= (int)($booking['child_price'] ?? 0) ?>;
    const custList = document.getElementById('custList');
    const repPreview = document.getElementById('repPreviewTotal');
    const hiddenCustomers = document.getElementById('hiddenCustomersJson');
    const form = document.getElementById('addRegistrantForm');

    if (!custList || !repPreview || !hiddenCustomers || !form) return;

    let lastAdults = 0, lastChilds = 0;

    function createRow() {
      const el = document.createElement('div');
      el.className = 'customer-item mb-2 p-2 border rounded-2';
      el.innerHTML = `
        <div class="row g-2 align-items-center">
          <div class="col-md-4"><input class="form-control cust-name" placeholder="Họ và tên"></div>
          <div class="col-md-3"><select class="form-select cust-gender"><option value="">Giới tính</option><option>Nam</option><option>Nữ</option></select></div>
          <div class="col-md-3"><input type="date" class="form-control cust-dob"></div>
          <div class="col-md-2 text-end"><button type="button" class="btn btn-outline-danger btn-sm cust-remove">Xóa</button></div>
        </div>
      `;
      custList.appendChild(el);
      const removeBtn = el.querySelector('.cust-remove');
      removeBtn.addEventListener('click', () => { el.remove(); computeTotals(); });
      el.querySelector('.cust-dob').addEventListener('change', computeTotals);
      el.querySelector('.cust-name').addEventListener('input', computeTotals);
      // focus the name input for better UX
      const nameInput = el.querySelector('.cust-name'); if (nameInput) nameInput.focus();
      return el;
    }

    function computeTotals() {
      const rows = Array.from(custList.querySelectorAll('.customer-item'));
      let adults = 0, childs = 0, base = 0;
      const now = new Date(); const currentYear = now.getFullYear();
      rows.forEach(r => {
        const name = r.querySelector('.cust-name')?.value || '';
        const dob = r.querySelector('.cust-dob')?.value || '';
        if (!name) return; // only count named rows
        if (dob) {
          const by = new Date(dob).getFullYear();
          if (!isNaN(by)) {
            const age = currentYear - by;
            if (age > 12) adults++; else childs++;
          } else {
            adults++; // default
          }
        } else {
          adults++; // default
        }
      });
      base = adults * ADULT_PRICE + childs * CHILD_PRICE;
      lastAdults = adults; lastChilds = childs;
      repPreview.textContent = base.toLocaleString('vi-VN') + 'đ';
    }

    function onAddClick() { createRow(); computeTotals(); }
    const addBtn = document.getElementById('addCustBtn');
    if (addBtn) addBtn.addEventListener('click', onAddClick);
    else document.addEventListener('click', (evt) => { if (evt.target && evt.target.closest && evt.target.closest('#addCustBtn')) onAddClick(); });

    // build customers JSON and hidden fields on submit
    form.addEventListener('submit', function(e){
      const rows = Array.from(custList.querySelectorAll('.customer-item'));
      const payload = [];
      rows.forEach(r => {
        const name = r.querySelector('.cust-name')?.value || '';
        if (!name) return;
        const gender = r.querySelector('.cust-gender')?.value || '';
        const date = r.querySelector('.cust-dob')?.value || '';
        payload.push({name: name, gender: gender || null, date: date || null});
      });
      if (payload.length === 0) {
        alert('Vui lòng thêm ít nhất 1 hành khách.');
        e.preventDefault();
        return;
      }
      // set hidden JSON
      hiddenCustomers.value = JSON.stringify(payload);

      // ensure adult_count / child_count and type fields exist (use lastAdults/lastChilds)
      ['adult_count','child_count','type'].forEach(n => { const ex = form.querySelector('input[name="'+n+'"]'); if (ex) ex.remove(); });
      const a = document.createElement('input'); a.type='hidden'; a.name='adult_count'; a.value = lastAdults || 0; form.appendChild(a);
      const c = document.createElement('input'); c.type='hidden'; c.name='child_count'; c.value = lastChilds || 0; form.appendChild(c);
      const t = document.createElement('input'); t.type='hidden'; t.name='type'; t.value = ( (parseInt(a.value) + parseInt(c.value)) && parseInt(c.value) > 0 ) ? 'Gia đình' : 'Cá nhân'; form.appendChild(t);

      // allow normal submit
    });

    // init: add one customer row by default
    createRow(); computeTotals();
  });
</script>


<!-- Modal Thêm Khách Hàng -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <!-- Header -->
      <div class="modal-header" style="background: linear-gradient(135deg, #007bff, #0056b3); color: #fff;">
        <h5 class="modal-title">
          <i class="bi bi-person-plus me-2"></i>Thêm khách hàng mới
        </h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

          <!-- Body: submit to controller to persist in DB (representative -> customers UI) -->
          <form id="addRegistrantForm" method="POST" action="index.php?action=add-registrant">
          <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['id'] ?? '') ?>">
          <input type="hidden" name="customers" id="hiddenCustomersJson" value="">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Họ và tên đại diện</label>
              <input type="text" class="form-control" name="name" id="repName" placeholder="Nhập họ và tên đại diện" required>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">Email</label>
                <input type="email" class="form-control" name="email" id="repEmail" placeholder="Nhập email">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" id="repPhone" placeholder="Nhập số điện thoại">
              </div>
            </div>

            <div class="mb-2 d-flex justify-content-between align-items-center">
              <strong>Hành khách</strong>
              <button type="button" id="addCustBtn" class="btn btn-sm btn-primary">+ Thêm hành khách</button>
            </div>

            <div id="custList" class="mb-3"></div>

            <div class="mb-2">
              <small class="text-muted">Tổng tiền dự kiến: <span id="repPreviewTotal" class="fw-bold text-danger">0đ</span></small>
            </div>
          </div>

          <!-- Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button class="btn btn-primary" type="submit" id="confirmAddCustomer">
              <i class="bi bi-check-circle me-1"></i>Xác nhận thêm
            </button>
          </div>
          </form>
    </div>
  </div>
</div>
</body>
</html>