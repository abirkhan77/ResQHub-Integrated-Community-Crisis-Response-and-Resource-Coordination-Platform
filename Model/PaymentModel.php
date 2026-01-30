<?php

require_once __DIR__ . '/Database.php';

class PaymentModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function createTransaction($donationId, $paymentMethod, $reference)
    {
        $sql = "INSERT INTO payment_transaction
                (donation_id, payment_method, transaction_reference)
                VALUES
                (:donation_id, :payment_method, :transaction_reference)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':donation_id'           => $donationId,
            ':payment_method'        => $paymentMethod,
            ':transaction_reference' => $reference
        ]);
    }

    public function getTransactionsByDonation($donationId)
    {
        $sql = "SELECT transaction_id, payment_method, transaction_reference,
                       payment_status, processed_at
                FROM payment_transaction
                WHERE donation_id = :donation_id
                ORDER BY processed_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':donation_id' => $donationId]);

        return $stmt->fetchAll();
    }

    public function getTransactionById($transactionId)
    {
        $sql = "SELECT *
                FROM payment_transaction
                WHERE transaction_id = :transaction_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':transaction_id' => $transactionId]);

        return $stmt->fetch();
    }

    public function updatePaymentStatus($transactionId, $status)
    {
        $sql = "UPDATE payment_transaction
                SET payment_status = :status
                WHERE transaction_id = :transaction_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'         => $status,
            ':transaction_id'=> $transactionId
        ]);
    }

    public function getAllTransactions()
    {
        $sql = "SELECT pt.transaction_id, pt.payment_method, pt.payment_status,
                       pt.processed_at, d.donation_amount, d.currency_code,
                       u.full_name AS donor_name
                FROM payment_transaction pt
                JOIN donation d ON pt.donation_id = d.donation_id
                JOIN users u ON d.donor_id = u.user_id
                ORDER BY pt.processed_at DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }
}