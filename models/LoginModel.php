<?php
require_once './commons/function.php';

class LoginModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function checkLogin($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT id, email, password, username, role FROM users WHERE email = :email AND role = 'admin'");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    unset($user['password']);
                    return $user;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("LoginModel Error: " . $e->getMessage());
            return false;
        }
    }
}