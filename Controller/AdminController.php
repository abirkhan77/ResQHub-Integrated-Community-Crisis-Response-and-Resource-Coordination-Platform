<?php

require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/EmergencyRequestModel.php';
require_once __DIR__ . '/../Model/AssignmentModel.php';
require_once __DIR__ . '/../Model/DonationModel.php';
require_once __DIR__ . '/../Model/PaymentModel.php';
require_once __DIR__ . '/../Model/CurrencyModel.php';
require_once __DIR__ . '/../Model/NotificationModel.php';
require_once __DIR__ . '/../Model/ActivityLogModel.php';

class AdminController
{
    private $userModel;
    private $requestModel;
    private $assignmentModel;
    private $donationModel;
    private $paymentModel;
    private $currencyModel;
    private $notificationModel;
    private $logModel;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') 
        {
            $this->jsonResponse(false, 'Unauthorized access');
        }

        $this->userModel         = new UserModel();
        $this->requestModel      = new EmergencyRequestModel();
        $this->assignmentModel   = new AssignmentModel();
        $this->donationModel     = new DonationModel();
        $this->paymentModel      = new PaymentModel();
        $this->currencyModel     = new CurrencyModel();
        $this->notificationModel = new NotificationModel();
        $this->logModel          = new ActivityLogModel();
    }

    /* DASHBOARD */

    public function dashboard()
    {
        $users = $this->userModel->getAllUsers();

        $requests = method_exists($this->requestModel, 'getAllActiveRequests')
            ? $this->requestModel->getAllActiveRequests()
            : [];

        $donations = method_exists($this->donationModel, 'getAllDonations')
            ? $this->donationModel->getAllDonations()
            : [];

        $transactions = method_exists($this->paymentModel, 'getAllTransactions')
            ? $this->paymentModel->getAllTransactions()
            : [];

        $this->jsonResponse(true, 'Admin dashboard loaded', [
            'total_users'        => is_array($users) ? count($users) : 0,
            'active_requests'    => is_array($requests) ? count($requests) : 0,
            'total_donations'    => is_array($donations) ? count($donations) : 0,
            'total_transactions' => is_array($transactions) ? count($transactions) : 0
        ]);
    }

    /* USER MANAGEMENT */

    public function listUsers()
    {
        $users = $this->userModel->getAllUsers();
        $this->jsonResponse(true, 'Users fetched', $users);
    }

    public function updateUserStatus($data)
    {
        if (empty($data['user_id']) || empty($data['status'])) 
        {
            $this->jsonResponse(false, 'Invalid input');
        }

        $updated = $this->userModel
            ->updateAccountStatus($data['user_id'], $data['status']);

        if ($updated) 
        {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Updated user status',
                'user',
                $data['user_id']
            );

            $this->jsonResponse(true, 'User status updated');
        }

        $this->jsonResponse(false, 'Failed to update user status');
    }

    /* REQUEST OVERSIGHT */

    public function listRequests()
    {
        $requests = $this->requestModel->getAllActiveRequests();
        $this->jsonResponse(true, 'Requests fetched', $requests);
    }

    public function overrideRequestStatus($data)
    {
        if (empty($data['request_id']) || empty($data['status'])) 
        {
            $this->jsonResponse(false, 'Invalid input');
        }

        $updated = $this->requestModel
            ->updateRequestStatus($data['request_id'], $data['status']);

        if ($updated) 
        {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Overrode request status',
                'request',
                $data['request_id']
            );

            $this->jsonResponse(true, 'Request status overridden');
        }

        $this->jsonResponse(false, 'Failed to override request status');
    }

    /* DONATIONS & PAYMENTS */
    public function listDonations()
{
    $donations = $this->donationModel->getAllDonations();

    $this->jsonResponse(true, 'Donations fetched', $donations);
}


    public function donationReport()
    {
        $byCurrency = $this->donationModel->getDonationSummaryByCurrency();
        $byRegion   = $this->donationModel->getDonationSummaryByRegion();

        $this->jsonResponse(true, 'Donation report generated', [
            'by_currency' => $byCurrency,
            'by_region'   => $byRegion
        ]);
    }

    public function transactions()
    {
        $transactions = $this->paymentModel->getAllTransactions();
        $this->jsonResponse(true, 'Transactions fetched', $transactions);
    }

    /*  CURRENCY MANAGEMENT */

    public function currencies()
    {
        $currencies = $this->currencyModel->getAllCurrencies();
        $this->jsonResponse(true, 'Currencies fetched', $currencies);
    }

    public function addCurrency($data)
    {
        if (
            empty($data['currency_code']) ||
            empty($data['currency_name']) ||
            empty($data['symbol'])
        ) 
        {
            $this->jsonResponse(false, 'Invalid currency data');
        }

        $added = $this->currencyModel
            ->addCurrency($data['currency_code'], $data['currency_name'], $data['symbol']);

        if ($added) 
        {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Added currency',
                'currency',
                0
            );

            $this->jsonResponse(true, 'Currency added');
        }

        $this->jsonResponse(false, 'Failed to add currency');
    }

    public function removeCurrency($data)
    {
        if (empty($data['currency_code'])) 
        {
            $this->jsonResponse(false, 'Currency code required');
        }

        $removed = $this->currencyModel
            ->removeCurrency($data['currency_code']);

        if ($removed) 
        {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Removed currency',
                'currency',
                0
            );

            $this->jsonResponse(true, 'Currency removed');
        }

        $this->jsonResponse(false, 'Failed to remove currency');
    }

    /* SYSTEM NOTIFICATIONS */

    public function broadcast($data)
    {
        if (empty($data['message'])) 
        {
            $this->jsonResponse(false, 'Message required');
        }

        $users = $this->userModel->getAllUsers();

        foreach ($users as $user) 
        {
            $this->notificationModel->createNotification(
                $user['user_id'],
                $data['message'],
                'broadcast'
            );
        }

        $this->logModel->logActivity(
            $_SESSION['user_id'],
            'Broadcasted system message',
            'system',
            0
        );

        $this->jsonResponse(true, 'Broadcast sent');
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