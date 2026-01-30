
<?php

require_once __DIR__ . '/Database.php';

class NotificationModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function createNotification($userId, $message, $type)
    {
        $sql = "INSERT INTO notification
                (user_id, message, notification_type)
                VALUES
                (:user_id, :message, :notification_type)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':user_id'           => $userId,
            ':message'           => $message,
            ':notification_type' => $type
        ]);
    }

    public function getNotificationsByUser($userId)
    {
        $sql = "SELECT notification_id, message, notification_type, is_read, created_at
                FROM notification
                WHERE user_id = :user_id
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll();
    }

    public function getUnreadNotificationsCount($userId)
    {
        $sql = "SELECT COUNT(*) AS unread_count
                FROM notification
                WHERE user_id = :user_id
                  AND is_read = FALSE";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        $result = $stmt->fetch();

        return (int) $result['unread_count'];
    }

    public function markNotificationAsRead($notificationId)
    {
        $sql = "UPDATE notification
                SET is_read = TRUE
                WHERE notification_id = :notification_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':notification_id' => $notificationId]);
    }

    public function markAllAsRead($userId)
    {
        $sql = "UPDATE notification
                SET is_read = TRUE
                WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':user_id' => $userId]);
    }

    public function deleteNotification($notificationId)
    {
        $sql = "DELETE FROM notification
                WHERE notification_id = :notification_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':notification_id' => $notificationId]);
    }
}
