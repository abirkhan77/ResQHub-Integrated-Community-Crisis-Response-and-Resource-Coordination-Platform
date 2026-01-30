<?php

require_once __DIR__ . '/Database.php';

class UserModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function createUser($fullName, $email, $passwordHash, $role, $phone = null)
    {
        $sql = "INSERT INTO users 
                (full_name, email, password_hash, role, phone) 
                VALUES 
                (:full_name, :email, :password_hash, :role, :phone)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':full_name'     => $fullName,
            ':email'         => $email,
            ':password_hash' => $passwordHash,
            ':role'          => $role,
            ':phone'         => $phone
        ]);
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getUserById($userId)
    {
        $sql = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch();
    }

    public function updateProfile($userId, $fullName, $phone = null)
    {
        $sql = "UPDATE users 
                SET full_name = :full_name,
                    phone = :phone
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':full_name' => $fullName,
            ':phone'     => $phone,
            ':user_id'   => $userId
        ]);
    }

    public function updateLastLogin($userId)
    {
        $sql = "UPDATE users 
                SET last_login = CURRENT_TIMESTAMP 
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':user_id' => $userId]);
    }

    public function updateAccountStatus($userId, $status)
    {
        $sql = "UPDATE users 
                SET account_status = :status 
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'  => $status,
            ':user_id' => $userId
        ]);
    }

    public function getAllUsers()
    {
        $sql = "SELECT user_id, full_name, email, role, account_status, created_at 
                FROM users
                ORDER BY created_at DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getUsersByRole($role)
    {
        $sql = "SELECT user_id, full_name, email, phone, account_status 
                FROM users 
                WHERE role = :role";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll();
    }
}