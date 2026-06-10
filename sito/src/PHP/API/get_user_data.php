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

try {
    // Verifica che l'utente sia autenticato
    if (!isset($_SESSION['username'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Utente non autenticato.'
        ]);
        exit();
    }

    // Ottieni i dati dell'utente dal database
    $userData = getUserData($_SESSION['username']);

    if (!$userData) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Utente non trovato.'
        ]);
        exit();
    }

    // Restituisci i dati
    echo json_encode([
        'success' => true,
        'username' => htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8'),
        'description' => htmlspecialchars($userData['description'] ?? '', ENT_QUOTES, 'UTF-8')
    ]);

} catch (DatabaseError $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Errore durante il caricamento dati utente: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Si è verificato un errore imprevisto.'
    ]);
}
?>
