<?php
include_once __DIR__ . '/html/registrazione.html';
require_once __DIR__ . '/../connessione_DB.php';
include_once __DIR__ . '/PHP/Handlers/ErrorHandler.php';
include_once __DIR__ . '/PHP/Controllers/InputController.php';


if($_SERVER["REQUEST_METHOD"] === "POST") {
    global $conn;
    $username = InputController::validateUsername($_POST['username']);
    $email = InputController::validateEmail($_POST['email']);
    $password = InputController::validatePassword($_POST['password']);
    $confirmPassword = InputController::validatePassword($_POST['confirm_password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if ($password !== $confirmPassword) {
        throw new InputError("Le password non coincidono.");
    }

    //controlla se email e username sono già in uso
    InputValidator::findMatchEmail($email);
    InputValidator::findMatchUsername($username);
    
    //inserimento dati nel database
    $stmt = $conn->prepare("INSERT INTO userdata (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $stmt->execute();
    $stmt->close();

    
}
?>