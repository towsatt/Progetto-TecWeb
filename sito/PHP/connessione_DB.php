<?php
use mysqli;
use mysqli_result;
use throwable;
use exception;
include_once __DIR__ . DIRECTORY_SEPARATOR . 'Handler' . DIRECTORY_SEPARATOR . 'ErrorHandler.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . 'InputController.php';

class DBAccess
{
    //XAMPP localhost mydb root ""
    //Docker db mydb root root
    private const HOST_DB = "localhost";
    private const DATABASE_NAME = "mydb";
    private const USERNAME = "root";
    private const PASSWORD = "";

    private $connection;

    public function openDBConnection()
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
            throw new DatabaseError("Errore di connessoine al database");
        }
    }

    public function closeConnection()
    {
        mysqli_close($this->connection);
    }

    public function researchUser($username): array
    {
        // InputController::validateUsername($username);
        // $query = "SELECT * FROM Utente WHERE username LIKE " . $username . "%;";
        // $stmt = mysqli_prepare($this->connection, $query);
        // mysqli_stmt_bind_param($stmt, "s", $username);
        // mysqli_stmt_execute($stmt);
        // return mysqli_stmt_get_result($stmt);
    }
}
?>