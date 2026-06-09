<?php

include_once __DIR__ . '/../Handlers/ErrorHandler.php';
include_once __DIR__ . '/../Validators/InputValidator.php';

class InputController {
    private static function sanitize(string $data): string
    {
        return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
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

