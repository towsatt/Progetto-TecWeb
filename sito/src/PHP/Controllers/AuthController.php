<?php

require_once __DIR__ . '/../Handlers/ErrorHandler.php';
require_once __DIR__ . '/../Validators/InputValidator.php';

class AuthController {
    public static function login($username, $password) {
        try {
            $username = InputController::validateUsername($username);
            $password = InputController::validatePassword($password);
            // Logica di autenticazione
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    public static function register($email, $password, $username) {
        try {
            $email = InputController::validateEmail($email);
            $password = InputController::validatePassword($password);
            $username = InputController::validateUsername($username);
            // Logica di registrazione
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
}