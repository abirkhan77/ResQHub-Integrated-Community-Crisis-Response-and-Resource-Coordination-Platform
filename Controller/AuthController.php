
<?php

require_once __DIR__ . '/../Model/UserModel.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    public function register($data)
    {
        if (
            empty($data['full_name']) ||
            empty($data['email']) ||
            empty($data['password']) ||
            empty($data['role'])
        ) {
            $this->jsonResponse(false, 'All fields are required');
        }

        $allowedRoles = ['citizen', 'volunteer', 'admin'];
        if (!in_array($data['role'], $allowedRoles)) {
            $this->jsonResponse(false, 'Invalid role');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(false, 'Invalid email');
        }

        if (strlen($data['password']) < 8) {
            $this->jsonResponse(false, 'Password must be at least 8 characters');
        }

        if ($this->userModel->getUserByEmail($data['email'])) {
            $this->jsonResponse(false, 'Email already registered');
        }

        $hash = password_hash($data['password'], PASSWORD_BCRYPT);

        $created = $this->userModel->createUser(
            $data['full_name'],
            $data['email'],
            $hash,
            $data['role'],
            $data['phone'] ?? null
        );

        if (!$created) {
            $this->jsonResponse(false, 'Registration failed');
        }

        $this->jsonResponse(true, 'Registration successful');
    }

    public function login($data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            $this->jsonResponse(false, 'Email and password are required');
        }

        $user = $this->userModel->getUserByEmail($data['email']);
        if (!$user) {
            $this->jsonResponse(false, 'Invalid credentials');
        }

        if ($user['account_status'] !== 'active') {
            $this->jsonResponse(false, 'Account inactive');
        }

        if (!password_verify($data['password'], $user['password_hash'])) {
            $this->jsonResponse(false, 'Invalid credentials');
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        $this->userModel->updateLastLogin($user['user_id']);

        $this->jsonResponse(true, 'Login successful', [
            'role' => $user['role']
        ]);
    }

    public function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(false, 'Unauthorized');
        }

        $this->jsonResponse(true, 'Authorized', [
            'user_id' => $_SESSION['user_id'],
            'role' => $_SESSION['role']
        ]);
    }

    public function logout()
    {
        session_destroy();
        $this->jsonResponse(true, 'Logged out');
    }

    private function jsonResponse($success, $message, $data = null)
    {
        if (ob_get_length()) {
            ob_clean();
        }

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}

<?php

require_once __DIR__ . '/../Model/UserModel.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    public function register($data)
    {
        if (
            empty($data['full_name']) ||
            empty($data['email']) ||
            empty($data['password']) ||
            empty($data['role'])
        ) {
            $this->jsonResponse(false, 'All fields are required');
        }

        $allowedRoles = ['citizen', 'volunteer', 'admin'];
        if (!in_array($data['role'], $allowedRoles)) {
            $this->jsonResponse(false, 'Invalid role');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(false, 'Invalid email');
        }

        if (strlen($data['password']) < 8) {
            $this->jsonResponse(false, 'Password must be at least 8 characters');
        }

        if ($this->userModel->getUserByEmail($data['email'])) {
            $this->jsonResponse(false, 'Email already registered');
        }

        $hash = password_hash($data['password'], PASSWORD_BCRYPT);

        $created = $this->userModel->createUser(
            $data['full_name'],
            $data['email'],
            $hash,
            $data['role'],
            $data['phone'] ?? null
        );

        if (!$created) {
            $this->jsonResponse(false, 'Registration failed');
        }

        $this->jsonResponse(true, 'Registration successful');
    }

    public function login($data)
    {
        if (empty($data['email']) || empty($data['password'])) {
            $this->jsonResponse(false, 'Email and password are required');
        }

        $user = $this->userModel->getUserByEmail($data['email']);
        if (!$user) {
            $this->jsonResponse(false, 'Invalid credentials');
        }

        if ($user['account_status'] !== 'active') {
            $this->jsonResponse(false, 'Account inactive');
        }

        if (!password_verify($data['password'], $user['password_hash'])) {
            $this->jsonResponse(false, 'Invalid credentials');
        }

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        $this->userModel->updateLastLogin($user['user_id']);

        $this->jsonResponse(true, 'Login successful', [
            'role' => $user['role']
        ]);
    }

    public function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(false, 'Unauthorized');
        }

        $this->jsonResponse(true, 'Authorized', [
            'user_id' => $_SESSION['user_id'],
            'role' => $_SESSION['role']
        ]);
    }

    public function logout()
    {
        session_destroy();
        $this->jsonResponse(true, 'Logged out');
    }

    private function jsonResponse($success, $message, $data = null)
    {
        if (ob_get_length()) {
            ob_clean();
        }

        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}
