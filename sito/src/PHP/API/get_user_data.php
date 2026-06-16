<?php
header('Content-Type: application/json; charset=utf-8');

// Definisci il BASE_PATH per includere i file
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/..');
}

require_once BASE_PATH . "/src/PHP/connessione_DB.php";
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";
require_once BASE_PATH . "/src/PHP/Handlers/ErrorHandler.php";

session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autenticato']);
    exit();
}   

$data = getUserData($_SESSION['username']);
if ($data) {
    echo json_encode(['success' => true, 'username' => $data['username'], 'email' => $data['email'], 'description' => $data['description'] ?? '']);
} else {
    echo json_encode(['success' => false, 'message' => 'Utente non trovato']);
}
?>
