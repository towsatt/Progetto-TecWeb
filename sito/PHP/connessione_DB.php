<?php
include_once __DIR__ . DIRECTORY_SEPARATOR . 'Handler' . DIRECTORY_SEPARATOR . 'ErrorHandler.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'Validators' . DIRECTORY_SEPARATOR . 'InputValidator.php';

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

    public function researchUser($username): array|null
    {
        InputValidator::validateUsername($username);

        $pattern = $username . '%';
        $stmt = mysqli_prepare($this->connection, "SELECT * FROM Utente WHERE username LIKE ?;");
        if (!$stmt) {
            throw new DatabaseError("Errore nella preparazione della query");
        }

        mysqli_stmt_bind_param($stmt, 's', $pattern);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            mysqli_stmt_close($stmt);
            throw new DatabaseError("Errore nell'esecuzione della query");
        }

        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        return count($rows) === 0 ? null : $rows;
    }
}
?>