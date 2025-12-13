<?php
require_once __DIR__ . '/../commons/function.php';

class SupplierModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        $sql = "
            SELECT 
                s.*,
                CASE s.service_type
                    WHEN 'khach_san' THEN 'Khách sạn'
                    WHEN 'nha_hang' THEN 'Nhà hàng'
                    WHEN 'xe' THEN 'Xe'
                    WHEN 'may_bay' THEN 'Máy bay'
                    ELSE 'Khác'
                END as service_type_name
            FROM suppliers s
            ORDER BY s.name ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getById($id) {
        $sql = "
            SELECT 
                s.*,
                CASE s.service_type
                    WHEN 'khach_san' THEN 'Khách sạn'
                    WHEN 'nha_hang' THEN 'Nhà hàng'
                    WHEN 'xe' THEN 'Xe'
                    WHEN 'may_bay' THEN 'Máy bay'
                    ELSE 'Khác'
                END as service_type_name
            FROM suppliers s
            WHERE s.id = :id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getByServiceType($serviceType) {
        $sql = "
            SELECT 
                s.*,
                CASE s.service_type
                    WHEN 'khach_san' THEN 'Khách sạn'
                    WHEN 'nha_hang' THEN 'Nhà hàng'
                    WHEN 'xe' THEN 'Xe'
                    WHEN 'may_bay' THEN 'Máy bay'
                    ELSE 'Khác'
                END as service_type_name
            FROM suppliers s
            WHERE s.service_type = :service_type
            ORDER BY s.name ASC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':service_type' => $serviceType]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function create($data) {
        try {
            $this->conn->beginTransaction();

            $nextId = $this->getNextId();
            $data['id'] = $data['id'] ?? $nextId;

            $sql = "INSERT INTO suppliers (id, name, service_type, address, email, phone, website, description, logo)
                    VALUES (:id, :name, :service_type, :address, :email, :phone, :website, :description, :logo)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $data['id'],
                ':name' => $data['name'],
                ':service_type' => $data['service_type'],
                ':address' => $data['address'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':website' => $data['website'] ?? null,
                ':description' => $data['description'] ?? null,
                ':logo' => $data['logo'] ?? null
            ]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log('SupplierModel create error: ' . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $sql = "UPDATE suppliers SET 
                    name = :name,
                    service_type = :service_type,
                    address = :address,
                    email = :email,
                    phone = :phone,
                    website = :website,
                    description = :description,
                    logo = :logo
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':service_type' => $data['service_type'],
                ':address' => $data['address'] ?? null,
                ':email' => $data['email'] ?? null,
                ':phone' => $data['phone'] ?? null,
                ':website' => $data['website'] ?? null,
                ':description' => $data['description'] ?? null,
                ':logo' => $data['logo'] ?? null
            ]);
            
            return $result && $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log('SupplierModel update error: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM suppliers WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (Exception $e) {
            error_log('SupplierModel delete error: ' . $e->getMessage());
            return false;
        }
    }

    public function getServiceTypes() {
        return [
            'khach_san' => 'Khách sạn',
            'nha_hang' => 'Nhà hàng',
            'xe' => 'Xe',
            'may_bay' => 'Máy bay',
            'khac' => 'Khác'
        ];
    }

    private function getNextId() {
        $sql = "SELECT MAX(CAST(id AS UNSIGNED)) + 1 AS next_id FROM suppliers";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch();
        return $row['next_id'] ?? 1;
    }
}

