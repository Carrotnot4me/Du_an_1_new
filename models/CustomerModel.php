<?php
require_once __DIR__ . '/../commons/env.php';

class CustomerModel {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    /**
     * Insert a customer linked to a registrant (booking_registrants.id)
     * Returns inserted id or false
     */
    public function add($registrantId, $name, $date = null, $gender = null, $note = null) {
        try {
            // Use note column if exists in table
            $colStmt = $this->conn->query("DESCRIBE customers");
            $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            $hasNote = in_array('note', $cols);
            if ($hasNote) {
                $stmt = $this->conn->prepare("INSERT INTO customers (id, name, date, gender, registrants_id, note) VALUES (?, ?, ?, ?, ?, ?)");
            } else {
                $stmt = $this->conn->prepare("INSERT INTO customers (id, name, date, gender, registrants_id) VALUES (?, ?, ?, ?, ?)");
            }
            // determine next id (keep consistent with other patterns)
            $idRow = $this->conn->query("SELECT COALESCE(MAX(id),0)+1 AS next_id FROM customers")->fetch(PDO::FETCH_ASSOC);
            $newId = $idRow ? (int)$idRow['next_id'] : 1;
            if ($hasNote) {
                $ok = $stmt->execute([$newId, $name, $date, $gender, $registrantId, $note]);
            } else {
                $ok = $stmt->execute([$newId, $name, $date, $gender, $registrantId]);
            }
            return $ok ? $newId : false;
        } catch (Exception $e) {
            error_log('CustomerModel.add error: ' . $e->getMessage());
            return false;
        }
    }

    public function getByRegistrant($registrantId) {
        $stmt = $this->conn->prepare("SELECT * FROM customers WHERE registrants_id = ? ORDER BY id ASC");
        $stmt->execute([$registrantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
