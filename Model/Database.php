
<?php

class Database
{
    public static function connect()
    {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=resqhub;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            return $pdo;

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Database connection failed',
                'error'   => $e->getMessage()
            ]);
            exit;
        }
    }
}
