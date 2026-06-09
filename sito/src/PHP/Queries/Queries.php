<?php

require_once BASE_PATH . "/src/PHP/Handlers/ErrorHandler.php";
require_once BASE_PATH . "/src/PHP/Controllers/InputController.php";
require_once BASE_PATH . "/src/PHP/connessione_DB.php";

function searchByUsername(string $inputUsername = ""): ?array
{
    if (!$inputUsername) {
        return null;
    }
    $username = InputController::validateUsername($inputUsername) . '%';
    $query = "SELECT * FROM Utente WHERE username LIKE ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
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
