<?php

include_once '../Handlers/ErrorHandler.php';
include_once '../Validators/InputValidator.php';

class InputController {
    private static function sanitize(string $data): string
    {
        return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
    }

/*  ===================================================================
                        VALIDAZIONE INPUT (Check Input)
    =================================================================== */

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


    public static function validateEmail($email) {
        try{
            $email = self::sanitize($email);
            InputValidator::validateEmail($email);
            return $email;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    public static function validatePassword($password) {
        try {
            $password = self::sanitize($password);
            InputValidator::validatePassword($password);
            return $password;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    public static function validateUsername($username) { // Rinominiamo in validateName per usarlo in più contesti? Tipo username, nome personaggio, ecc.
        try {
            $username = self::sanitize($username);
            InputValidator::validateUsername($username);
            return $username;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    public static function validateDescription($description) {
        try {
            $description = self::sanitize($description);
            InputValidator::validateDescription($description);
            return $description;
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
}

}

