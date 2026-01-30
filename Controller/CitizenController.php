
<?php

require_once __DIR__ . '/../Model/EmergencyRequestModel.php';
require_once __DIR__ . '/../Model/AssignmentModel.php';
require_once __DIR__ . '/../Model/NotificationModel.php';
require_once __DIR__ . '/../Model/DonationModel.php';
require_once __DIR__ . '/../Model/ActivityLogModel.php';
require_once __DIR__ . '/../Model/UserModel.php';
require_once __DIR__ . '/../Model/Database.php';

class CitizenController
{
    private $requestModel;
    private $assignmentModel;
    private $notificationModel;
    private $donationModel;
    private $logModel;
    private $userModel;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
            $this->jsonResponse(false, 'Unauthorized access');
        }

        $this->requestModel      = new EmergencyRequestModel();
        $this->assignmentModel   = new AssignmentModel();
        $this->notificationModel = new NotificationModel();
        $this->donationModel     = new DonationModel();
        $this->logModel          = new ActivityLogModel();
        $this->userModel         = new UserModel();
    }

    /* =====================
       PROFILE (FIXED)
    ===================== */

    public function profile()
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
            $this->jsonResponse(false, 'Name is required');
        }

        $stmt = Database::connect()->prepare(
            "UPDATE users 
             SET full_name = :full_name, phone = :phone 
             WHERE user_id = :user_id"
        );

        $updated = $stmt->execute([
            ':full_name' => $data['full_name'],
            ':phone'     => $data['phone'] ?? null,
            ':user_id'   => $_SESSION['user_id']
        ]);

        if ($updated) {
            $this->jsonResponse(true, 'Profile updated successfully');
        }

        $this->jsonResponse(false, 'Update failed');
    }

    /* =====================
       JSON RESPONSE
    ===================== */

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
