<?php
include_once './Handlers/ErrorHandler.php';
include_once './Controllers/InputController.php';

class DBAccess
{
    private const HOST_DB = "db";
    private const DATABASE_NAME = "my_database";
    private const USERNAME = "local_user";
    private const PASSWORD = "password";

    private static ?mysqli $connection = null;

    public static function getInstance(): mysqli
    {
        if (self::$connection === null) {
            self::$connection = new mysqli(
                self::HOST_DB,
                self::USERNAME,
                self::PASSWORD,
                self::DATABASE_NAME
            );

            if (self::$connection->connect_error) {
                error_log("Errore di connessione: " . self::$connection->connect_error);
                throw new DatabaseError("Connessione al database fallita.");
            }
        }

        return self::$connection;
    }

    // Rendere il costruttore private
    private function __construct() {}

    public function closeConnection()
    {
        mysqli_close($this->connection);
    }

    public static function doQuery($query, ...$params): array
    {
        $connection = self::connect();
        $q = $connection->prepare($query);

        if ($q === false) {
            error_log("Errore nella preparazione della query: " . $connection->error);
            throw new DatabaseError("Si è verificato un errore nella preparazione della query.");
        }

        if (count($params) > 0) {
            $q->bind_param(str_repeat("s", count($params)), ...$params);
        }

        if (!$q->execute()) {
            error_log("Errore nell'esecuzione della query: " . $q->error);
            throw new DatabaseError("Si è verificato un errore nell'esecuzione della query.");
        }

        $result = $q->get_result();

        if ($result === false || $result->num_rows === 0) {
            return false;
        }

        if ($result->num_rows === 1) {
            $result_array = $result->fetch_assoc();
        } else {
            $result_array = $result->fetch_all(MYSQLI_ASSOC);
        }

        $q->close();
        $connection->close();

        return $result_array;
    }

    public static function getCampagne($query, ...$params): array
    {
        $connection = self::connect();
        $q = $connection->prepare($query);

        if ($q === false) {
            error_log("Errore nella preparazione della query: " . $connection->error);
            throw new DatabaseError("Si è verificato un errore nella preparazione della query.");
        }

        if (count($params) > 0) {
            $q->bind_param(str_repeat("s", count($params)), ...$params);
        }

        if (!q->execute()) {
            error_log("Errore nell'esecuzione della query: " . $q->error);
            throw new DatabaseError("Si è verificato un errore nell'esecuzione della query.");
        }

        $result = $q->get_result();

        if ($result === false || $result->num_rows === 0) {
            return [];
        }

        $result_array = $result->fetch_all(MYSQLI_ASSOC);
        $q->close();
        $connection->close();
        return $result_array;
    }

    public function researchUser($input_username): ?array
    {
        $username = InputController::validateUsername($input_username) . '%';
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
