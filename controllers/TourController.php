<?php
require_once './models/TourModel.php';

class TourController {
    private $model;

    public function __construct() {
        $this->model = new TourModel();
    }

    public function list() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=showLogin');
            exit;
        }
        
        if (($_SESSION['user_role'] ?? 'user') !== 'admin') {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=trangchu');
            exit;
        }

        $tours = [];
        $tourObjects = $this->model->getAll();
        
        foreach ($tourObjects as $t) {
            $row = is_array($t) ? $t : (array)$t;
            $id = $row['id'] ?? '';
            
            $tours[] = [
                'id' => $id,
                'type' => $row['type'] ?? '',
                'name' => $row['name'] ?? '',
                'tour_code' => $row['tour_code'] ?? '',
                'main_destination' => $row['main_destination'] ?? '',
                'short_description' => $row['short_description'] ?? '',
                'max_people' => $row['max_people'] ?? null,
                'images' => $this->model->getImages($id),
                'price' => ['adult' => (int)($row['adult_price'] ?? 0), 'child' => (int)($row['child_price'] ?? 0)],
                'policy' => $this->model->getPolicy($id),
                'schedule' => $this->model->getSchedule($id),
            ];
        }

        include './views/admin/tour-list.php';
    }

    public function edit() {
        // Get tour ID from URL
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }

        // Fetch tour data
        $row = $this->model->getTourById($id);
        if (!$row) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }

        // Normalize to array
        $row = is_array($row) ? $row : (array)$row;

        // Build tour data structure
        $tour = [
            'id' => $row['id'] ?? $id,
            'type' => $row['type'] ?? '',
            'name' => $row['name'] ?? '',
            'tour_code' => $row['tour_code'] ?? '',
            'main_destination' => $row['main_destination'] ?? '',
            'short_description' => $row['short_description'] ?? '',
            'max_people' => $row['max_people'] ?? null,
            'images' => $this->model->getImages($id),
            'price' => ['adult' => (int)($row['adult_price'] ?? 0), 'child' => (int)($row['child_price'] ?? 0)],
            'policy' => $this->model->getPolicy($id),
            'schedule' => $this->model->getSchedule($id),
        ];

        // Fetch departures for this tour
        $departures = $this->model->getDeparturesByTourId($id);

        include './views/admin/tour-edit.php';
    }

    public function getTours() {
        header('Content-Type: application/json; charset=utf-8');
        $tours = $this->model->getAll();
        // normalize to expected frontend shape: images[], price:{adult,child}, policy, schedule
        $out = array_map(function($t) {
            return [
                'id' => $t->id,
                'type' => $t->type,
                'name' => $t->name,
                'tour_code' => $t->tour_code,
                'main_destination' => $t->main_destination,
                'short_description' => $t->short_description,
                'max_people' => $t->max_people,
                'images' => $this->getImagesFor($t->id),
                'price' => ['adult' => (int)$t->adult_price, 'child' => (int)$t->child_price],
                'policy' => $this->getPolicyFor($t->id),
                'schedule' => $this->getScheduleFor($t->id)
            ];
        }, $tours);
        echo json_encode($out, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function add() {
        // Support both JSON (AJAX) and regular form POST
        $isJson = false;
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $isJson = true;
        }

        if ($isJson) header('Content-Type: application/json; charset=utf-8');

        // try JSON body first
        $input = null;
        if ($isJson) {
            $input = json_decode(file_get_contents('php://input'), true);
        }

        // fallback to form POST
        if (!$input || !is_array($input)) {
            // build input from $_POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
                $input = [];
                $input['type'] = $_POST['type'] ?? '';
                $input['name'] = $_POST['name'] ?? '';
                $input['tour_code'] = $_POST['tour_code'] ?? '';
                $input['main_destination'] = $_POST['main_destination'] ?? '';
                $input['short_description'] = $_POST['short_description'] ?? '';
                $input['max_people'] = isset($_POST['max_people']) ? (int)$_POST['max_people'] : null;
                $imgs = $_POST['images'] ?? '';
                $input['images'] = $imgs !== '' ? array_values(array_filter(array_map('trim', explode(',', $imgs)))) : [];
                $input['price'] = ['adult' => (int)($_POST['price_adult'] ?? 0), 'child' => (int)($_POST['price_child'] ?? 0)];
                $input['policy'] = ['cancel' => $_POST['policy_cancel'] ?? '', 'refund' => $_POST['policy_refund'] ?? ''];
                // schedules from arrays schedule_day[] and schedule_activity[]
                $input['schedule'] = [];
                $days = $_POST['schedule_day'] ?? [];
                $acts = $_POST['schedule_activity'] ?? [];
                $count = max(count($days), count($acts));
                for ($i = 0; $i < $count; $i++) {
                    $day = isset($days[$i]) ? (int)$days[$i] : null;
                    $act = isset($acts[$i]) ? $acts[$i] : '';
                    if ($day || $act) $input['schedule'][] = ['day' => $day ?: 1, 'activity' => $act];
                }
                // attach details if present
                $detailsJson = $_POST['schedule_details_json'] ?? '';
                $detailsPerSchedule = [];
                if ($detailsJson) {
                    $decoded = json_decode($detailsJson, true);
                    if (is_array($decoded)) $detailsPerSchedule = $decoded;
                }
                foreach ($input['schedule'] as $idx => &$sch) {
                    $sch['details'] = $detailsPerSchedule[$idx] ?? [];
                }
                unset($sch);
            }
        }

        if (!$input || !is_array($input)) {
            if ($isJson) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid payload']);
                exit;
            } else {
                // nothing to do: redirect back
                header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
                exit;
            }
        }

        $ok = $this->model->create($input);

        if ($isJson) {
            if ($ok) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Insert failed']);
            }
            exit;
        } else {
            // redirect back to list for regular form POST
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }
    }

    public function delete() {
        // Accept JSON/AJAX or regular form POST
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $isJson = stripos($contentType, 'application/json') !== false ||
                  (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

        if ($isJson) header('Content-Type: application/json; charset=utf-8');

        $input = [];
        if ($isJson) {
            $input = json_decode(file_get_contents('php://input'), true) ?: [];
        }
        $id = $input['id'] ?? $_POST['id'] ?? $_GET['id'] ?? null;
        if (!$id) {
            if ($isJson) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Missing id']);
                exit;
            } else {
                header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
                exit;
            }
        }

        $success = $this->model->delete($id);

        if ($isJson) {
            echo json_encode(['success' => $success]);
            exit;
        } else {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }
    }

    public function update() {
        // Build input from POST form
        $input = [];
        $input['id'] = $_POST['id'] ?? null;
        $input['type'] = $_POST['type'] ?? '';
        $input['name'] = $_POST['name'] ?? '';
        $input['tour_code'] = $_POST['tour_code'] ?? '';
        $input['main_destination'] = $_POST['main_destination'] ?? '';
        $input['short_description'] = $_POST['short_description'] ?? '';
        $input['max_people'] = isset($_POST['max_people']) ? (int)$_POST['max_people'] : null;
        
        $imgs = $_POST['images'] ?? '';
        $input['images'] = $imgs !== '' ? array_values(array_filter(array_map('trim', explode(',', $imgs)))) : [];
        
        $input['price'] = ['adult' => (int)($_POST['price_adult'] ?? 0), 'child' => (int)($_POST['price_child'] ?? 0)];
        $input['policy'] = ['cancel' => $_POST['policy_cancel'] ?? '', 'refund' => $_POST['policy_refund'] ?? ''];
        
        // schedules from arrays
        $input['schedule'] = [];
        $days = $_POST['schedule_day'] ?? [];
        $acts = $_POST['schedule_activity'] ?? [];
        $count = max(count($days), count($acts));
        for ($i = 0; $i < $count; $i++) {
            $day = isset($days[$i]) ? (int)$days[$i] : null;
            $act = isset($acts[$i]) ? $acts[$i] : '';
            if ($day || $act) $input['schedule'][] = ['day' => $day ?: 1, 'activity' => $act];
        }
        // attach details if present
        $detailsJson = $_POST['schedule_details_json'] ?? '';
        $detailsPerSchedule = [];
        if ($detailsJson) {
            $decoded = json_decode($detailsJson, true);
            if (is_array($decoded)) $detailsPerSchedule = $decoded;
        }
        foreach ($input['schedule'] as $idx => &$sch) {
            $sch['details'] = $detailsPerSchedule[$idx] ?? [];
        }
        unset($sch);

        // schedule details JSON (serialized by JS). It's an array where each index corresponds to schedule index
        $detailsJson = $_POST['schedule_details_json'] ?? '';
        $detailsPerSchedule = [];
        if ($detailsJson) {
            $decoded = json_decode($detailsJson, true);
            if (is_array($decoded)) $detailsPerSchedule = $decoded;
        }

        // attach details to each schedule entry if present
        foreach ($input['schedule'] as $idx => &$sch) {
            $sch['details'] = $detailsPerSchedule[$idx] ?? [];
        }
        unset($sch);

        if (!$input['id']) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }

        // Update in model: delete old related data and insert new
        // For simplicity, we delete and re-insert related tables
        $ok = $this->model->updateTourComplete($input['id'], $input);

        // Redirect to tour list after update
        header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
        exit;
    }

    // Xóa một departure (đợt khởi hành)
    public function deleteDeparture() {
        $id = $_POST['departure_id'] ?? $_GET['departure_id'] ?? null;
        $tourId = $_POST['tour_id'] ?? $_GET['tour_id'] ?? null;
        if (!$id) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }

        $ok = $this->model->deleteDepartureById($id);

        // Redirect back to edit page for the same tour if available
        if ($tourId) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=editTour&id=' . urlencode($tourId));
        } else {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
        }
        exit;
    }

    // Thêm đợt khởi hành (POST)
    public function addDeparture() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }
        $tourId = $_POST['tour_id'] ?? null;
        $data = [
            'tourId' => $tourId,
            'dateStart' => $_POST['dateStart'] ?? null,
            'dateEnd' => $_POST['dateEnd'] ?? null,
            'meetingPoint' => $_POST['meetingPoint'] ?? '',
            'guideId' => $_POST['guideId'] ?? null,
            'driver' => $_POST['driver'] ?? ''
        ];

        $ok = $this->model->createDeparture($data);
        header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=editTour&id=' . urlencode($tourId));
        exit;
    }

    // Cập nhật đợt khởi hành (POST)
    public function updateDeparture() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }
        $id = $_POST['departure_id'] ?? null;
        $tourId = $_POST['tour_id'] ?? null;
        if (!$id) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=editTour&id=' . urlencode($tourId));
            exit;
        }
        $data = [
            'dateStart' => $_POST['dateStart'] ?? null,
            'dateEnd' => $_POST['dateEnd'] ?? null,
            'meetingPoint' => $_POST['meetingPoint'] ?? '',
            'guideId' => $_POST['guideId'] ?? null,
            'driver' => $_POST['driver'] ?? ''
        ];

        $ok = $this->model->updateDeparture($id, $data);
        header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=editTour&id=' . urlencode($tourId));
        exit;
    }
    

    // Duplicate tour (create new tour from posted form data)
    public function duplicateTour() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
            exit;
        }

        $input = [];
        $input['type'] = $_POST['type'] ?? '';
        $input['name'] = $_POST['name'] ?? '';
        $input['tour_code'] = $_POST['tour_code'] ?? '';
        $input['main_destination'] = $_POST['main_destination'] ?? '';
        $input['short_description'] = $_POST['short_description'] ?? '';
        $input['max_people'] = isset($_POST['max_people']) ? (int)$_POST['max_people'] : null;
        $imgs = $_POST['images'] ?? '';
        $input['images'] = $imgs !== '' ? array_values(array_filter(array_map('trim', explode(',', $imgs)))) : [];
        $input['price'] = ['adult' => (int)($_POST['price_adult'] ?? 0), 'child' => (int)($_POST['price_child'] ?? 0)];
        $input['policy'] = ['cancel' => $_POST['policy_cancel'] ?? '', 'refund' => $_POST['policy_refund'] ?? ''];

        // schedules
        $input['schedule'] = [];
        $days = $_POST['schedule_day'] ?? [];
        $acts = $_POST['schedule_activity'] ?? [];
        $count = max(count($days), count($acts));
        for ($i = 0; $i < $count; $i++) {
            $day = isset($days[$i]) ? (int)$days[$i] : null;
            $act = isset($acts[$i]) ? $acts[$i] : '';
            if ($day || $act) $input['schedule'][] = ['day' => $day ?: 1, 'activity' => $act];
        }

        $newId = $this->model->duplicateTour($input);
        if ($newId) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=editTour&id=' . urlencode($newId));
        } else {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=tour-list');
        }
        exit;
    }

    // helpers
    private function getImagesFor($id) {
        return $this->model->getImages($id);
    }

    private function getPolicyFor($id) {
        return $this->model->getPolicy($id);
    }

    private function getScheduleFor($id) {
        return $this->model->getSchedule($id);
    }
}