
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Thêm Thanh toán Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #fdf8e7; }
        .main-content { max-width: 600px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="main-content">
    <h3 class="mb-4 text-dark"><i class="bi bi-credit-card-fill me-2"></i> Ghi nhận Thanh toán</h3>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $status === 'success' ? 'success' : 'danger'; ?>" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=process-payment">
        <div class="mb-3">
            <label for="tourId" class="form-label">Tên Tour</label>
            <select class="form-select" id="tourId" name="tourId">
                <option value="">Chọn một Tour (Tùy chọn)</option>
                <?php 
                $allTours = $allTours ?? [];
                foreach ($allTours as $tour): 
                ?>
                    <option value="<?php echo htmlspecialchars($tour['id']); ?>">
                        <?php echo htmlspecialchars($tour['name']); ?> (Mã: <?php echo htmlspecialchars($tour['tour_code']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="form-text">Chọn Tour để tiện tham khảo hoặc lọc dữ liệu (không bắt buộc).</div>
        </div>
        <div class="mb-3">
            <label for="bookingId" class="form-label">Mã Booking</label>
            <input type="text" class="form-control" id="bookingId" name="bookingId" required 
                   value="<?php echo htmlspecialchars($bookingId ?? ''); ?>" placeholder="Ví dụ: 2">
            <div class="form-text">Nhập Mã Booking cần ghi nhận thanh toán.</div>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Số tiền Thanh toán (VNĐ)</label>
            <input type="number" class="form-control" id="amount" name="amount" required min="1000" placeholder="Ví dụ: 5000000">
        </div>
        <div class="mb-3">
            <label for="method" class="form-label">Phương thức Thanh toán</label>
            <select class="form-select" id="method" name="method" required>
                <option value="">Chọn phương thức</option>
                <option value="Tiền mặt">Tiền mặt</option>
                <option value="Chuyển khoản">Chuyển khoản</option>
                <option value="Thẻ tín dụng">Thẻ tín dụng</option>
                <option value="Khác">Khác</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Hoàn thành">Hoàn thành</option>
                <option value="Đã cọc">Đã cọc</option>
                <option value="Chờ xử lý">Chờ xử lý</option>
                <option value="Đã hủy">Đã hủy</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="transactionCode" class="form-label">Mã giao dịch (Nếu có)</label>
            <input type="text" class="form-control" id="transactionCode" name="transactionCode" placeholder="Ví dụ: TXN123456">
        </div>
        <button type="submit" class="btn btn-warning w-100">Ghi nhận Thanh toán</button>
    </form>
    <div class="mt-3 text-center">
        <a href="index.php?action=revenue-report" class="btn btn-outline-secondary btn-sm">Quay lại Báo cáo</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>