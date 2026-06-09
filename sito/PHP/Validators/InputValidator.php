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

    private static function isMail($mail): bool|string
    {
        if (strlen($mail) > 256) {
            return "<li>La <span lang=\"en\">mail</span> può essere lunga al massimo 256 caratteri</li>";
        }
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            return "<li><span lang=\"en\">Mail</span> non valida</li>";
        }
        return true;
    }

    private static function isUsername($username): bool|string
    {
        $username_pattern = '/^[\w' . ACCENTED_CHARACTERS . '\'\-]{1,40}$/';
        if (!preg_match($username_pattern, $username)) {
            return "<li>Lo <span lang=\"en\">Username</span> può contenere solo lettere, numeri, apostrofi, trattini e <span lang=\"en\">underscore</span>, non può contenere spazi e deve essere lungo al massimo 40 caratteri</li>";
        }
        return true;
    }

    private static function isPassword($pass): bool|string
    {
        $password_pattern = '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\s])[\S]{8,256}$/';
        if (!preg_match($password_pattern, $pass)) {
            return "<li>La <span lang=\"en\">password</span> deve essere lunga almeno 8 caratteri e massimo 256, deve contenere almeno un carattere maiuscolo, un carattere minuscolo, un numero e un carattere speciale</li>";
        }
        return true;
    }

    private static function isDescription($description): bool|string
    {
        if (strlen($description) > 500) {
            return "<li>La descrizione può essere lunga al massimo 500 caratteri</li>";
        }
        return true;
    }
    
}