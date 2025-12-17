<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt tour du lịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f5f7fa; font-family: 'Segoe UI', sans-serif; padding: 20px 0; }
        .card-custom { background: white; border-radius: 16px; box-shadow: 0 6px 25px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 25px; }
        .card-header-custom { background: #f8f9fa; padding: 18px 30px; font-weight: 600; font-size: 1.15rem; color: #2c3e50; border-bottom: 1px solid #e9ecef; }
        .form-control, .form-select { border-radius: 12px; height: 50px; font-size: 1rem; }
        .form-control-sm, .form-select-sm { height: 40px; font-size: 0.95rem; border-radius: 10px; }
        .table thead th { background: #e9ecef; font-weight: 600; color: #495057; padding: 14px 10px; }
        .table td { padding: 10px; vertical-align: middle; text-align: center; }
        .total-box { background: #f8f9fa; border-left: 6px solid #28a745; padding: 25px 30px; text-align: right; font-size: 1.4rem; font-weight: bold; color: #dc3545; }
        .btn-complete { background: #28a745; color: white; font-size: 1.1rem; font-weight: bold; padding: 12px 20px; border-radius: 14px; width: 100%; margin-top: 20px; }
        .btn-add-passenger { background: #17a2b8; color: white; border-radius: 50px; padding: 8px 18px; font-weight: 600; }
        .qty-stack { display: flex; flex-direction: column; }
        .representative { background: #fff; border-radius: 8px; padding: 12px; margin-bottom: 14px; border: 1px solid #e6e6e6; }
        .customer-item { background: #fafafa; border: 1px dashed #ddd; border-radius: 6px; padding: 8px; margin-bottom: 8px; }
    </style>
</head>
<body>

<div class="container">

    <form id="bookingForm" action="index.php?action=booking-save" method="POST">

        <!-- NOTIFICATIONS -->
        <?php if (!empty($_SESSION['errors'])): ?>
            <div class="alert alert-danger"><?php foreach ($_SESSION['errors'] as $e) echo htmlspecialchars($e) . '<br>'; unset($_SESSION['errors']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <!-- TOUR INFO -->
        <div class="card-custom">
            <div class="card-header-custom">Thông tin tour</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Chọn tour</label>
                        <select id="tour_id" name="tour_id" class="form-select">
                            <option value="">-- Chọn tour --</option>
                            <?php if (!empty($tours)): foreach ($tours as $t): ?>
                                <option value="<?= $t['id'] ?>" data-adult="<?= (int)($t['adult_price'] ?? 0) ?>" data-child="<?= (int)($t['child_price'] ?? 0) ?>"><?= htmlspecialchars($t['name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="date" name="date_start" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="date" name="date_end" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Điểm tập trung</label>
                        <input type="text" class="form-control" name="meeting_point" placeholder="VD: Nhà hát lớn Hà Nội">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label class="form-label">Tài xế</label>
                        <select class="form-select" name="driver_id">
                            <option value="">-- Chọn tài xế --</option>
                            <?php if (!empty($drivers)): foreach ($drivers as $dr): ?>
                            <option value="<?= $dr['id'] ?>"><?= htmlspecialchars($dr['name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCHEDULE (kept simple) -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">Lịch trình</div>
            <div class="card-body p-3">
                <div id="scheduleBox">Chọn tour để xem lịch trình</div>
                <table class="table mt-3" id="scheduleTable" style="display:none">
                    <thead><tr><th>Ngày</th><th>Hoạt động</th></tr></thead>
                    <tbody id="scheduleBody"></tbody>
                </table>
            </div>
        </div>

        <!-- REPRESENTATIVE / CUSTOMERS -->
        <div class="card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <span class="fw-bold">Thông tin hành khách</span>
                <button type="button" id="btnAddRepresentative" class="btn btn-add-passenger">+ Đại diện</button>
            </div>
            <div class="card-body p-4">
                <div id="representativeList"></div>
                <div id="repHiddenInputs"></div>
            </div>
        </div>

        <!-- TOTAL -->
        <div class="card-custom mt-4">
            <div class="card-body total-box">
                <div>Tạm tính: <span id="rowSubtotalSum">0 ₫</span></div>
                <div style="font-size:0.9rem; margin-top:8px">Phí phát sinh (10%): <strong id="surchargeAmount">0 ₫</strong></div>
                <div style="margin-top:8px">TỔNG: <span id="totalAmount">0 ₫</span></div>
            </div>
        </div>

        <button type="submit" class="btn btn-complete">HOÀN TẤT ĐẶT TOUR</button>

        <!-- Hidden totals for server -->
        <input type="hidden" name="total_amount" id="totalAmountInput">
        <input type="hidden" name="total_quantity" id="totalQuantityInput">

    </form>
</div>

<script>
// Basic integration script
let adultPrice = 0, childPrice = 0;
const repList = document.getElementById('representativeList');
const repHidden = document.getElementById('repHiddenInputs');
let repIndex = 0;

function addRepresentative() {
    const idx = repIndex++;
    const el = document.createElement('div');
    el.className = 'representative';
    el.setAttribute('data-index', idx);
    el.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Đại diện #${idx+1}</strong>
        <button type="button" class="btn btn-sm btn-danger rep-remove">✕</button>
      </div>
      <div class="row g-2 mb-2">
        <div class="col-md-4"><input class="form-control rep-name" placeholder="Họ và tên đại diện"></div>
        <div class="col-md-4"><input class="form-control rep-email" placeholder="Email"></div>
        <div class="col-md-4"><input class="form-control rep-phone" placeholder="Số điện thoại"></div>
      </div>
      <div class="mb-2 d-flex gap-2">
        <button type="button" class="btn btn-sm btn-primary add-passenger-btn">+ Thêm hành khách</button>
        <div class="ms-auto">Số lượng: <input readonly class="form-control form-control-sm qty-display" style="width:80px; display:inline-block; text-align:center;" value="0"></div>
        <div style="width:140px; text-align:right; margin-left:8px">Tạm tính: <input readonly class="form-control form-control-sm total-display text-end" style="width:140px; display:inline-block;" value="0 đ"></div>
      </div>
      <div class="customer-list"></div>
    `;
    repList.appendChild(el);

    el.querySelector('.rep-remove').addEventListener('click', () => { el.remove(); computeAllTotals(); });
    el.querySelector('.add-passenger-btn').addEventListener('click', () => addPassenger(idx));
}

function addPassenger(repIdx) {
    const rep = repList.querySelector(`.representative[data-index="${repIdx}"]`);
    if (!rep) return;
    const list = rep.querySelector('.customer-list');
    const item = document.createElement('div');
    item.className = 'customer-item';
        item.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-md-4"><input class="form-control cust-name" placeholder="Họ và tên"></div>
                <div class="col-md-3"><select class="form-select cust-gender"><option value="">Giới tính</option><option>Nam</option><option>Nữ</option></select></div>
                <div class="col-md-3"><input type="date" class="form-control cust-dob"></div>
                <div class="col-md-2 text-end"><button type="button" class="btn btn-outline-danger btn-sm cust-remove">Xóa</button></div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-12"><input class="form-control form-control-sm cust-note" placeholder="Ghi chú (tùy chọn)"></div>
            </div>
        `;
    list.appendChild(item);
    item.querySelector('.cust-remove').addEventListener('click', () => { item.remove(); computeAllTotals(); });
    item.querySelector('.cust-dob').addEventListener('change', computeAllTotals);
    computeAllTotals();
}

function computeAllTotals() {
    const reps = Array.from(repList.querySelectorAll('.representative'));
    let baseTotal = 0; let totalQty = 0;
    const now = new Date(); const currentYear = now.getFullYear();
    reps.forEach(rep => {
        const customers = Array.from(rep.querySelectorAll('.customer-item'));
        let repAdult = 0, repChild = 0;
        customers.forEach(c => {
            const dob = c.querySelector('.cust-dob')?.value || '';
            if (!dob) return;
            const by = new Date(dob).getFullYear(); if (!by || isNaN(by)) return;
            const age = currentYear - by;
            if (age > 12) repAdult++; else repChild++;
        });
        const qty = repAdult + repChild;
        const repTotal = repAdult * (adultPrice || 0) + repChild * (childPrice || 0);
        const qtyEl = rep.querySelector('.qty-display'); const totalEl = rep.querySelector('.total-display');
        if (qtyEl) qtyEl.value = qty; if (totalEl) totalEl.value = repTotal ? repTotal.toLocaleString('vi-VN') + ' đ' : '0 đ';
        baseTotal += repTotal; totalQty += qty;
    });
    document.getElementById('rowSubtotalSum').textContent = baseTotal.toLocaleString('vi-VN') + ' ₫';
    const surcharge = Math.round(baseTotal * 0.1);
    document.getElementById('surchargeAmount').textContent = surcharge.toLocaleString('vi-VN') + ' ₫';
    const grandTotal = baseTotal + surcharge;
    document.getElementById('totalAmount').textContent = grandTotal.toLocaleString('vi-VN') + ' ₫';
    document.getElementById('totalAmountInput').value = grandTotal; document.getElementById('totalQuantityInput').value = totalQty;
}

// tour select change
const tourSelect = document.getElementById('tour_id');
if (tourSelect) tourSelect.addEventListener('change', function(){
    const opt = this.selectedOptions[0];
    adultPrice = parseInt(opt?.dataset?.adult || 0);
    childPrice = parseInt(opt?.dataset?.child || 0);
    const tourId = this.value;
    if (tourId) fetch('index.php?controller=booking&action=getSchedule&tour_id=' + tourId).then(r=>r.json()).then(data=>{
        const tbody = document.getElementById('scheduleBody'); tbody.innerHTML='';
        if (data && data.length) { data.forEach(item=>{ const row=document.createElement('tr'); row.innerHTML=`<td><strong>Ngày ${item.day}</strong></td><td style="text-align:left">${item.activity||''}</td>`; tbody.appendChild(row); }); document.getElementById('scheduleTable').style.display='table'; document.getElementById('scheduleBox').style.display='none'; }
        else { document.getElementById('scheduleTable').style.display='none'; document.getElementById('scheduleBox').style.display='block'; document.getElementById('scheduleBox').innerText='Không có lịch trình'; }
    }).catch(()=>{ document.getElementById('scheduleBox').innerText='Lỗi tải lịch trình'; document.getElementById('scheduleTable').style.display='none'; });
    computeAllTotals();
});

// before submit: generate arrays expected by server
document.getElementById('bookingForm').addEventListener('submit', function(e){
    repHidden.innerHTML = '';
    const reps = Array.from(repList.querySelectorAll('.representative'));
    const now = new Date(); const currentYear = now.getFullYear();
        reps.forEach(rep=>{
        const repName = rep.querySelector('.rep-name')?.value || '';
        const repEmail = rep.querySelector('.rep-email')?.value || '';
        const repPhone = rep.querySelector('.rep-phone')?.value || '';
        let repAdult=0, repChild=0; const customers = Array.from(rep.querySelectorAll('.customer-item'));
        const customersPayload = [];
        customers.forEach(c=>{
            const name = c.querySelector('.cust-name')?.value || '';
            const gender = c.querySelector('.cust-gender')?.value || '';
            const dob = c.querySelector('.cust-dob')?.value || '';
            const note = c.querySelector('.cust-note')?.value || '';
            if (!name) return; // skip unnamed
            const by = dob ? new Date(dob).getFullYear() : null;
            if (by && !isNaN(by)) {
                const age = currentYear - by;
                if (age > 12) repAdult++; else repChild++;
            }
            customersPayload.push({name: name, gender: gender, date: dob || null, note: note || null});
        });
        if (repAdult + repChild <= 0) return; // skip empty reps
        const type = repChild>0 ? 'Gia đình' : 'Cá nhân';
        function h(n,v){ const i=document.createElement('input'); i.type='hidden'; i.name=n; i.value=v; repHidden.appendChild(i); }
        h('fullname[]', repName); h('email[]', repEmail); h('phone[]', repPhone); h('quantity_adult[]', repAdult); h('quantity_child[]', repChild); h('type[]', type);
        // attach per-registrant customers JSON so server can persist into customers table
        h('customers[]', JSON.stringify(customersPayload));
    });
});

// init
addRepresentative(); computeAllTotals();
</script>

</body>
</html>