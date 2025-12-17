<?php
require_once './models/UserModel.php';

class AuthController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function showLogin() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        // if user is already logged in, redirect to appropriate page
        if (!empty($_SESSION['user_id'])) {
            $role = $_SESSION['user_role'] ?? 'user';
            $redirect = htmlspecialchars($_SERVER['PHP_SELF']) . '?action=' . ($role === 'admin' ? 'tour-list' : 'trangchu');
            header('Location: ' . $redirect);
            exit;
        }
        $error = '';
        include './views/users/login.php';
    }

    public function showRegister() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        // if user is already logged in, redirect to appropriate page
        if (!empty($_SESSION['user_id'])) {
            $role = $_SESSION['user_role'] ?? 'user';
            $redirect = htmlspecialchars($_SERVER['PHP_SELF']) . '?action=' . ($role === 'admin' ? 'tour-list' : 'trangchu');
            header('Location: ' . $redirect);
            exit;
        }
        $error = '';
        include './views/users/register.php';
    }

    public function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        session_unset();
        session_destroy();
        $redirect = htmlspecialchars($_SERVER['PHP_SELF']) . '?action=showLogin';
        if (!headers_sent()) {
            header('Location: ' . $redirect);
            exit;
        } else {
            echo '<script>window.location.href="' . $redirect . '";</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=' . $redirect . '"></noscript>';
            exit;
        }
    }

    public function profile() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        // protect: only logged-in users can access profile
        if (empty($_SESSION['user_id'])) {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?action=showLogin');
            exit;
        }

        $user = $this->model->getById($_SESSION['user_id']);
        $success = '';
        $error = '';

        // Handle POST (avatar update)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newAvatar = $_POST['avatar_url'] ?? '';
            if ($newAvatar) {
                $ok = $this->model->updateAvatar($_SESSION['user_id'], $newAvatar);
                if ($ok) {
                    $_SESSION['user_avatar'] = $newAvatar;
                    // keep `$_SESSION['user']` in sync for views that use it
                    if (!empty($_SESSION['user']) && is_array($_SESSION['user'])) {
                        $_SESSION['user']['avatar'] = $newAvatar;
                    } else {
                        $_SESSION['user'] = [
                            'id' => $_SESSION['user_id'],
                            'email' => $_SESSION['user_email'] ?? '',
                            'username' => $user['username'] ?? ($_SESSION['user_email'] ?? 'User'),
                            'role' => $_SESSION['user_role'] ?? 'user',
                            'avatar' => $newAvatar
                        ];
                    }
                    $user['avatar'] = $newAvatar;
                    $success = 'Avatar đã cập nhật thành công.';
                } else {
                    $error = 'Cập nhật avatar thất bại.';
                }
            } else {
                $error = 'Vui lòng nhập URL avatar.';
            }
        }

        include './views/users/profile.php';
    }

    public function login() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $email = $_POST['login-email'] ?? '';
        $password = $_POST['login-password'] ?? '';

        if (!$email || !$password) {
            $error = 'Vui lòng nhập email và mật khẩu.';
            include './views/users/login.php';
            return;
        }

        $user = $this->model->getByEmail($email);
        if (!$user) {
            $error = 'Email không tồn tại.';
            include './views/users/login.php';
            return;
        }

        $hash = $user['password'] ?? '';
        $valid = false;

        if ($hash && password_get_info($hash)['algo']) {
            $valid = password_verify($password, $hash);
        }

        if (!$valid && preg_match('/^[a-f0-9]{64}$/i', $hash)) {
            $submittedHash = preg_match('/^[a-f0-9]{64}$/i', $password) ? $password : hash('sha256', $password);
            $valid = hash_equals(strtolower($hash), strtolower($submittedHash));
        }

        if (!$valid && $hash !== '') {
            $valid = ($password === $hash);
        }

        if (!$valid) {
            $error = 'Mật khẩu không đúng.';
            include './views/users/login.php';
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';
        $_SESSION['user_avatar'] = $user['avatar'] ?? 'https://ui-avatars.com/api/?name=User&background=random';

        // For views that expect a `$_SESSION['user']` array, keep it in sync
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'] ?? ($user['email'] ?? 'User'),
            'role' => $_SESSION['user_role'],
            'avatar' => $_SESSION['user_avatar']
        ];

        $role = $_SESSION['user_role'];
        $redirect = htmlspecialchars($_SERVER['PHP_SELF']) . '?action=' . ($role === 'admin' ? 'tour-list' : 'trangchu');

        if (!headers_sent()) {
            header('Location: ' . $redirect);
            exit;
        } else {
            echo '<script>window.location.href="' . $redirect . '";</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=' . $redirect . '"></noscript>';
            exit;
        }
    }

    public function register() {
        $email = $_POST['register-email'] ?? '';
        $password = $_POST['register-password'] ?? '';
        $username = $_POST['register-username'] ?? '';
        $phone = $_POST['register-phone'] ?? '';
        $genre = $_POST['register-genre'] ?? '';

        $error = '';
        if (!$email || !$password) {
            $error = 'Vui lòng điền email và mật khẩu.';
            include './views/users/register.php';
            return;
        }

        $exists = $this->model->getByEmail($email);
        if ($exists) {
            $error = 'Email đã tồn tại.';
            include './views/users/register.php';
            return;
        }

        if ($phone) {
            $existsPhone = $this->model->getByPhone($phone);
            if ($existsPhone) {
                $error = 'Số điện thoại đã được sử dụng.';
                include './views/users/register.php';
                return;
            }
        }

        $ok = $this->model->createUser(['email'=>$email,'password'=>$password,'username'=>$username,'phone'=>$phone,'genre'=>$genre]);
        if ($ok) {
            $success = 'Đăng ký thành công. Vui lòng đăng nhập.';
            include './views/users/login.php';
            return;
        } else {
            $error = 'Đăng ký thất bại.';
            include './views/users/register.php';
            return;
        }
    }
}

