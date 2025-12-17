<?php
require_once __DIR__ . '/../commons/env.php';
require_once __DIR__ . '/../commons/function.php';

class PaymentHistoryModel {
    private $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    public function add($registrantId, $bookingId = null, $amount = 0, $method = null, $note = null) {
        try {
            // Detect existing columns in payment_history to support different schemas
            $colStmt = $this->conn->query("DESCRIBE payment_history");
            $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);

            $insertCols = [];
            $params = [];

            if (in_array('registrant_id', $cols)) {
                $insertCols[] = 'registrant_id';
                $params[':registrant_id'] = $registrantId;
            }
            if (in_array('booking_id', $cols) && $bookingId !== null) {
                $insertCols[] = 'booking_id';
                $params[':booking_id'] = $bookingId;
            }
            if (in_array('amount', $cols)) {
                $insertCols[] = 'amount';
                $params[':amount'] = $amount;
            }
            if (in_array('method', $cols) && $method !== null) {
                $insertCols[] = 'method';
                $params[':method'] = $method;
            }
            if (in_array('note', $cols) && $note !== null) {
                $insertCols[] = 'note';
                $params[':note'] = $note;
            }

            if (empty($insertCols)) {
                // nothing to insert
                return false;
            }

            $placeholders = implode(',', array_map(function($c){ return ':' . $c; }, $insertCols));
            $sql = "INSERT INTO payment_history (" . implode(',', $insertCols) . ") VALUES ($placeholders)";
            $stmt = $this->conn->prepare($sql);
            $ok = $stmt->execute($params);
            if ($ok) return (int)$this->conn->lastInsertId();
            return false;
        } catch (Exception $e) {
            error_log('PaymentHistoryModel::add error: ' . $e->getMessage());
            return false;
        }
    }

    public function getByRegistrant($registrantId) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM payment_history WHERE registrant_id = ? ORDER BY created_at DESC");
            $stmt->execute([$registrantId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('PaymentHistoryModel::getByRegistrant error: ' . $e->getMessage());
            return [];
        }
    }

}
