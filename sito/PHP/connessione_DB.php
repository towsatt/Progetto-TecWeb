<?php
use mysqli;
use mysqli_result;
use throwable;
use exception;

class DBAccess
{
    //XAMPP localhost mydb root ""
    //Docker db mydb root root
    private const HOST_DB = "localhost";
    private const DATABASE_NAME = "mydb";
    private const USERNAME = "root";
    private const PASSWORD = "";

    private $connection;

    public function openDBConnection() {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->connection = mysqli_connect(
                self::HOST_DB,
                self::USERNAME,
                self::PASSWORD,
                self::DATABASE_NAME
            );
            return true;

        } catch (\mysqli_sql_exception $e) {
            http_response_code(500);
            include __DIR__ . "../500.php";
            exit();
        }
    }

    public function closeConnection()
    {
        mysqli_close($this->connection);
    }

}
?>