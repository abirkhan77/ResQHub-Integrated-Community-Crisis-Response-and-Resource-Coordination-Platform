
<?php

require_once __DIR__ . '/../Model/NotificationModel.php';

class NotificationController
{
    private $notificationModel;

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['user_id'])) 
        {
            $this->jsonResponse(false, 'Unauthorized');
        }

        $this->notificationModel = new NotificationModel();
    }

    /* READ */

    public function listMyNotifications()
    {
        $notifications = $this->notificationModel
            ->getNotificationsByUser($_SESSION['user_id']);

        $this->jsonResponse(true, 'Notifications fetched', $notifications);
    }

    public function unreadCount()
    {
        $count = $this->notificationModel
            ->getUnreadNotificationsCount($_SESSION['user_id']);

        $this->jsonResponse(true, 'Unread count fetched', [
            'unread_count' => $count
        ]);
    }

    /* UPDATE */

    public function markAsRead($data)
    {
        if (empty($data['notification_id'])) 
        {
            $this->jsonResponse(false, 'Notification ID required');
        }

        $updated = $this->notificationModel
            ->markNotificationAsRead($data['notification_id']);

        if ($updated) 
        {
            $this->jsonResponse(true, 'Notification marked as read');
        }

        $this->jsonResponse(false, 'Failed to update notification');
    }

    public function markAllAsRead()
    {
        $updated = $this->notificationModel
            ->markAllAsRead($_SESSION['user_id']);

        if ($updated) 
        {
            $this->jsonResponse(true, 'All notifications marked as read');
        }

        $this->jsonResponse(false, 'Failed to update notifications');
    }

    /* DELETE */

    public function delete($data)
    {
        if (empty($data['notification_id'])) 
        {
            $this->jsonResponse(false, 'Notification ID required');
        }

        $deleted = $this->notificationModel
            ->deleteNotification($data['notification_id']);

        if ($deleted) 
        {
            $this->jsonResponse(true, 'Notification deleted');
        }

        $this->jsonResponse(false, 'Failed to delete notification');
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
