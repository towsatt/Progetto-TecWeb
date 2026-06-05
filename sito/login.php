<?php
include_once __DIR__ . '/html/login.html';
require_once __DIR__ . '/../connessione_DB.php';
include_once __DIR__ . '/PHP/Handlers/ErrorHandler.php';
include_once __DIR__ . '/PHP/Controllers/InputController.php';


if($_SERVER["REQUEST_METHOD"] === "POST") {
    global $conn;
    InputValidator::findUser($_POST['username'], $_POST['password']);

    //da aggiungere la gestione della sessione
    
}
?>