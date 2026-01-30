<?php

require_once __DIR__ . '/Database.php';

class DonationModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function createDonation($donorId, $amount, $currencyCode, $region, $remarks = null)
    {
        $sql = "INSERT INTO donation
                (donor_id, donation_amount, currency_code, donor_region, remarks)
                VALUES
                (:donor_id, :donation_amount, :currency_code, :donor_region, :remarks)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':donor_id'         => $donorId,
            ':donation_amount'  => $amount,
            ':currency_code'    => $currencyCode,
            ':donor_region'     => $region,
            ':remarks'          => $remarks
        ]);
    }

    public function getDonationById($donationId)
    {
        $sql = "SELECT d.*, u.full_name AS donor_name, u.email
                FROM donation d
                JOIN users u ON d.donor_id = u.user_id
                WHERE d.donation_id = :donation_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':donation_id' => $donationId]);

        return $stmt->fetch();
    }

    public function getDonationsByUser($userId)
    {
        $sql = "SELECT donation_id, donation_amount, currency_code, donor_region,
                       donation_status, donation_date
                FROM donation
                WHERE donor_id = :donor_id
                ORDER BY donation_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':donor_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getAllDonations()
    {
        $sql = "SELECT d.donation_id, d.donation_amount, d.currency_code,
                       d.donor_region, d.donation_status, d.donation_date,
                       u.full_name AS donor_name
                FROM donation d
                JOIN users u ON d.donor_id = u.user_id
                ORDER BY d.donation_date DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function updateDonationStatus($donationId, $status)
    {
        $sql = "UPDATE donation
                SET donation_status = :status
                WHERE donation_id = :donation_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'       => $status,
            ':donation_id'  => $donationId
        ]);
    }

    public function getDonationSummaryByCurrency()
    {
        $sql = "SELECT currency_code,
                       SUM(donation_amount) AS total_amount,
                       COUNT(*) AS total_donations
                FROM donation
                WHERE donation_status = 'completed'
                GROUP BY currency_code";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function getDonationSummaryByRegion()
    {
        $sql = "SELECT donor_region,
                       SUM(donation_amount) AS total_amount,
                       COUNT(*) AS total_donations
                FROM donation
                WHERE donation_status = 'completed'
                GROUP BY donor_region";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }
}<?php

require_once __DIR__ . '/Database.php';

class DonationModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function createDonation($donorId, $amount, $currencyCode, $region, $remarks = null)
    {
        $sql = "INSERT INTO donation
                (donor_id, donation_amount, currency_code, donor_region, remarks)
                VALUES
                (:donor_id, :donation_amount, :currency_code, :donor_region, :remarks)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':donor_id'         => $donorId,
            ':donation_amount'  => $amount,
            ':currency_code'    => $currencyCode,
            ':donor_region'     => $region,
            ':remarks'          => $remarks
        ]);
    }

    public function getDonationById($donationId)
    {
        $sql = "SELECT d.*, u.full_name AS donor_name, u.email
                FROM donation d
                JOIN users u ON d.donor_id = u.user_id
                WHERE d.donation_id = :donation_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':donation_id' => $donationId]);

        return $stmt->fetch();
    }

    public function getDonationsByUser($userId)
    {
        $sql = "SELECT donation_id, donation_amount, currency_code, donor_region,
                       donation_status, donation_date
                FROM donation
                WHERE donor_id = :donor_id
                ORDER BY donation_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':donor_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getAllDonations()
    {
        $sql = "SELECT d.donation_id, d.donation_amount, d.currency_code,
                       d.donor_region, d.donation_status, d.donation_date,
                       u.full_name AS donor_name
                FROM donation d
                JOIN users u ON d.donor_id = u.user_id
                ORDER BY d.donation_date DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function updateDonationStatus($donationId, $status)
    {
        $sql = "UPDATE donation
                SET donation_status = :status
                WHERE donation_id = :donation_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'       => $status,
            ':donation_id'  => $donationId
        ]);
    }

    public function getDonationSummaryByCurrency()
    {
        $sql = "SELECT currency_code,
                       SUM(donation_amount) AS total_amount,
                       COUNT(*) AS total_donations
                FROM donation
                WHERE donation_status = 'completed'
                GROUP BY currency_code";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function getDonationSummaryByRegion()
    {
        $sql = "SELECT donor_region,
                       SUM(donation_amount) AS total_amount,
                       COUNT(*) AS total_donations
                FROM donation
                WHERE donation_status = 'completed'
                GROUP BY donor_region";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }
}