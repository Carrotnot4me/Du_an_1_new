
<?php
require_once './models/RevenueReportModel.php'; 

class PaymentController {
    private $model;

    public function __construct() {
        $this->model = new RevenueReportModel();
    }

    public function addPaymentForm($message = null, $status = null) {
        $bookingId = $_GET['bookingId'] ?? ''; 
        
        $allTours = $this->model->getAllTours();
        
        include './views/admin/payment_form.php';
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookingId = trim($_POST['bookingId'] ?? '');
            $amount = (int)($_POST['amount'] ?? 0);
            $method = trim($_POST['method'] ?? '');
            $status = trim($_POST['status'] ?? 'Chờ xử lý');
            $transactionCode = trim($_POST['transactionCode'] ?? '');
            $transactionCode = $transactionCode ?: NULL;

            if (empty($bookingId) || $amount <= 0 || empty($method) || empty($status)) {
                return $this->addPaymentForm("Vui lòng điền đầy đủ thông tin thanh toán hợp lệ.", 'danger');
            }

            $success = $this->model->addPayment($bookingId, $amount, $method, $status, $transactionCode);

            if ($success) {
                header("Location: index.php?action=revenue-report&message=Thanh toán booking #$bookingId đã được ghi nhận thành công.&status=success");
                exit();
            } else {
                return $this->addPaymentForm("Đã xảy ra lỗi khi ghi nhận thanh toán vào CSDL. Vui lòng kiểm tra Mã Booking và dữ liệu.", 'danger');
            }
        } else {
            $this->addPaymentForm();
        }
    }
    
    public function updatePaymentStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $paymentId = (int)($_GET['id'] ?? 0);
            $newStatus = trim($_GET['status'] ?? ''); 

            if ($paymentId <= 0 || empty($newStatus)) {
                header("Location: index.php?action=revenue-report&message=Lỗi: Thiếu ID thanh toán hoặc Trạng thái.&status=danger");
                exit();
            }

            $allowedStatuses = ['Hoàn thành', 'Chờ xử lý', 'Đã hủy', 'Đã cọc'];
            if (!in_array($newStatus, $allowedStatuses)) {
                header("Location: index.php?action=revenue-report&message=Lỗi: Trạng thái không hợp lệ.&status=danger");
                exit();
            }

            $success = $this->model->updatePaymentStatus($paymentId, $newStatus);

            if ($success) {
                header("Location: index.php?action=revenue-report&message=Cập nhật trạng thái thanh toán #$paymentId thành công: $newStatus&status=success");
                exit();
            } else {
                header("Location: index.php?action=revenue-report&message=Đã xảy ra lỗi khi cập nhật trạng thái thanh toán trong CSDL.&status=danger");
                exit();
            }
        }
    }
}