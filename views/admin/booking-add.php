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
        .card-custom {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
        }
        .card-header-custom {
            background: #f8f9fa;
            padding: 18px 30px;
            font-weight: 600;
            font-size: 1.15rem;
            color: #2c3e50;
            border-bottom: 1px solid #e9ecef;
        }
        .form-control, .form-select {
            border-radius: 12px;
            height: 50px;
            font-size: 1rem;
        }
        .form-control-sm, .form-select-sm {
            height: 40px;
            font-size: 0.95rem;
            border-radius: 10px;
        }
        .table thead th {
            background: #e9ecef;
            font-weight: 600;
            color: #495057;
            padding: 14px 10px;
            font-size: 0.95rem;
            text-align: center;
        }
        .table td {
            padding: 10px;
            vertical-align: middle;
            text-align: center;
        }
        .total-box {
            background: #f8f9fa;
            border-left: 6px solid #28a745;
            padding: 25px 30px;
            text-align: right;
            font-size: 2rem;
            font-weight: bold;
            color: #dc3545;
        }
        .btn-complete {
            background: #28a745;
            color: white;
            font-size: 1.4rem;
            font-weight: bold;
            padding: 18px 0;
            border-radius: 14px;
            width: 100%;
            margin-top: 30px;
            box-shadow: 0 8px 25px rgba(40,167,69,0.4);
            transition: all 0.3s;
        }
        .btn-complete:hover {
            background: #218838;
            transform: translateY(-2px);
        }
        .btn-add-passenger {
            background: #17a2b8;
            color: white;
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .btn-remove {
            background: #dc3545;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
        }
        .schedule-box {
            background: #f8f9fa;
            border: 1px dashed #ccc;
            border-radius: 12px;
            padding: 25px;
            min-height: 120px;
            color: #6c757d;
            font-size: 1rem;
        }
        .qty-small {
            width: 80px !important;
            text-align: center;
        }
        .label-small {
            font-size: 0.95rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .schedule-table td {
            text-align: left;
            padding: 12px 10px;
        }
        .schedule-table td:first-child {
            font-weight: bold;
            width: 10%;
            text-align: center;
        }
        .qty-stack input {
            width: 100%;
            box-sizing: border-box;
        }
        .qty-stack { display: flex; flex-direction: column; }
    </style>
</head>
<body>

<div class="container">
    <!-- Hiển thị lỗi validation -->
    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-circle"></i> Lỗi:</strong>
            <ul style="margin-bottom: 0; padding-left: 20px;">
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <!-- Hiển thị thành công -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-check-circle"></i> Thành công:</strong> <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form id="bookingForm" action="index.php?action=booking-save" method="POST">

        <!-- THÔNG TIN TOUR – ĐÃ THÊM HƯỚNG DẪN VIÊN VÀO GIỮA -->
        <div class="card-custom">
            <div class="card-header-custom">Thông tin tour</div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- 1. TOUR -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Chọn Tour <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg" name="tour_id" id="tour_id" required>
                            <option value="">-- Chọn tour --</option>
                            <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['id'] ?>"
                                    data-adult="<?= $t['adult_price'] ?? 0 ?>"
                                    data-child="<?= $t['child_price'] ?? 0 ?>"
                                    data-schedule="<?= htmlspecialchars($t['schedule'] ?? '', ENT_QUOTES) ?>">
                                <?= htmlspecialchars($t['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. HƯỚNG DẪN VIÊN (ĐÃ THÊM VÀO GIỮA) -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Hướng dẫn viên</label>
                        <select class="form-select form-select-lg" name="staff_id">
                            <option value="">-- Chọn hướng dẫn viên --</option>
                            <?php foreach ($staffs as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 3. NGÀY KHỞI HÀNH / THÔNG TIN NGÀY MỚI -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Ngày khởi hành (nếu không chọn ngày có sẵn)</label>
                        <div class="d-flex gap-2">
                            <input type="date" class="form-control form-control-lg" name="date_start" placeholder="Ngày bắt đầu">
                            <input type="date" class="form-control form-control-lg" name="date_end" placeholder="Ngày kết thúc">
                        </div>
                    </div>
                </div> <!-- /.row g-4 -->

                <div class="row g-2 mt-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Điểm tập trung</label>
                        <input type="text" class="form-control form-control-lg" name="meeting_point" placeholder="VD: Sân bay Nội Bài">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tài xế</label>
                        <select class="form-select form-select-lg" name="driver_id">
                            <option value="">-- Chọn tài xế (tuỳ chọn) --</option>
                            <?php if (!empty($drivers)): foreach ($drivers as $dr): ?>
                            <option value="<?= $dr['id'] ?>"><?= htmlspecialchars($dr['name']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="form-text mt-2">Hoặc chọn 1 ngày khởi hành có sẵn ở phần "Ngày khởi hành" bên dưới (nếu có).</div>
                    </div>
                </div>
            </div> <!-- /.card-body -->
        </div> <!-- /.card-custom -->

        <!-- LỊCH TRÌNH -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">Lịch trình chi tiết tour</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 schedule-table" id="scheduleTable" style="display: none;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 10%; text-align: center;">Ngày</th>
                            <th>Hoạt động</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleBody">
                    </tbody>
                </table>
                <div class="schedule-box mt-3" id="scheduleBox">
                    Chọn tour để xem lịch trình
                </div>
            </div>
        </div>

        <!-- THÔNG TIN HÀNH KHÁCH -->
        <div class="card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <span class="fw-bold">Thông tin hành khách</span>
                <button type="button" id="addCustomer" class="btn btn-add-passenger">+ Khách hàng</button>
            </div>

            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Họ và tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th style="width:18%;">
                                <div class="d-flex flex-column">
                                    <small class="label-small">Số lượng</small>
                                </div>
                            </th>
                            <th style="width:12%;">Tạm tính</th>
                            <th>Loại khách</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody id="customerList">
                        <tr class="customer-row">
                            <td><input type="text" class="form-control form-control-sm" name="fullname[]" placeholder="Nhập họ tên" required></td>
                            <td><input type="email" class="form-control form-control-sm" name="email[]" placeholder="email@gmail.com"></td>
                            <td><input type="text" class="form-control form-control-sm" name="phone[]" placeholder="0901234567"></td>
                            <td>
                                <div class="qty-stack">
                                    <input type="number" class="form-control form-control-sm text-center qty-input qty-child" name="quantity_child[]" min="0" placeholder="Số lượng trẻ nhỏ" style="margin-bottom:6px;">
                                    <input type="number" class="form-control form-control-sm text-center qty-input qty-adult" name="quantity_adult[]"
                                    placeholder="Số lượng người lớn" min="0">
                                </div>
                            </td>
                            <td class="row-subtotal">0 ₫</td>
                            <td>
                                <select class="form-select form-select-sm" name="type[]">
                                    <option value="Gia đình">Gia đình</option>
                                    <option value="Cá nhân">Cá nhân</option>
                                    <option value="Bạn bè">Bạn bè</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-remove removeRow">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TỔNG TIỀN -->
        <div class="card-custom mt-4">
            <div class="card-body total-box">
                <div>
                    <div style="margin-bottom:12px; font-size:0.9rem; color:#666;">Tạm tính từng khách: <span id="rowSubtotalSum">0 ₫</span></div>
                    TỔNG TIỀN: <span id="totalAmount">0 ₫</span>
                </div>
                <div style="font-size:0.95rem; margin-top:12px; color:#333; display:flex; gap:12px; align-items:center; justify-content:flex-end;">
                    <div>Phí phát sinh (10%): <strong id="surchargeAmount">0 ₫</strong></div>
                    <!-- Extra fee input removed by request -->
                </div>
                <div id="totalNotice" style="margin-top:8px; color:#d9534f; font-weight:600; text-align:right; display:none;"></div>
            </div>
        </div>

        <!-- NÚT HOÀN TẤT -->
        <button type="submit" class="btn btn-complete">
            HOÀN TẤT ĐẶT TOUR
        </button>
    </form>
</div>

<!-- Hidden inputs -->
<input type="hidden" name="total_amount" id="totalAmountInput">
<input type="hidden" name="total_quantity" id="totalQuantityInput">

<script>
let adultPrice = 0, childPrice = 0;

document.getElementById('tour_id').addEventListener('change', function() {
    const tourId = this.value;
    const opt = this.selectedOptions[0];
    adultPrice = parseInt(opt.dataset.adult || 0);
    childPrice = parseInt(opt.dataset.child || 0);

    if (tourId) {
        // Fetch schedule from server
        fetch('index.php?controller=booking&action=getSchedule&tour_id=' + tourId)
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Schedule data:', data);
                const tbody = document.getElementById('scheduleBody');
                const table = document.getElementById('scheduleTable');
                const box = document.getElementById('scheduleBox');
                
                if (data && data.length > 0) {
                    tbody.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><strong>Ngày ${item.day}</strong></td>
                            <td style="text-align: left;">${item.activity}</td>
                        `;
                        tbody.appendChild(row);
                    });
                    table.style.display = 'table';
                    box.style.display = 'none';
                } else {
                    table.style.display = 'none';
                    box.style.display = 'block';
                    box.innerHTML = '<span class="text-danger fw-bold">Không có lịch trình</span>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('scheduleBox').innerHTML = '<span class="text-danger fw-bold">Lỗi tải lịch trình: ' + error.message + '</span>';
                document.getElementById('scheduleTable').style.display = 'none';
                document.getElementById('scheduleBox').style.display = 'block';
            });
    } else {
        document.getElementById('scheduleBox').innerHTML = 'Chọn tour để xem lịch trình';
        document.getElementById('scheduleTable').style.display = 'none';
        document.getElementById('scheduleBox').style.display = 'block';
    }

    updateTotal();
});

function updateTotal() {
    const rows = Array.from(document.querySelectorAll('.customer-row'));

    // Require fullname for all rows before calculating
    const incomplete = rows.some(row => {
        const nameInput = row.querySelector('input[name="fullname[]"]');
        return !nameInput || nameInput.value.trim() === '';
    });

    if (incomplete) {
        // Hide totals until all fullnames provided
        document.getElementById('totalNotice').style.display = '';
        document.getElementById('rowSubtotalSum').textContent = '0 ₫';
        document.getElementById('surchargeAmount').textContent = '0 ₫';
        document.getElementById('totalAmount').textContent = '0 ₫';
        document.getElementById('totalAmountInput').value = 0;
        document.getElementById('totalQuantityInput').value = 0;
        return;
    }

    document.getElementById('totalNotice').style.display = 'none';

    // Sum all row subtotals (extract from text)
    let baseTotal = 0;
    let totalQty = 0;
    rows.forEach(row => {
        const adultQty = parseInt(row.querySelector('.qty-adult')?.value) || 0;
        const childQty = parseInt(row.querySelector('.qty-child')?.value) || 0;
        baseTotal += adultQty * adultPrice + childQty * childPrice;
        totalQty += adultQty + childQty;
    });

    document.getElementById('rowSubtotalSum').textContent = formatVnd(baseTotal);

    // surcharge 20%
    const surcharge = Math.round(baseTotal * 0.1);
    const extra = parseInt(document.getElementById('extraFee')?.value) || 0;
    const grandTotal = baseTotal + surcharge + extra;

    document.getElementById('surchargeAmount').textContent = surcharge.toLocaleString('vi-VN') + ' ₫';
    document.getElementById('totalAmount').textContent = grandTotal.toLocaleString('vi-VN') + ' ₫';
    document.getElementById('totalAmountInput').value = grandTotal;
    document.getElementById('totalQuantityInput').value = totalQty;
}

function handleTypeChange(row) {
    const type = row.querySelector('select[name="type[]"]').value;
    const childInput = row.querySelector('.qty-child');
    const adultInput = row.querySelector('.qty-adult');

    if (!childInput || !adultInput) return;

    if (type === 'Gia đình') {
        childInput.style.display = '';
        childInput.disabled = false;
        adultInput.style.display = '';
        adultInput.disabled = false;
        if (childInput.value === '') childInput.value = 0;
        if (adultInput.value === '' || adultInput.value === '0') adultInput.value = 1;
    } else if (type === 'Cá nhân') {
        // single person: adult =1 fixed, child hidden
        childInput.style.display = 'none';
        childInput.disabled = true;
        childInput.value = 0;

        adultInput.style.display = '';
        adultInput.disabled = true;
        adultInput.value = 1;
    } else if (type === 'Bạn bè') {
        // group friends: only adult count editable
        childInput.style.display = 'none';
        childInput.disabled = true;
        childInput.value = 0;

        adultInput.style.display = '';
        adultInput.disabled = false;
        if (adultInput.value === '' || adultInput.value === '0') adultInput.value = 1;
    }

    updateTotal();
}

function formatVnd(amount) {
    return amount.toLocaleString('vi-VN') + ' ₫';
}

function updateRowSubtotal(row) {
    const adultQty = parseInt(row.querySelector('.qty-adult')?.value) || 0;
    const childQty = parseInt(row.querySelector('.qty-child')?.value) || 0;
    const subtotal = adultQty * adultPrice + childQty * childPrice;
    const cell = row.querySelector('.row-subtotal');
    if (cell) {
        const nameInput = row.querySelector('input[name="fullname[]"]');
        if (!nameInput || nameInput.value.trim() === '') {
            cell.textContent = '0 ₫';
        } else {
            cell.textContent = formatVnd(subtotal);
        }
    }
}

function attachRowEvents(row) {
    const qtyInputs = row.querySelectorAll('.qty-input');
    qtyInputs.forEach(i => {
        i.addEventListener('input', () => {
            updateRowSubtotal(row);
            updateTotal();
        });
    });
    const nameInput = row.querySelector('input[name="fullname[]"]');
    if (nameInput) {
        nameInput.addEventListener('input', () => {
            updateRowSubtotal(row);
            updateTotal();
        });
    }
    const typeSelect = row.querySelector('select[name="type[]"]');
    if (typeSelect) {
        typeSelect.addEventListener('change', () => {
            handleTypeChange(row);
            updateRowSubtotal(row);
            updateTotal();
        });
    }
    const removeBtn = row.querySelector('.removeRow');
    if (removeBtn) removeBtn.addEventListener('click', () => { row.remove(); updateTotal(); });
}

// Thêm khách
document.getElementById('addCustomer').addEventListener('click', () => {
    const tbody = document.getElementById('customerList');
    const row = document.createElement('tr');
    row.className = 'customer-row';
    row.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" name="fullname[]" placeholder="Nhập họ tên" required></td>
        <td><input type="email" class="form-control form-control-sm" name="email[]"></td>
        <td><input type="text" class="form-control form-control-sm" name="phone[]"></td>
        <td>
            <div class="qty-stack">
                <input type="number" class="form-control form-control-sm text-center qty-input qty-child" name="quantity_child[]" value="0" min="0" style="margin-bottom:6px;">
                <input type="number" class="form-control form-control-sm text-center qty-input qty-adult" name="quantity_adult[]" value="1" min="0">
            </div>
        </td>
        <td class="row-subtotal">0 ₫</td>
        <td>
            <select class="form-select form-select-sm" name="type[]">
                <option value="Gia đình">Gia đình</option>
                <option value="Cá nhân">Cá nhân</option>
                <option value="Bạn bè">Bạn bè</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-remove removeRow">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    attachRowEvents(row);
    handleTypeChange(row);
    updateRowSubtotal(row);
    updateTotal();
});

// extra fee change (guarded in case the element was removed)
const __extraFeeEl = document.getElementById('extraFee');
if (__extraFeeEl) __extraFeeEl.addEventListener('input', updateTotal);

// Attach events to existing rows on load
document.querySelectorAll('.customer-row').forEach(r => {
    attachRowEvents(r);
    updateRowSubtotal(r);
});

// Khởi tạo
updateTotal();
</script>

</body>
</html>