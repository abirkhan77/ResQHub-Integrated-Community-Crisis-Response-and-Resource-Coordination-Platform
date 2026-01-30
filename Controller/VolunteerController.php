
<?php

require_once __DIR__ . '/../Model/EmergencyRequestModel.php';
require_once __DIR__ . '/../Model/AssignmentModel.php';
require_once __DIR__ . '/../Model/NotificationModel.php';
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/ActivityLogModel.php';

class VolunteerController
{
    private $requestModel;
    private $assignmentModel;
    private $notificationModel;
    private $userModel;
    private $logModel;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
            $this->jsonResponse(false, 'Unauthorized access');
        }

        $this->requestModel       = new EmergencyRequestModel();
        $this->assignmentModel    = new AssignmentModel();
        $this->notificationModel  = new NotificationModel();
        $this->userModel          = new UserModel();
        $this->logModel           = new ActivityLogModel();
    }

    /* ==========================
       DASHBOARD (UPGRADED SAFELY)
    ========================== */

    public function dashboard()
    {
        $volunteerId = $_SESSION['user_id'];

        // Existing logic (kept)
        $available = $this->requestModel->getUnassignedRequests();
        $assigned  = $this->assignmentModel->getAssignmentsByVolunteer($volunteerId);

        // NEW: completed tasks count
        $completed = array_filter($assigned, function ($a) {
            return isset($a['status']) && $a['status'] === 'completed';
        });

        // NEW: volunteer availability (safe default)
        $status = 'Available';
        if (!empty($assigned)) {
            foreach ($assigned as $a) {
                if (isset($a['status']) && $a['status'] === 'assigned') {
                    $status = 'Busy';
                    break;
                }
            }
        }

        $this->jsonResponse(true, 'Dashboard loaded', [
            // original values (unchanged)
            'available_requests' => count($available),
            'assigned_requests'  => count($assigned),

            // new values for rich UI
            'completed_tasks'    => count($completed),
            'status'             => $status
        ]);
    }

    /* ==========================
       AVAILABLE REQUESTS
    ========================== */

    public function availableRequests()
    {
        $requests = $this->requestModel->getUnassignedRequests();
        $this->jsonResponse(true, 'Available requests loaded', $requests);
    }

    /* ==========================
       ASSIGN REQUEST
    ========================== */

    public function assignRequest($data)
    {
        if (empty($data['request_id'])) {
            $this->jsonResponse(false, 'Request ID required');
        }

        $assigned = $this->assignmentModel->assignVolunteer(
            $data['request_id'],
            $_SESSION['user_id']
        );

        if ($assigned) {
            $this->logModel->logActivity(
                $_SESSION['user_id'],
                'Accepted emergency request',
                'assignment',
                $data['request_id']
            );

            $this->jsonResponse(true, 'Request assigned');
        }

        $this->jsonResponse(false, 'Assignment failed');
    }

    /* ==========================
       MY ASSIGNMENTS
    ========================== */

    public function myAssignments()
    {
        $assignments = $this->assignmentModel
            ->getAssignmentsByVolunteer($_SESSION['user_id']);

        $this->jsonResponse(true, 'My assignments loaded', $assignments);
    }

    /* ==========================
       UPDATE STATUS
    ========================== */

    public function updateStatus($data)
    {
        if (empty($data['assignment_id']) || empty($data['status'])) {
            $this->jsonResponse(false, 'Invalid input');
        }

        $updated = $this->assignmentModel->updateStatus(
            $data['assignment_id'],
            $data['status']
        );

        if ($updated) {
            $this->jsonResponse(true, 'Status updated');
        }

        $this->jsonResponse(false, 'Update failed');
    }

    /* ==========================
       PROFILE
    ========================== */

    public function getProfile()
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if (!$user) {
            $this->jsonResponse(false, 'User not found');
        }

        $this->jsonResponse(true, 'Profile loaded', [
            'full_name' => $user['full_name'],
            'email'     => $user['email'],
            'phone'     => $user['phone'],
            'role'      => $user['role']
        ]);
    }

    public function updateProfile($data)
    {
        if (empty($data['full_name'])) {
            $this->jsonResponse(false, 'Name required');
        }

        $updated = $this->userModel->updateProfile(
            $_SESSION['user_id'],
            $data['full_name'],
            $data['phone'] ?? null
        );

        if ($updated) {
            $this->jsonResponse(true, 'Profile updated');
        }

        $this->jsonResponse(false, 'Update failed');
    }

    /* ==========================
       JSON RESPONSE
    ========================== */

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
