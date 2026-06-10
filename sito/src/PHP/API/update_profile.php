<?php
header('Content-Type: application/json; charset=utf-8');

// Definisci il BASE_PATH per includere i file
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__ . '/..');
}

require_once BASE_PATH . "/src/PHP/connessione_DB.php";
require_once BASE_PATH . "/src/PHP/Controllers/InputController.php";
require_once BASE_PATH . "/src/PHP/Validators/InputValidator.php";
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

    // Ottieni i dati inviati
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['field']) || !isset($input['value'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Dati non validi.'
        ]);
        exit();
    }

    $field = trim($input['field']);
    $value = trim($input['value']);
    $currentUsername = $_SESSION['username'];

    // Validazione e controlli specifici per campo
    switch ($field) {
        case 'email':
            // Valida l'email
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Il formato dell\'email non è valido.'
                ]);
                exit();
            }

            // Controlla se l'email è già in uso (se diversa da quella attuale)
            $currentData = getUserData($currentUsername);
            if ($currentData['email'] !== $value && emailExists($value)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Questa email è già registrata.'
                ]);
                exit();
            }

            InputValidator::validateEmail($value);
            break;

        case 'username':
            // Valida l'username
            InputValidator::validateUsername($value);

            // Controlla se l'username è già in uso
            if (usernameExists($value, $currentUsername)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Questo username è già utilizzato.'
                ]);
                exit();
            }
            $field = 'username';
            break;

        case 'password':
            // Valida la password
            InputValidator::validatePassword($value);
            break;

        case 'description':
            // Valida la descrizione
            InputValidator::validateDescription($value);
            break;

        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Campo non valido.'
            ]);
            exit();
    }

    // Aggiorna il database
    $updated = updateUserProfile($currentUsername, $field, $value);

    if (!$updated) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Errore durante l\'aggiornamento dei dati.'
        ]);
        exit();
    }

    // Aggiorna la sessione
    if ($field === 'username') {
        $_SESSION['username'] = $value;
    } elseif ($field === 'email') {
        $_SESSION['email'] = $value;
    } elseif ($field === 'password') {
        $_SESSION['password'] = $value; // La sessione contiene la password in chiaro (come nel codice originale)
    } elseif ($field === 'description') {
        $_SESSION['description'] = $value;
    }

    // Restituisci il risultato
    echo json_encode([
        'success' => true,
        'message' => 'Dati aggiornati con successo!',
        'newValue' => $field === 'password' ? '********' : $value
    ]);

} catch (InputError $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (DatabaseError $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Errore durante l'aggiornamento profilo: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Si è verificato un errore imprevisto.'
    ]);
}
?>
