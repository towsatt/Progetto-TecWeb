<?php

include_once BASE_PATH . "/src/PHP/Handlers/ErrorHandler.php";
include_once BASE_PATH . "/src/PHP/connessione_DB.php";

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

        $len = strlen($username);
        if ($len < 3 || $len > 30){
            throw new InputError("L'username deve contenere tra 3 e 30 caratteri.");
        }
    }
    //se teniamo caso sotto allora eliminiamo questo
    public static function validateDescription(string $description): void
    {
        if (strlen($description) > 500) {
            throw new InputError("La descrizione non può superare i 500 caratteri.");
        }
    }

    //aggiunte per personaggio
    public static function validateCharacterName(string $characterName): void
    {
        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $characterName)) {
            throw new InputError("Il nome del personaggio può contenere solo lettere e numeri.");
        }
        if (strlen($characterName) < 2 || strlen($characterName) > 30) {
            throw new InputError("Il nome del personaggio deve essere lungo tra 2 e 30 caratteri.");
        }
        
    }

    public static function validateSkillPoints(int $skillPoints): void
    {
        if ($skillPoints < 0 || $skillPoints > 20) {
            throw new InputError("I punti abilità devono essere compresi tra 0 e 20.");
        }
    }

    public static function validateNonNegativeInteger(int $value): void
    {
        if ($value < 0) {
            throw new InputError("Il valore deve essere un intero non negativo.");
        }
    }
    //controllo della lunghezza max delle descrizioni in base al caso
    public static function validateMaxLength(string $value, int $maxLength): void
    {
        if (strlen($value) > $maxLength) {
            throw new InputError("Il testo non può superare i $maxLength caratteri.");
        }
    }
    //controllo di com'è scritto il dado vita nel formato 5d8
    public static function validateHitDie(string $hitDie): void
    {
        if (!preg_match('/^\d+d\d+$/', $hitDie)) {
            throw new InputError("Il dado vita deve essere nel formato 'XdY', dove X e Y sono numeri interi.");
        }
        //controllo se il numero a dx del d è maggiore di 0 e max 100
        $parts = explode('d', $hitDie);
        $sides = intval($parts[1]);
        if ($sides <= 0 || $sides > 100) {
            throw new InputError("Il numero di lati del dado deve essere compreso tra 1 e 100.");
        }
        //controllo se il numero a sx del d è maggiore di 0
        $count = intval($parts[0]);
        if ($count <= 0 || $count > 100) {
            throw new InputError("Il numero di dadi deve essere compreso tra 1 e 100.");
        }
    }
    //controllo range interro con min e max
    public static function validateRangeInteger(int $value, int $min, int $max): void
    {
        if ($value < $min || $value > $max) {
            throw new InputError("Il valore deve essere compreso tra $min e $max.");
        }
    }
    //controllo enum classe
    public static function validateEnumClasse(string $classe): void
    {
        $validClasses = ['barbaro', 'bardo', 'chierico', 'druido', 'guerriero', 'ladro', 'mago', 'monaco', 'paladino', 'ranger', 'stregone', 'warlock'];
        if (!in_array($classe, $validClasses)) {
            throw new InputError("La classe selezionata non è valida.");
        }
    }
    //controllo enum razza
    public static function validateEnumRazza(string $razza): void
    {
        $validRaces = ['dragonide', 'elfo', 'gnomo', 'halfling', 'mezzelfo', 'mezzorco', 'tiefling', 'umano'];
        if (!in_array($razza, $validRaces)) {
            throw new InputError("La razza selezionata non è valida.");
        }
    }
}
