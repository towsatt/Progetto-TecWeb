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

    public static function findMatchEmail(string $email): void
    {
        global $conn;
        $stmt = $conn->prepare("SELECT email FROM userdata WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        if ($exists) {
            throw new InputError("L'email inserita è già in uso.");
        }
    }

    public static function findMatchUsername(string $username): void
    {
        global $conn;
        $stmt = $conn->prepare("SELECT username FROM userdata WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        if ($exists) {
            throw new InputError("L'username inserito è già in uso.");
        }
    }

    

    public static function findUser(string $username, string $password): void
    {
        global $conn;
        $stmt = $conn->prepare("SELECT username FROM userdata WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        if ($exists==1) {
            $stmt->close();
            $stmt=$conn->prepare("SELECT password FROM userdata WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $exists=$stmt->num_rows > 0;
            if($exists==1){
                //avviene connessione 
            } else{
                throw new InputError("Username o password errati.");
            }

        }
    }
}