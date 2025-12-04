<?php
require_once './models/CustomerModel.php';
require_once './models/BookingModel.php';
require_once './models/NoteModel.php';

class CustomerController {
    private $customerModel;
    private $bookingModel;
    private $noteModel;

    public function __construct() {
        $this->customerModel = new CustomerModel();
        $this->bookingModel = new BookingModel();
        $this->noteModel = new NoteModel();
    }

    public function list() {
        $customers = $this->customerModel->getAll();
        include './views/admin/customer-list.php';
    }

    public function getCustomers() {
        header('Content-Type: application/json');
        echo json_encode($this->customerModel->getAll());
        exit;
    }

    public function getCustomerDetail() {
        header('Content-Type: application/json');
        try {
            $email = $_GET['email'] ?? null;
            
            if ($email) {
                $customer = $this->customerModel->getByEmail($email);
                $bookings = $this->customerModel->getBookingsByEmail($email);
                $notes = $this->noteModel->getByEmail($email);
                
                echo json_encode([
                    'customer' => $customer ? $customer : null,
                    'bookings' => $bookings ? $bookings : [],
                    'notes' => $notes ? $notes : []
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Missing email parameter']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            error_log('CustomerController getCustomerDetail Error: ' . $e->getMessage());
        }
        exit;
    }
}

