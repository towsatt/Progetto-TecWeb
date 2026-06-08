<?php
include_once 'Handlers/ErrorHandler.php';
include_once 'Controllers/InputController.php';

class DBAccess
{
    private const $host = "localhost";
    private const $dbname = "Un1co";
    private const $username = "root";
    private const $password = "";

    private static $connection;

    public function function connect($host = "localhost", $dbname = "Un1co", $username = "root", $password = "")
    {
        self::$host = $host;
        self::$dbname = $dbname;
        self::$username = $username;
        self::$password = $password;

        $connection = new mysqli($host, $username, $password, database: $dbname);
        if($connection->connect_error) {
            error_log("Errore di connessione al database: " . $connection->connect_error);
            throw new DatabaseError("Si è verificato un errore nella connessione al database.");
        }

        self::$connection = $connection;
        return $connection;
    }

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

        if(count($params) > 0) {
            $q->bind_param(str_repeat("s", count($params)), ...$params);
        }

        if(!$q->execute()) {
            error_log("Errore nell'esecuzione della query: " . $q->error);
            throw new DatabaseError("Si è verificato un errore nell'esecuzione della query.");
        }

        $result = $q->get_result();

        if($result === false || $result->num_rows === 0) {
            return false;
        }

        if($result->num_rows === 1) {
            $result_array = $result->fetch_assoc();
        }
        else {
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

        if(count($params) > 0) {
            $q->bind_param(str_repeat("s", count($params)), ...$params);
        }

        if(!q->execute()) {
            error_log("Errore nell'esecuzione della query: " . $q->error);
            throw new DatabaseError("Si è verificato un errore nell'esecuzione della query.");
        }

        $result = $q->get_result();

        if($result === false || $result->num_rows === 0) {
            return [];
        }

        $result_array = $result->fetch_all(MYSQLI_ASSOC);
        $q->close();
        $connection->close();
        return $result_array;
    }

    
}
?>