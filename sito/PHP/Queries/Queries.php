<?php

require_once __DIR__ . '/../Handlers/ErrorHandler.php';
require_once __DIR__ . '/../Controllers/InputController.php';

class Queries
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function searchByUsername(string $inputUsername): ?array
    {
        $username = InputController::validateUsername($inputUsername) . '%';
        $query = "SELECT * FROM Utente WHERE username LIKE ?";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                return null;
            }

            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }

            return $users;
        } catch (mysqli_sql_exception $e) {
            error_log("Errore DB durante la ricerca utente: " . $e->getMessage());
            throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
        }
    }
}
