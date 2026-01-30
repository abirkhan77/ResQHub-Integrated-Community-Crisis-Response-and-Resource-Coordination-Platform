<?php

require_once __DIR__ . '/Database.php';

class EmergencyRequestModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    // ✅ FIXED: parameter order aligned with controller & JS
    public function createRequest($citizenId, $type, $urgency, $location, $description)
    {
        $sql = "INSERT INTO emergency_request
                (citizen_id, request_type, urgency_level, location_text, description, request_status, created_at)
                VALUES
                (:citizen_id, :request_type, :urgency_level, :location_text, :description, 'pending', NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':citizen_id'    => $citizenId,
            ':request_type'  => $type,
            ':urgency_level' => $urgency,
            ':location_text' => $location,
            ':description'   => $description
        ]);
    }

    public function getRequestById($requestId)
    {
        $sql = "SELECT * FROM emergency_request
                WHERE request_id = :request_id
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':request_id' => $requestId]);

        return $stmt->fetch();
    }

    public function getRequestsByCitizen($citizenId)
    {
        $sql = "SELECT *
                FROM emergency_request
                WHERE citizen_id = :citizen_id
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':citizen_id' => $citizenId]);

        return $stmt->fetchAll();
    }

    public function getAllActiveRequests()
    {
        $sql = "SELECT er.*, u.full_name AS citizen_name
                FROM emergency_request er
                JOIN users u ON er.citizen_id = u.user_id
                WHERE er.request_status IN ('pending', 'assigned', 'in_progress')
                ORDER BY er.urgency_level DESC, er.created_at ASC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function updateRequestStatus($requestId, $status)
    {
        $sql = "UPDATE emergency_request
                SET request_status = :status
                WHERE request_id = :request_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':status'     => $status,
            ':request_id' => $requestId
        ]);
    }

    public function updateRequestDetails($requestId, $description, $location, $urgency)
    {
        $sql = "UPDATE emergency_request
                SET description = :description,
                    location_text = :location_text,
                    urgency_level = :urgency_level
                WHERE request_id = :request_id
                  AND request_status = 'pending'";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':description'   => $description,
            ':location_text' => $location,
            ':urgency_level' => $urgency,
            ':request_id'    => $requestId
        ]);
    }

    public function cancelRequest($requestId)
    {
        $sql = "UPDATE emergency_request
                SET request_status = 'cancelled'
                WHERE request_id = :request_id
                  AND request_status = 'pending'";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':request_id' => $requestId]);
    }
}