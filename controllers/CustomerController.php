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
        $email = $_GET['email'] ?? null;
        
        if ($email) {
            $customer = $this->customerModel->getByEmail($email);
            $bookings = $this->customerModel->getBookingsByEmail($email);
            $notes = $this->noteModel->getByEmail($email);
            
            echo json_encode([
                'customer' => $customer,
                'bookings' => $bookings,
                'notes' => $notes
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing email parameter']);
        }
        exit;
    }
}

