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
            return "Lo username che hai utilizzato già esiste! Scegline uno nuovo!";
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
    $query = "SELECT * FROM Campagna WHERE visibilita = true"; //boolean: true, not 'true' / "true"

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

function getMembriByCampagna(string $codice_campagna): ?array
{
    $query = "SELECT utente FROM Membro WHERE codice_campagna = ?";

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
        error_log("Errore DB durante il caricamento dei membri della campagna: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

//verificare se con quel codice campagna esiste il membro con quell'utente, se esiste allora ritorna true altrimenti false
function isUserMemberOfCampagna(string $username, string $codice_campagna): bool
{
    $query = "SELECT * FROM Membro WHERE utente = ? AND codice_campagna = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $username, $codice_campagna);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il controllo membro campagna: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}

//verificare se con quel codice utente e quel codice campagna, è un dungeon master, se lo è ritorna true altrimenti false
function isUserDungeonMaster(string $username, string $codice_campagna): bool
{
    $query = "SELECT * FROM Membro WHERE utente = ? AND codice_campagna = ? AND ruolo = 'dungeon_master'";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $username, $codice_campagna);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il controllo dungeon master: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}
//verificare se il personaggio esiste già per quell'utente e quella campagna, se esiste ritorna true altrimenti false
function personaggioExist(string $campagna_id,string $user_id): bool
{
    $query = "SELECT * FROM Personaggio WHERE campagna_id = ? AND user_id = ?";

    try {
        $connection = DBAccess::getInstance();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $campagna_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante il controllo personaggio: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
    }
}
//inserire realmente il personaggio nel db
function setCharacter(string $campagna_id,string $user_id, string $namech, string $classe, string $razza, string $eta, string $altezza, string $peso, string $occhi, string $carnagione, string $capelli, string $aspetto, string $aleorg, string $storia, string $lp, string $forza, string $destrezza, string $costit, string $intel, string $sagg, string $carisma, string $ispiraz, string $bdcomp, string $percpas, string $valuta, string $clarm, string $speed, string $dadivita, string $maxptifer,string $incantesimi): bool
{
    $query = "INSERT INTO Personaggio (campagna_id, user_id, namech, classe, razza, eta, altezza, peso, occhi, carnagione, capelli, aspetto, aleorg, storia, lp, forza, destrezza, costit, intel, sagg, carisma, ispiraz, bdcomp, percpas, valuta, clarm, speed, dadivita, maxptifer, incantesimi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        $connection = DBAccess::getInstance();
        if(personaggioExist($campagna_id,$user_id)){
            return "Il personaggio l'hai già creato per questa campagna!";
        }
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ssssssssssssssssssssssssssssss', $campagna_id, $user_id, $namech, $classe, $razza, $eta, $altezza, $peso, $occhi, $carnagione, $capelli, $aspetto, $aleorg, $storia, $lp, $forza, $destrezza, $costit, $intel, $sagg, $carisma, $ispiraz, $bdcomp, $percpas, $valuta, $clarm, $speed, $dadivita, $maxptifer, $incantesimi);
        return $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante la creazione del personaggio: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore durante la creazione del personaggio.");
    }
}

function createCharacter(string $user_id, string $campagna_id, string $namech, string $classe, string $razza, string $eta, string $altezza, string $peso, string $occhi, string $carnagione, string $capelli, string $aspetto, string $aleorg, string $storia, string $lp, string $forza, string $destrezza, string $costit, string $intel, string $sagg, string $carisma, string $ispiraz, string $bdcomp, string $percpas, string $valuta, string $clarm, string $speed, string $dadivita, string $maxptifer,string $incantesimi): bool
{
    try {
        return setCharacter($user_id,$campagna_id,$namech,$classe,$razza,$eta,$altezza,$peso,$occhi,$carnagione,$capelli,$aspetto,$aleorg,$storia,$lp,$forza,$destrezza,$costit,$intel,$sagg,$carisma,$ispiraz,$bdcomp,$percpas,$valuta,$clarm,$speed,$dadivita,$maxptifer,$incantesimi);
    } catch (mysqli_sql_exception $e) {
        error_log("Errore DB durante la creazione del personaggio: " . $e->getMessage());
        throw new DatabaseError("Si è verificato un errore durante la creazione del personaggio.");
    }
}