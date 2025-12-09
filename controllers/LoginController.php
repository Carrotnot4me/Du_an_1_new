<?php
require_once './models/LoginModel.php';

class LoginController {
    private $model;

    public function __construct() {
        $this->model = new LoginModel();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        // Nếu đã đăng nhập → chuyển thẳng vào dashboard
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            header('Location: index.php?action=dashboard');
            exit;
        }

        // Lấy thông báo lỗi (nếu có)
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        include './views/users/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Gọi model kiểm tra
            $user = $this->model->checkLogin($email, $password);

            if ($user) {

                // Chuẩn hoá thông tin đưa vào SESSION
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role']
                ];

                header('Location: index.php?action=dashboard');
                exit;
            } else {
                $_SESSION['login_error'] = 'Email hoặc mật khẩu không chính xác, hoặc bạn không có quyền truy cập.';
                header('Location: index.php?action=login');
                exit;
            }
        }

        // Nếu vào link login không phải POST
        header('Location: index.php?action=login');
        exit;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
        
        header('Location: index.php?action=login');
        exit;
    }
}
