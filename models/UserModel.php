<?php
require_once './commons/function.php';

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByPhone($phone) {
        $sql = "SELECT * FROM users WHERE phone = :phone LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        try {
            $sql = "INSERT INTO users (id, email, password, username, phone, genre, role, avatar) VALUES (:id, :email, :password, :username, :phone, :genre, :role, :avatar)";
            $stmt = $this->conn->prepare($sql);

            $id = $data['id'] ?? null;
            if (!$id) {
                $stmtMax = $this->conn->query("SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM users");
                $row = $stmtMax->fetch();
                $id = $row['next_id'] ?? 1;
            }

            // store password as plain text per user request
            $password = $data['password'];

            return $stmt->execute([
                ':id' => $id,
                ':email' => $data['email'],
                ':password' => $password,
                ':username' => $data['username'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':genre' => $data['genre'] ?? null,
                ':role' => $data['role'] ?? 'user',
                ':avatar' => $data['avatar'] ?? 'https://ui-avatars.com/api/?name=User&background=random'
            ]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateAvatar($id, $avatarUrl) {
        try {
            $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([':avatar' => $avatarUrl, ':id' => $id]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
