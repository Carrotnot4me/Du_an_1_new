<?php


require_once 'models/CheckinModel.php';

class CheckinController {
    public function handle() {
        $sub_action = $_GET['sub_action'] ?? 'list'; 
        
        $message_text = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
        $message_html = $message_text ? '<div class="alert alert-success mt-3" role="alert">' . $message_text . '</div>' : '';

        switch ($sub_action) {
            case 'checkin':
                $bookingId = $_GET['booking_id'] ?? '';
                if (performCheckin($bookingId)) {
                    $result = 'Check-in thành công cho Booking ID: **' . htmlspecialchars($bookingId) . '**';
                } else {
                    $result = 'Check-in thất bại. Booking ID **' . htmlspecialchars($bookingId) . '** đã được Check-in hoặc không hợp lệ/chưa xác nhận.';
                }
                
                header('Location: index.php?action=checkin&message=' . urlencode($result));
                exit;

            case 'list':
            default:
                $keyword = $_GET['keyword'] ?? '';
                $bookings = getBookingListForCheckin($keyword);
                $message = $message_html;
                include 'views/admin/checkin_list.php';
                break;
        }
    }
}