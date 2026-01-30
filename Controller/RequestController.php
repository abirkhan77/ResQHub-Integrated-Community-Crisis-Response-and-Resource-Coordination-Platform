
<?php

require_once __DIR__ . '/../Model/EmergencyRequestModel.php';
require_once __DIR__ . '/../Model/AssignmentModel.php';
require_once __DIR__ . '/../Model/NotificationModel.php';
require_once __DIR__ . '/../Model/ActivityLogModel.php';

class RequestController
{
    private $requestModel;
    private $assignmentModel;
    private $notificationModel;
    private $logModel;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(false, 'Unauthorized');
        }

        $this->requestModel      = new EmergencyRequestModel();
        $this->assignmentModel   = new AssignmentModel();
        $this->notificationModel = new NotificationModel();
        $this->logModel          = new ActivityLogModel();
    }

    /* =========================
       CREATE (Citizen)
       ========================= */

    public function create($data)
    {
        if ($_SESSION['role'] !== 'citizen') {
            $this->jsonResponse(false, 'Access denied');
        }

        if (
            empty($data['type']) ||
            empty($data['urgency']) ||
            empty($data['location']) ||
            empty($data['description'])
        ) {
            $this->jsonResponse(false, 'All fields are required');
        }

        // ✅ FIXED ORDER (matches EmergencyRequestModel)
        $created = $this->requestModel->createRequest(
            $_SESSION['user_id'],
            $data['type'],
            $data['urgency'],
            $data['location'],
            $data['description']
        );

        if ($created) {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Created emergency request',
                'request',
                0
            );

            $this->jsonResponse(true, 'Request created successfully');
        }

        $this->jsonResponse(false, 'Failed to create request');
    }

    /* =========================
       READ
       ========================= */

    public function view($data)
    {
        if (empty($data['request_id'])) {
            $this->jsonResponse(false, 'Request ID required');
        }

        $request = $this->requestModel->getRequestById($data['request_id']);

        if (!$request) {
            $this->jsonResponse(false, 'Request not found');
        }

        $this->jsonResponse(true, 'Request fetched', $request);
    }

    public function listMyRequests()
    {
        if ($_SESSION['role'] !== 'citizen') {
            $this->jsonResponse(false, 'Access denied');
        }

        $requests = $this->requestModel
            ->getRequestsByCitizen($_SESSION['user_id']);

        $this->jsonResponse(true, 'Requests fetched', $requests);
    }

    public function listActive()
    {
        if (!in_array($_SESSION['role'], ['volunteer', 'admin'])) {
            $this->jsonResponse(false, 'Access denied');
        }

        $requests = $this->requestModel->getAllActiveRequests();
        $this->jsonResponse(true, 'Active requests fetched', $requests);
    }

    /* =========================
       UPDATE
       ========================= */

    public function update($data)
    {
        if (empty($data['request_id'])) {
            $this->jsonResponse(false, 'Request ID required');
        }

        if ($_SESSION['role'] === 'citizen') {
            if (
                empty($data['description']) ||
                empty($data['location']) ||
                empty($data['urgency'])
            ) {
                $this->jsonResponse(false, 'Invalid input');
            }

            $updated = $this->requestModel->updateRequestDetails(
                $data['request_id'],
                $data['description'],
                $data['location'],
                $data['urgency']
            );

            if ($updated) {
                $this->jsonResponse(true, 'Request updated');
            }

            $this->jsonResponse(false, 'Request cannot be updated');
        }

        if ($_SESSION['role'] === 'admin') {
            if (empty($data['status'])) {
                $this->jsonResponse(false, 'Status required');
            }

            $updated = $this->requestModel
                ->updateRequestStatus($data['request_id'], $data['status']);

            if ($updated) {
                $this->logModel->logActivity(
                    $_SESSION['user_id'],
                    'Admin updated request status',
                    'request',
                    $data['request_id']
                );

                $this->jsonResponse(true, 'Request status updated');
            }

            $this->jsonResponse(false, 'Failed to update status');
        }

        $this->jsonResponse(false, 'Access denied');
    }

    /* =========================
       CANCEL
       ========================= */

    public function cancel($data)
    {
        if ($_SESSION['role'] !== 'citizen') {
            $this->jsonResponse(false, 'Access denied');
        }

        if (empty($data['request_id'])) {
            $this->jsonResponse(false, 'Request ID required');
        }

        $cancelled = $this->requestModel
            ->cancelRequest($data['request_id']);

        if ($cancelled) {
            $this->jsonResponse(true, 'Request cancelled');
        }

        $this->jsonResponse(false, 'Unable to cancel request');
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
