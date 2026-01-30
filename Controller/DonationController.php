
<?php

require_once __DIR__ . '/../Model/DonationModel.php';
require_once __DIR__ . '/../Model/PaymentModel.php';
require_once __DIR__ . '/../Model/CurrencyModel.php';
require_once __DIR__ . '/../Model/ActivityLogModel.php';

class DonationController
{
    private $donationModel;
    private $paymentModel;
    private $currencyModel;
    private $logModel;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(false, 'Unauthorized');
        }

        $this->donationModel = new DonationModel();
        $this->paymentModel  = new PaymentModel();
        $this->currencyModel = new CurrencyModel();
        $this->logModel      = new ActivityLogModel();
    }

    /* =========================
       CREATE DONATION
    ========================= */

    public function create($data)
    {
        if (
            empty($data['amount']) ||
            empty($data['currency'])
        ) {
            $this->jsonResponse(false, 'Invalid donation data');
        }

        if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            $this->jsonResponse(false, 'Invalid donation amount');
        }

        if (!$this->currencyModel->isCurrencySupported($data['currency'])) {
            $this->jsonResponse(false, 'Unsupported currency');
        }

        /* ✅ OPTION B FIX: Provide fallback region */
        $created = $this->donationModel->createDonation(
            $_SESSION['user_id'],
            $data['amount'],
            $data['currency'],
            'General',                  // <-- FIX HERE (NO NULL)
            $data['purpose'] ?? null
        );

        if ($created) {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Created donation',
                'donation',
                0
            );

            $this->jsonResponse(true, 'Donation created successfully');
        }

        $this->jsonResponse(false, 'Failed to create donation');
    }

    /* =========================
       READ
    ========================= */

    public function view($data)
    {
        if (empty($data['donation_id'])) {
            $this->jsonResponse(false, 'Donation ID required');
        }

        $donation = $this->donationModel->getDonationById($data['donation_id']);

        if (!$donation) {
            $this->jsonResponse(false, 'Donation not found');
        }

        if (
            $_SESSION['role'] !== 'admin' &&
            $donation['donor_id'] != $_SESSION['user_id']
        ) {
            $this->jsonResponse(false, 'Access denied');
        }

        $this->jsonResponse(true, 'Donation fetched', $donation);
    }

    public function myDonations()
    {
        $donations = $this->donationModel
            ->getDonationsByUser($_SESSION['user_id']);

        $this->jsonResponse(true, 'Donations fetched', $donations);
    }

    /* =========================
       JSON RESPONSE
    ========================= */

    private function jsonResponse($success, $message, $data = null)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }
}
