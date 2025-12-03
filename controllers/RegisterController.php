<?php
require_once './models/RegisterModel.php';

class RegisterController {
    private $model;

    public function __construct() {
        $this->model = new RegisterModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $error = $_SESSION['register_error'] ?? null;
        $success = $_SESSION['register_success'] ?? null;
        unset($_SESSION['register_error'], $_SESSION['register_success']);
        
        include './views/auth/register.php';
    }

    public function registerAdmin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=register_admin');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? null);

        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['register_error'] = 'Vui lòng điền đầy đủ Tên, Email và Mật khẩu.';
            header('Location: index.php?action=register_admin');
            exit;
        }

        if ($this->model->createAdmin($username, $email, $password, $phone)) {
            $_SESSION['register_success'] = 'Tài khoản Admin đã được tạo thành công! Bạn có thể đăng nhập ngay.';
            header('Location: index.php?action=register_admin');
            exit;
        } else {
            $_SESSION['register_error'] = 'Đăng ký thất bại. Có thể Email đã tồn tại.';
            header('Location: index.php?action=register_admin');
            exit;
        }
    }
}