<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Handler' . DIRECTORY_SEPARATOR . 'ErrorHandler.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'connessione_DB.php';

class InputValidator
{
    public static function validateEmail($email): null
    {
        // connessione al database -> se fallisce throw DatabaseError
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InputError("Invalid email format");
        }
    }

    public static function validatePassword($password): null
    {
        // Logica della password da imparare
        if (strlen($password) < 8) {
            throw new InputError("Password must be at least 8 characters long");
        }
    }

    public static function validateUsername($username): null
    {
        // Sistemare la whitelist dei caratteri
        if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $username)) {
            throw new InputError("Invalid username format");
        }
    }
}
?>