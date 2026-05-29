<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Handler' . DIRECTORY_SEPARATOR . 'ErrorHandler.php';
include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Validator' . DIRECTORY_SEPARATOR . 'InputValidator.php';

class InputController {
    public static function validateEmail($email) {
        try{
            InputValidator::validateEmail($email);
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
            InputValidator::validatePassword($password);
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
    public static function validateUsername($username) {
        try {
            InputValidator::validateUsername($username);
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
}
?>