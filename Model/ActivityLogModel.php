
<?php

require_once __DIR__ . '/Database.php';

class ActivityLogModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function logActivity($userId, $action, $referenceType, $referenceId)
    {
        $sql = "INSERT INTO activity_log
                (user_id, action, reference_type, reference_id)
                VALUES
                (:user_id, :action, :reference_type, :reference_id)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':user_id'        => $userId,
            ':action'         => $action,
            ':reference_type' => $referenceType,
            ':reference_id'   => $referenceId
        ]);
    }

    public function getAllLogs()
    {
        $sql = "SELECT 
                    al.log_id,
                    al.action,
                    al.reference_type,
                    al.reference_id,
                    al.timestamp,
                    u.full_name AS user_name,
                    u.role
                FROM activity_log al
                JOIN users u ON al.user_id = u.user_id
                ORDER BY al.timestamp DESC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function getLogsByUser($userId)
    {
        $sql = "SELECT log_id, action, reference_type, reference_id, timestamp
                FROM activity_log
                WHERE user_id = :user_id
                ORDER BY timestamp DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getLogsByReference($referenceType, $referenceId)
    {
        $sql = "SELECT log_id, user_id, action, timestamp
                FROM activity_log
                WHERE reference_type = :reference_type
                  AND reference_id = :reference_id
                ORDER BY timestamp DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':reference_type' => $referenceType,
            ':reference_id'   => $referenceId
        ]);

        return $stmt->fetchAll();
    }

    public function deleteLogsByReference($referenceType, $referenceId)
    {
        $sql = "DELETE FROM activity_log
                WHERE reference_type = :reference_type
                  AND reference_id = :reference_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':reference_type' => $referenceType,
            ':reference_id'   => $referenceId
        ]);
    }
}
