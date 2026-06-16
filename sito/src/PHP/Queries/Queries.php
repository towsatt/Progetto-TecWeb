<?php

require_once BASE_PATH . "/src/PHP/Handlers/ErrorHandler.php";
require_once BASE_PATH . "/src/PHP/Controllers/InputController.php";
require_once BASE_PATH . "/src/PHP/connessione_DB.php";


function searchByUsername(string $inputUsername = ""): ?array
{
    if (!$inputUsername) {
        return null;
    }
    $username = InputController::validateUsername($inputUsername) . '%';
    $query = "SELECT * FROM Utente WHERE username LIKE ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante la ricerca utente: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function login(string $username, string $password): bool
{
    $query = "SELECT * FROM Utente WHERE username = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = $result->fetch_object();
        if ($user && password_verify($password, $user->password)) {
            return true;
        }
        return false;
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il login: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function registerUser(string $email, string $password, string $username): bool | string
{
    $query = "INSERT INTO Utente (email, password, username) VALUES (?, ?, ?)";

    try {
        $connection = DBAccess::getInstance();
        if(emailExists($email)){
            return "L'email che hai utilizzato già esiste!";
        }

        if(usernameExists($username)){
            return "Lo username che hai utilizzato già esiste! Scelgline uno nuovo!";
        }
        
        $stmt = $connection->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param('sss', $email, $hashedPassword, $username);

        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante la registrazione utente: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

/**
 * Verifica se un'email esiste già nel database
 */
function emailExists(string $email): bool
{
    $query = "SELECT id FROM Utente WHERE email = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il controllo email: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

/**
 * Verifica se un username esiste già nel database (escludendo l'utente corrente)
 */
function usernameExists(string $username): bool
{
    $query = "SELECT username FROM Utente WHERE username = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il controllo username: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

/**
 * Aggiorna un campo del profilo utente
 */
function updateUserProfile(string $username, string $field, string $value): bool
{
    $allowedFields = ['email', 'password', 'username', 'description'];
    
    if (!in_array($field, $allowedFields)) {
        throw new InputError("Campo non valido per l'aggiornamento.");
    }

    // Se è la password, hasharla
    if ($field === 'password') {
        $value = password_hash($value, PASSWORD_BCRYPT);
    }

    $query = "UPDATE Utente SET " . $field . " = ? WHERE username = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $value, $username);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante l'aggiornamento profilo: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore durante l'aggiornamento dei dati.");
    }
}

/**
 * Ottiene i dati dell'utente
 */
function getUserData(string $username): ?array
{
    $query = "SELECT username, email, description FROM Utente WHERE username = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il caricamento dati utente: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function setUserCampagna(string $nome_campagna, string $tipologia, string $durata, string $codice_campagna, string $password, string $descrizione, string $username): bool
{
    $query = "INSERT INTO Campagna (nome_campagna, tipologia, durata, codice_campagna, password, descrizione, username) VALUES (?, ?, ?, ?, ?, ?, ?)";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('sssssss', $nome_campagna, $tipologia, $durata, $codice_campagna, $password, $descrizione, $username);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante l'aggiunta campagna: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore durante l'aggiunta della campagna.");
    }
}


function getUserCampagne(string $username): ?array
{
    $query = "SELECT * FROM Membro WHERE utente = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il caricamento campagne utente: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function getCampagnePubbliche(): ?array
{
    $query = "SELECT * FROM Campagna WHERE visibilita = 'true'";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il caricamento campagne pubbliche: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function getCampagnaByCodice(string $codice_campagna): ?array
{
    $query = "SELECT * FROM Campagna WHERE codice_campagna = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $codice_campagna);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il caricamento campagna: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function getUserCharacters(string $username): ?array
{
    $query = "SELECT p.*, c.nome as nome_campagna FROM Personaggio p 
              JOIN Membro m ON p.codice_campagna = m.codice_campagna AND p.utente = m.utente
              JOIN Campagna c ON p.codice_campagna = c.codice_campagna
              WHERE p.utente = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il caricamento personaggi utente: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function getSessioniByCampagna(string $codice_campagna): ?array
{
    $query = "SELECT * FROM Sessione WHERE codice_campagna = ? ORDER BY data ASC";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $codice_campagna);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il caricamento sessioni: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

function getPersonaggiByCampagna(string $codice_campagna): ?array
{
    $query = "SELECT * FROM Personaggio WHERE codice_campagna = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('s', $codice_campagna);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il caricamento personaggi campagna: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

