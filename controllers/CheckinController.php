<?php


require_once 'models/CheckinModel.php';

class CheckinController {
    public function handle() {
        $sub_action = $_GET['sub_action'] ?? 'list'; 
        
        $message_text = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
        $alert_type = (strpos($message_text, 'thành công') !== false) ? 'alert-success' : 'alert-danger';
        $message_html = $message_text ? '<div class="alert ' . $alert_type . ' mt-3" role="alert">' . $message_text . '</div>' : '';

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

            case 'undo_checkin':
                $bookingId = $_GET['booking_id'] ?? '';
                if (undoCheckin($bookingId)) {
                    $result = 'Hoàn tác Check-in thành công cho Booking ID: **' . htmlspecialchars($bookingId) . '**';
                } else {
                    $result = 'Hoàn tác Check-in thất bại. Booking ID **' . htmlspecialchars($bookingId) . '** chưa được Check-in hoặc không hợp lệ.';
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

    public function handleGuide() {
        $sub_action = $_GET['sub_action'] ?? 'search'; 
        
        $message_text = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
        $alert_type = (strpos($message_text, 'thành công') !== false) ? 'alert-success' : 'alert-danger';
        $message_html = $message_text ? '<div class="alert ' . $alert_type . ' mt-3" role="alert">' . $message_text . '</div>' : '';
        $message = $message_html;


        switch ($sub_action) {
            case 'perform':
                $bookingId = $_GET['booking_id'] ?? '';
                $keyword = $_GET['keyword'] ?? '';
                if (performCheckin($bookingId)) {
                    $result = 'Check-in thành công cho Booking ID: **' . htmlspecialchars($bookingId) . '**';
                } else {
                    $result = 'Check-in thất bại. Booking ID **' . htmlspecialchars($bookingId) . '** đã được Check-in hoặc không hợp lệ/chưa xác nhận.';
                }
                
                header('Location: index.php?action=guide_checkin&sub_action=search&keyword=' . urlencode($keyword) . '&message=' . urlencode($result));
                exit;

            case 'search':
            default:
                $keyword = $_GET['keyword'] ?? '';
                $bookings = [];
                if (!empty($keyword)) {
                    $bookings = getBookingListForCheckin($keyword); 
                }
                
                include 'views/guide/guide_checkin_search.php';
                break;
        }
    }
}