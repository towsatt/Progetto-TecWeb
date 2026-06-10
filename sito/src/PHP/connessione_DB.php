<?php
include_once BASE_PATH . "/src/PHP/Handlers/ErrorHandler.php";
include_once BASE_PATH . "/src/PHP/Controllers/InputController.php";

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

    private function __construct() {}
}
