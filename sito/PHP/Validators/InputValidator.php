<?php

include_once '../Handlers/ErrorHandler.php';
include_once '../connessione_DB.php';

class InputValidator
{

    public static function validateEmail($email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InputError("Il formato dell'email non è valido.");
        }
    }

    public static function validatePassword(string $password): void
    {
        $len = strlen($password);
        if ($len < 12 || $len > 24) {
            throw new InputError("La password deve contenere tra 12 e 24 caratteri.");
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new InputError("La password deve contenere almeno una lettera maiuscola.");
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new InputError("La password deve contenere almeno un numero.");
        }

        // Modo più robusto: cerca qualsiasi carattere che NON sia alfanumerico
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            throw new InputError("La password deve contenere almeno un carattere speciale.");
        }
    }

    public static function validateUsername(string $username): void
    {
        if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            throw new InputError("L'username può contenere solo lettere, numeri e underscore, e deve essere lungo tra i 3 e i 20 caratteri.");
        }
    }

    public static function validateDescription(string $description): void
    {
        if (strlen($description) > 500) {
            throw new InputError("La descrizione non può superare i 500 caratteri.");
        }
    }

    
}