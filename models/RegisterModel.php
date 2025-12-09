<?php
require_once './commons/function.php';

class RegisterModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    // Kiểm tra email đã tồn tại hay chưa
    public function checkEmailExists($email) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Chèn người dùng mới vào CSDL với mật khẩu đã hash
    public function createAdmin($username, $email, $password, $phone) {
        if ($this->checkEmailExists($email)) {
            return false; // Email đã tồn tại
        }

        // Hashing mật khẩu bằng hàm password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Tạo ID đơn giản (Bạn có thể cần thay đổi logic tạo ID theo database của bạn)
        $id = uniqid(); 

        try {
            $sql = "INSERT INTO users (id, username, email, password, phone, role, avatar) 
                    VALUES (:id, :username, :email, :password, :phone, 'admin', :avatar)";
            $stmt = $this->conn->prepare($sql);

            $avatar_default = 'https://cdn2.fptshop.com.vn/small/avatar_trang_1_cd729c335b.jpg';
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':avatar', $avatar_default);

            return $stmt->execute();

        } catch (PDOException $e) {
            error_log("RegisterModel Error: " . $e->getMessage());
            return false;
        }
    }
}