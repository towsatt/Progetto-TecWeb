<?php
include_once 'Handlers/ErrorHandler.php';
include_once 'Controllers/InputController.php';

class DBAccess
{
    private const HOST_DB = "db";
    private const DATABASE_NAME = "my_database";
    private const USERNAME = "local_user";
    private const PASSWORD = "password";

    private $connection;

    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = mysqli_connect(
                self::HOST_DB,
                self::USERNAME,
                self::PASSWORD,
                self::DATABASE_NAME
            );
        } catch (Exception $_e) {
            throw new DatabaseError("Errore di connessione al database");
        }
    }

    public function closeConnection()
    {
        mysqli_close($this->connection);
    }

    public function researchUser($input_username): ?array
    {
        $username = InputController::validateUsername($input_username) . '%';
        $query = "SELECT * FROM Utente WHERE username LIKE ?";
        try{
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
        }catch (mysqli_sql_exception $e) {
            error_log("Errore DB durante la ricerca utente: " . $e->getMessage());
            throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
        }
    }
}
?>