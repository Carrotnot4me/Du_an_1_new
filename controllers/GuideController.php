<?php
require_once './models/GuideModel.php';
require_once './models/BookingModel.php';

class GuideController {

    private $model;

    public function __construct() {
        $this->model = new GuideModel();
    }

    public function index() {
        $tours = $this->model->getAllTours();
        $departures = $this->model->getAllDepartures();
        $guides = $this->model->getGuides();
        $unassignedBookings = $this->model->getUnassignedBookings();

        include './views/admin/guide.php';
    }

    // POST handler to assign a guide (staff) to a booking
    public function assign() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=guides'); exit;
        }
        $guideId = $_POST['guide_id'] ?? null;
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$guideId || !$bookingId) {
            $_SESSION['flash'] = 'Thiếu thông tin phân lịch.';
            header('Location: index.php?action=guides'); exit;
        }
        // ensure booking exists and create departure if missing, then assign guide
        try {
            $bm = new BookingModel();
            $booking = $bm->getBookingById($bookingId);
            if (!$booking) {
                $_SESSION['flash'] = 'Booking không tồn tại.';
                header('Location: index.php?action=guides'); exit;
            }

            $db = connectDB();
            $db->beginTransaction();

            // if booking has no departuresId, create a new departure row
            $depId = $booking['departuresId'] ?? ($booking['departures_id'] ?? null);
            if (empty($depId)) {
                $newDep = $bm->insertDeparture([
                    'tour_id' => $booking['tourId'] ?? ($booking['tour_id'] ?? null),
                    'date_start' => $booking['dateStart'] ?? null,
                    'date_end' => $booking['dateEnd'] ?? null,
                    'meeting_point' => null,
                    'guide_id' => $guideId,
                    'driver_id' => null,
                ]);
                if ($newDep === false) {
                    throw new Exception('Không tạo được departures mới');
                }
                // set booking.departuresId to newDep
                $upd = $db->prepare("UPDATE bookings SET departuresId = ? WHERE id = ?");
                $upd->execute([$newDep, $bookingId]);
                $depId = $newDep;
            } else {
                // ensure departures.guideId is set to this guide
                $updDep = $db->prepare("UPDATE departures SET guideId = ? WHERE id = ?");
                $updDep->execute([$guideId, $depId]);
            }

            // assign guide to booking (staffId)
            $stmt = $db->prepare("UPDATE bookings SET staffId = ? WHERE id = ?");
            $stmt->execute([$guideId, $bookingId]);

            $db->commit();
            $_SESSION['flash'] = 'Phân lịch thành công.';
        } catch (Exception $e) {
            if (isset($db) && $db->inTransaction()) $db->rollBack();
            error_log('assign guide error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Lỗi khi phân lịch.';
        }
        header('Location: index.php?action=guides'); exit;
    }
}
