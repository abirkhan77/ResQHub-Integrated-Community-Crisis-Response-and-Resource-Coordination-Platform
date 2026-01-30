<?php

require_once __DIR__ . '/Database.php';

class VolunteerModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function createVolunteerProfile($userId, $skillType)
    {
        $sql = "INSERT INTO volunteer_profile 
                (user_id, skill_type) 
                VALUES 
                (:user_id, :skill_type)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':user_id'    => $userId,
            ':skill_type' => $skillType
        ]);
    }

    public function getVolunteerProfileByUserId($userId)
    {
        $sql = "SELECT vp.*, u.full_name, u.email, u.phone
                FROM volunteer_profile vp
                JOIN users u ON vp.user_id = u.user_id
                WHERE vp.user_id = :user_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetch();
    }

    public function getAllVolunteers()
    {
        $sql = "SELECT 
                    u.user_id,
                    u.full_name,
                    u.email,
                    u.phone,
                    vp.skill_type,
                    vp.availability_status,
                    vp.total_help_completed,
                    vp.verified_status
                FROM users u
                JOIN volunteer_profile vp ON u.user_id = vp.user_id
                WHERE u.role = 'volunteer'
                ORDER BY vp.total_help_completed DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function getAvailableVolunteersBySkill($skillType)
    {
        $sql = "SELECT 
                    u.user_id,
                    u.full_name,
                    u.phone,
                    vp.skill_type
                FROM users u
                JOIN volunteer_profile vp ON u.user_id = vp.user_id
                WHERE u.role = 'volunteer'
                  AND vp.skill_type = :skill_type
                  AND vp.availability_status = 'available'
                  AND u.account_status = 'active'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':skill_type' => $skillType]);

        return $stmt->fetchAll();
    }

    public function updateAvailabilityStatus($userId, $status)
    {
        $sql = "UPDATE volunteer_profile
                SET availability_status = :status
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'  => $status,
            ':user_id' => $userId
        ]);
    }

    public function incrementHelpCount($userId)
    {
        $sql = "UPDATE volunteer_profile
                SET total_help_completed = total_help_completed + 1
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':user_id' => $userId]);
    }

    public function verifyVolunteer($userId)
    {
        $sql = "UPDATE volunteer_profile
                SET verified_status = TRUE
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':user_id' => $userId]);
    }

    public function countAssignedRequests($volunteerId)
{
    $sql = "SELECT COUNT(*) FROM emergency_requests 
            WHERE assigned_volunteer_id = :vid 
            AND status = 'assigned'";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':vid' => $volunteerId]);

    return (int) $stmt->fetchColumn();
}

public function countCompletedTasks($volunteerId)
{
    $sql = "SELECT COUNT(*) FROM emergency_requests 
            WHERE assigned_volunteer_id = :vid 
            AND status = 'completed'";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':vid' => $volunteerId]);

    return (int) $stmt->fetchColumn();
}

public function getVolunteerStatus($volunteerId)
{
    $sql = "SELECT availability_status 
            FROM volunteers 
            WHERE user_id = :vid 
            LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':vid' => $volunteerId]);

    $status = $stmt->fetchColumn();
    return $status ?: 'Available';
}

}<?php

require_once __DIR__ . '/Database.php';

class VolunteerModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function createVolunteerProfile($userId, $skillType)
    {
        $sql = "INSERT INTO volunteer_profile 
                (user_id, skill_type) 
                VALUES 
                (:user_id, :skill_type)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':user_id'    => $userId,
            ':skill_type' => $skillType
        ]);
    }

    public function getVolunteerProfileByUserId($userId)
    {
        $sql = "SELECT vp.*, u.full_name, u.email, u.phone
                FROM volunteer_profile vp
                JOIN users u ON vp.user_id = u.user_id
                WHERE vp.user_id = :user_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetch();
    }

    public function getAllVolunteers()
    {
        $sql = "SELECT 
                    u.user_id,
                    u.full_name,
                    u.email,
                    u.phone,
                    vp.skill_type,
                    vp.availability_status,
                    vp.total_help_completed,
                    vp.verified_status
                FROM users u
                JOIN volunteer_profile vp ON u.user_id = vp.user_id
                WHERE u.role = 'volunteer'
                ORDER BY vp.total_help_completed DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function getAvailableVolunteersBySkill($skillType)
    {
        $sql = "SELECT 
                    u.user_id,
                    u.full_name,
                    u.phone,
                    vp.skill_type
                FROM users u
                JOIN volunteer_profile vp ON u.user_id = vp.user_id
                WHERE u.role = 'volunteer'
                  AND vp.skill_type = :skill_type
                  AND vp.availability_status = 'available'
                  AND u.account_status = 'active'";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':skill_type' => $skillType]);

        return $stmt->fetchAll();
    }

    public function updateAvailabilityStatus($userId, $status)
    {
        $sql = "UPDATE volunteer_profile
                SET availability_status = :status
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'  => $status,
            ':user_id' => $userId
        ]);
    }

    public function incrementHelpCount($userId)
    {
        $sql = "UPDATE volunteer_profile
                SET total_help_completed = total_help_completed + 1
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':user_id' => $userId]);
    }

    public function verifyVolunteer($userId)
    {
        $sql = "UPDATE volunteer_profile
                SET verified_status = TRUE
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':user_id' => $userId]);
    }

    public function countAssignedRequests($volunteerId)
{
    $sql = "SELECT COUNT(*) FROM emergency_requests 
            WHERE assigned_volunteer_id = :vid 
            AND status = 'assigned'";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':vid' => $volunteerId]);

    return (int) $stmt->fetchColumn();
}

public function countCompletedTasks($volunteerId)
{
    $sql = "SELECT COUNT(*) FROM emergency_requests 
            WHERE assigned_volunteer_id = :vid 
            AND status = 'completed'";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':vid' => $volunteerId]);

    return (int) $stmt->fetchColumn();
}

public function getVolunteerStatus($volunteerId)
{
    $sql = "SELECT availability_status 
            FROM volunteers 
            WHERE user_id = :vid 
            LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':vid' => $volunteerId]);

    $status = $stmt->fetchColumn();
    return $status ?: 'Available';
}

}