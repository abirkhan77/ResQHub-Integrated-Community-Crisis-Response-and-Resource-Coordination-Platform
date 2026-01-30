<?php

require_once __DIR__ . '/../Model/PaymentModel.php';
require_once __DIR__ . '/../Model/DonationModel.php';
require_once __DIR__ . '/../Model/ActivityLogModel.php';

class PaymentController
{
    private $paymentModel;
    private $donationModel;
    private $logModel;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) 
        {
            $this->jsonResponse(false, 'Unauthorized');
        }

        $this->paymentModel  = new PaymentModel();
        $this->donationModel = new DonationModel();
        $this->logModel      = new ActivityLogModel();
    }

    /* CREATE PAYMENT TRANSACTION */

    public function create($data)
    {
        if (
            empty($data['donation_id']) ||
            empty($data['payment_method']) ||
            empty($data['transaction_reference'])
        ) 
        {
            $this->jsonResponse(false, 'Invalid payment data');
        }

        $donation = $this->donationModel
            ->getDonationById($data['donation_id']);

        if (!$donation) 
        {
            $this->jsonResponse(false, 'Donation not found');
        }

        if 
        (
            $_SESSION['role'] !== 'admin' &&
            $donation['donor_id'] != $_SESSION['user_id']
        ) 
        
        {
            $this->jsonResponse(false, 'Access denied');
        }

        $created = $this->paymentModel->createTransaction(
            $data['donation_id'],
            $data['payment_method'],
            $data['transaction_reference']
        );

        if ($created) 
        {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Created payment transaction',
                'payment',
                $data['donation_id']
            );

            $this->jsonResponse(true, 'Payment transaction created');
        }

        $this->jsonResponse(false, 'Failed to create payment transaction');
    }

    /* UPDATE PAYMENT STATUS (Admin Only) */

    public function updateStatus($data)
    {
        if ($_SESSION['role'] !== 'admin') 
        {
            $this->jsonResponse(false, 'Access denied');
        }

        if (
            empty($data['transaction_id']) ||
            empty($data['status'])
        ) 
        
        {
            $this->jsonResponse(false, 'Invalid input');
        }

        $updated = $this->paymentModel
            ->updatePaymentStatus($data['transaction_id'], $data['status']);

        if ($updated) 
        {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Updated payment status',
                'payment',
                $data['transaction_id']
            );

            $this->jsonResponse(true, 'Payment status updated');
        }

        $this->jsonResponse(false, 'Failed to update payment status');
    }

    /* VIEW TRANSACTIONS */

    public function viewByDonation($data)
    {
        if (empty($data['donation_id'])) 
        {
            $this->jsonResponse(false, 'Donation ID required');
        }

        $donation = $this->donationModel
            ->getDonationById($data['donation_id']);

        if (!$donation) 
        {
            $this->jsonResponse(false, 'Donation not found');
        }

        if (
            $_SESSION['role'] !== 'admin' &&
            $donation['donor_id'] != $_SESSION['user_id']
        ) 
        
        {
            $this->jsonResponse(false, 'Access denied');
        }

        $transactions = $this->paymentModel
            ->getTransactionsByDonation($data['donation_id']);

        $this->jsonResponse(true, 'Transactions fetched', $transactions);
    }

    /* ADMIN: ALL TRANSACTIONS */

    public function all()
    {
        if ($_SESSION['role'] !== 'admin') 
        {
            $this->jsonResponse(false, 'Access denied');
        }

        $transactions = $this->paymentModel->getAllTransactions();
        $this->jsonResponse(true, 'All transactions fetched', $transactions);
    }

    /* JSON RESPONSE */

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