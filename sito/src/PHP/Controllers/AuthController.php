<?php

require_once __DIR__ . '/../Handlers/ErrorHandler.php';
require_once __DIR__ . '/../Validators/InputValidator.php';
require_once __DIR__ . '/../Queries/Queries.php';

class AuthController
{
    public static function login($username, $password)
    {
        try {
            $username = InputController::validateUsername($username);
            $password = InputController::validatePassword($password);
            if (!empty($_POST)) {
                if (isset($_POST['username']) && isset($_POST['password'])) 
                    return login($_POST['username'], $_POST['password']);
            }
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }

    public static function register($email, $password, $username)
    {
        try {
            $email = InputController::validateEmail($email);
            $password = InputController::validatePassword($password);
            $username = InputController::validateUsername($username);
            if (!empty($_POST)) {
                if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
                    if (registerUser($_POST['username'], $_POST['email'], $_POST['password'])) {
                        header("Location: /login");
                        exit();
                    } else {
                        throw new InvalidParameterError("Registrazione fallita. Riprovare!");
                    }
                }
            }
        } catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
}