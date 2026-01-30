<?php

require_once __DIR__ . '/Database.php';

class AssignmentModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function assignVolunteerToRequest($requestId, $volunteerId)
    {
        $sql = "INSERT INTO request_assignment
                (request_id, volunteer_id)
                VALUES
                (:request_id, :volunteer_id)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':request_id'   => $requestId,
            ':volunteer_id' => $volunteerId
        ]);
    }

    public function getAssignmentByRequestId($requestId)
    {
        $sql = "SELECT ra.*, u.full_name AS volunteer_name, u.phone
                FROM request_assignment ra
                JOIN users u ON ra.volunteer_id = u.user_id
                WHERE ra.request_id = :request_id
                ORDER BY ra.assigned_at DESC
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':request_id' => $requestId]);

        return $stmt->fetch();
    }

    public function getAssignmentsByVolunteer($volunteerId)
    {
        $sql = "SELECT ra.*, er.request_type, er.location_text, er.urgency_level
                FROM request_assignment ra
                JOIN emergency_request er ON ra.request_id = er.request_id
                WHERE ra.volunteer_id = :volunteer_id
                ORDER BY ra.assigned_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':volunteer_id' => $volunteerId]);

        return $stmt->fetchAll();
    }

    public function updateAssignmentStatus($assignmentId, $status)
    {
        $sql = "UPDATE request_assignment
                SET current_status = :status
                WHERE assignment_id = :assignment_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'        => $status,
            ':assignment_id' => $assignmentId
        ]);
    }

    public function hasActiveAssignment($volunteerId)
    {
        $sql = "SELECT COUNT(*) AS active_count
                FROM request_assignment
                WHERE volunteer_id = :volunteer_id
                  AND current_status IN ('accepted', 'on_the_way')";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':volunteer_id' => $volunteerId]);

        $result = $stmt->fetch();

        return $result['active_count'] > 0;
    }
}