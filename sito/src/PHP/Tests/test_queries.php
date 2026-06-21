<?php

require_once BASE_PATH . '/src/PHP/Queries/Queries.php';

global $testHtml;
$testHtml = '<ul>';

function addHtml(string $html): void
{
    global $testHtml;
    $testHtml .= $html;
}

function beginTestSection(string $name): void
{
    addHtml('<li><strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong><ul>');
}

function endTestSection(bool $success): void
{
    addHtml('</ul><p>Result: ' . ($success ? 'Ok' : 'Sbagliato') . '</p></li>');
}

function getTestDbConnection(): mysqli
{
    return DBAccess::getInstance();
}

function normalizeValue($value)
{
    if (is_null($value) || is_bool($value) || is_int($value) || is_float($value) || is_string($value)) {
        return $value;
    }

    if (is_array($value)) {
        $normalized = [];
        foreach ($value as $key => $item) {
            $normalized[$key] = normalizeValue($item);
        }

        if (array_values($normalized) === $normalized) {
            usort($normalized, function ($a, $b) {
                return strcmp(json_encode($a), json_encode($b));
            });
        } else {
            ksort($normalized);
        }

        return $normalized;
    }

    return $value;
}

function compareResults($expected, $actual): bool
{
    return json_encode(normalizeValue($expected)) === json_encode(normalizeValue($actual));
}

function runCase(string $label, $expected, $actual): bool
{
    $result = compareResults($expected, $actual);
    addHtml('<li>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ': ' . ($result ? 'Ok' : 'Sbagliato'));
    if (!$result) {
        addHtml('<ul><li>expected: ' . htmlspecialchars(json_encode(normalizeValue($expected), JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') . '</li>');
        addHtml('<li>actual: ' . htmlspecialchars(json_encode(normalizeValue($actual), JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') . '</li></ul>');
    }
    addHtml('</li>');
    return $result;
}

function fetchAssocList(mysqli $conn, string $sql, string $types = '', array $params = []): ?array
{
    try {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return ['exception' => 'mysqli_prepare_error', 'message' => $conn->error];
        }

        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            return ['exception' => 'mysqli_execute_error', 'message' => $stmt->error];
        }

        $result = $stmt->get_result();
        if (!$result) {
            return null;
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return count($rows) === 0 ? null : $rows;
    } catch (Throwable $e) {
        return ['exception' => get_class($e), 'message' => $e->getMessage()];
    }
}

function fetchAssocSingle(mysqli $conn, string $sql, string $types = '', array $params = []): ?array
{
    $rows = fetchAssocList($conn, $sql, $types, $params);
    return is_array($rows) && count($rows) > 0 ? $rows[0] : null;
}

function executeDirectPrepared(string $sql, string $types = '', array $params = [])
{
    try {
        $conn = getTestDbConnection();
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return ['exception' => 'mysqli_prepare_error', 'message' => $conn->error];
        }

        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            return ['exception' => 'mysqli_execute_error', 'message' => $stmt->error];
        }

        $result = $stmt->get_result();
        if ($result === false) {
            return null;
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return count($rows) === 0 ? null : $rows;
    } catch (Exception $e) {
        return ['exception' => get_class($e), 'message' => $e->getMessage()];
    }
}

function prepareTestFixtures(): void
{
    $conn = getTestDbConnection();
    $testPasswordHash = password_hash('TestPass1!', PASSWORD_BCRYPT);

    $conn->query("INSERT INTO Utente (username, password, email, profile_picture_path) VALUES ('test_user_a', '{$testPasswordHash}', 'test_user_a@example.com', NULL) ON DUPLICATE KEY UPDATE password = VALUES(password), email = VALUES(email), profile_picture_path = VALUES(profile_picture_path)");
    $conn->query("INSERT INTO Utente (username, password, email, profile_picture_path) VALUES ('test_user_b', '{$testPasswordHash}', 'test_user_b@example.com', NULL) ON DUPLICATE KEY UPDATE password = VALUES(password), email = VALUES(email), profile_picture_path = VALUES(profile_picture_path)");
    $conn->query("INSERT INTO Campagna (codice_campagna, nome, tipologia, durata, descrizione, dungeon_master, visibilita) VALUES ('TESTCAMP1', 'Campagna di test', 'Originale', 'One Shot', 'Descrizione di test', 'test_user_a', true) ON DUPLICATE KEY UPDATE nome = nome");
    $conn->query("INSERT INTO Membro (codice_campagna, utente) VALUES ('TESTCAMP1', 'test_user_b') ON DUPLICATE KEY UPDATE utente = utente");
    $conn->query("INSERT INTO Personaggio (codice_campagna, utente, nome, classe, razza, livello, eta, altezza, peso, occhi, carnagione, capelli, aspetto, alleati_organizzazione, storia, tesoro, competenze_linguaggi, forza, destrezza, costituzione, intelligenza, saggezza, carisma, ispirazione, bonus_competenza, percezione_passiva, valuta, classe_armatura, velocita, dadi_vita, max_punti_ferita, equipaggiamento, incantesimi) VALUES ('TESTCAMP1', 'test_user_b', 'TestPersonaggio', 'Guerriero', 'Umano', 1, 25, 175, 70, 'Marroni', 'Chiara', 'Castani', 'Aspetto test', 'Alleanza test', 'Storia test', '5 MO', 'Comune', 15, 12, 13, 10, 11, 9, 0, 2, 10, '10 MO', '18', 30, '1D10', 10, 'Spada, Scudo', '') ON DUPLICATE KEY UPDATE nome = nome");
    $conn->query("INSERT INTO Sessione (codice_campagna, data, descrizione) SELECT 'TESTCAMP1', '2024-01-01 10:00:00', 'Sessione di test' FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM Sessione WHERE codice_campagna = 'TESTCAMP1' AND descrizione = 'Sessione di test')");
}

function wrapCall(callable $call)
{
    try {
        return $call();
    } catch (Throwable $e) {
        return ['exception' => get_class($e), 'message' => $e->getMessage()];
    }
}

function test_searchByUsername(): bool
{
    beginTestSection('searchByUsername');
    $conn = getTestDbConnection();
    $cases = [
        ['label' => 'prefix Player', 'input' => 'Player', 'expected' => executeDirectPrepared("SELECT * FROM Utente WHERE username LIKE ? ORDER BY username ASC", 's', ['Player%'])],
        ['label' => 'exact DungeonMaster1', 'input' => 'DungeonMaster1', 'expected' => executeDirectPrepared("SELECT * FROM Utente WHERE username LIKE ? ORDER BY username ASC", 's', ['DungeonMaster1%'])],
        ['label' => 'no match', 'input' => 'NoSuchUser', 'expected' => executeDirectPrepared("SELECT * FROM Utente WHERE username LIKE ? ORDER BY username ASC", 's', ['NoSuchUser%'])],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => searchByUsername($case['input']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_login(): bool
{
    beginTestSection('login');
    $conn = getTestDbConnection();

    $cases = [
        ['label' => 'correct credentials', 'username' => 'test_user_a', 'password' => 'TestPass1!', 'expected' => true],
        ['label' => 'wrong password', 'username' => 'test_user_a', 'password' => 'WrongPass1!', 'expected' => false],
        ['label' => 'missing user', 'username' => 'no_such_user', 'password' => 'whatever', 'expected' => false],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => login($case['username'], $case['password']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_registerUser(): bool
{
    beginTestSection('registerUser');
    $uniqueId = uniqid('reg_', true);
    $cases = [
        ['label' => 'new user', 'email' => "{$uniqueId}@example.com", 'password' => 'StrongPass!23', 'username' => "user_{$uniqueId}", 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
        ['label' => 'duplicate email', 'email' => 'test_user_a@example.com', 'password' => 'StrongPass!23', 'username' => "user_{$uniqueId}_2", 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
        ['label' => 'duplicate username', 'email' => "{$uniqueId}_dup@example.com", 'password' => 'StrongPass!23', 'username' => 'test_user_a', 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => registerUser($case['email'], $case['password'], $case['username']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_emailExists(): bool
{
    beginTestSection('emailExists');
    $cases = [
        ['label' => 'existing email', 'email' => 'test_user_a@example.com', 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
        ['label' => 'missing email', 'email' => 'missing_email@example.com', 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
        ['label' => 'another existing email', 'email' => 'test_user_b@example.com', 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => emailExists($case['email']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_usernameExists(): bool
{
    beginTestSection('usernameExists');
    $cases = [
        ['label' => 'existing username', 'username' => 'test_user_a', 'expected' => true],
        ['label' => 'missing username', 'username' => 'no_such_user', 'expected' => false],
        ['label' => 'another existing username', 'username' => 'test_user_b', 'expected' => true],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => usernameExists($case['username']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_updateUserProfile(): bool
{
    beginTestSection('updateUserProfile');
    $conn = getTestDbConnection();
    $originalEmail = 'test_user_a@example.com';
    $newEmail = 'test_user_a_changed@example.com';
    $newPassword = 'NewTestPass!23';

    $cases = [
        ['label' => 'update email', 'field' => 'email', 'value' => $newEmail, 'expected' => true],
        ['label' => 'update password', 'field' => 'password', 'value' => $newPassword, 'expected' => true],
        ['label' => 'invalid field', 'field' => 'invalid_field', 'value' => 'x', 'expected' => ['exception' => 'InputError']],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => updateUserProfile('test_user_a', $case['field'], $case['value']));
        if ($case['case'] ?? true) {
            if ($case['expected'] === ['exception' => 'InputError']) {
                if (!runCase($case['label'], $case['expected'], is_array($actual) ? ['exception' => $actual['exception']] : $actual)) {
                    $success = false;
                }
            } else {
                if (!runCase($case['label'], $case['expected'], $actual)) {
                    $success = false;
                }
            }
        }
    }

    if ($originalEmail !== $newEmail) {
        $conn->query("UPDATE Utente SET email = '{$originalEmail}' WHERE username = 'test_user_a'");
    }

    endTestSection($success);
    return $success;
}

function test_getUserData(): bool
{
    beginTestSection('getUserData');
    $conn = getTestDbConnection();
    $cases = [
        ['label' => 'existing user', 'username' => 'test_user_a', 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
        ['label' => 'missing user', 'username' => 'no_such_user', 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
        ['label' => 'empty username', 'username' => '', 'expected' => ['exception' => 'DatabaseError', 'message' => 'Si è verificato un errore nel caricamento dei dati.']],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => getUserData($case['username']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_getUserCampagne(): bool
{
    beginTestSection('getUserCampagne');
    $cases = [
        ['label' => 'user with membership', 'username' => 'test_user_b', 'expected' => executeDirectPrepared("SELECT * FROM Membro WHERE utente = ? ORDER BY codice_campagna ASC", 's', ['test_user_b'])],
        ['label' => 'user without membership', 'username' => 'test_user_a', 'expected' => executeDirectPrepared("SELECT * FROM Membro WHERE utente = ? ORDER BY codice_campagna ASC", 's', ['test_user_a'])],
        ['label' => 'missing user', 'username' => 'no_such_user', 'expected' => executeDirectPrepared("SELECT * FROM Membro WHERE utente = ? ORDER BY codice_campagna ASC", 's', ['no_such_user'])],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => getUserCampagne($case['username']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_getCampagnePubbliche(): bool
{
    beginTestSection('getCampagnePubbliche');
    $expected = executeDirectPrepared("SELECT * FROM Campagna ORDER BY codice_campagna ASC");
    $actual = wrapCall(fn() => getCampagnePubbliche());
    $success = runCase('all campaigns', $expected, $actual);
    endTestSection($success);
    return $success;
}

function test_getCampagnaByCodice(): bool
{
    beginTestSection('getCampagnaByCodice');
    $cases = [
        ['label' => 'existing camp', 'codice' => 'TESTCAMP1', 'expected' => fetchAssocSingle(getTestDbConnection(), "SELECT * FROM Campagna WHERE codice_campagna = ?", 's', ['TESTCAMP1'])],
        ['label' => 'missing camp', 'codice' => 'NO_CAMP', 'expected' => fetchAssocSingle(getTestDbConnection(), "SELECT * FROM Campagna WHERE codice_campagna = ?", 's', ['NO_CAMP'])],
        ['label' => 'empty codice', 'codice' => '', 'expected' => fetchAssocSingle(getTestDbConnection(), "SELECT * FROM Campagna WHERE codice_campagna = ?", 's', [''])],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => getCampagnaByCodice($case['codice']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_getUserCharacters(): bool
{
    beginTestSection('getUserCharacters');
    $cases = [
        ['label' => 'existing character', 'username' => 'test_user_b', 'expected' => executeDirectPrepared("SELECT p.*, c.nome as nome_campagna FROM Personaggio p JOIN Membro m ON p.codice_campagna = m.codice_campagna AND p.utente = m.utente JOIN Campagna c ON p.codice_campagna = c.codice_campagna WHERE p.utente = ? ORDER BY p.codice_campagna ASC", 's', ['test_user_b'])],
        ['label' => 'user without characters', 'username' => 'test_user_a', 'expected' => executeDirectPrepared("SELECT p.*, c.nome as nome_campagna FROM Personaggio p JOIN Membro m ON p.codice_campagna = m.codice_campagna AND p.utente = m.utente JOIN Campagna c ON p.codice_campagna = c.codice_campagna WHERE p.utente = ? ORDER BY p.codice_campagna ASC", 's', ['test_user_a'])],
        ['label' => 'missing user', 'username' => 'no_such_user', 'expected' => executeDirectPrepared("SELECT p.*, c.nome as nome_campagna FROM Personaggio p JOIN Membro m ON p.codice_campagna = m.codice_campagna AND p.utente = m.utente JOIN Campagna c ON p.codice_campagna = c.codice_campagna WHERE p.utente = ? ORDER BY p.codice_campagna ASC", 's', ['no_such_user'])],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => getUserCharacters($case['username']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_getSessioniByCampagna(): bool
{
    beginTestSection('getSessioniByCampagna');
    $cases = [
        ['label' => 'existing campaign sessions', 'codice' => 'TESTCAMP1', 'expected' => executeDirectPrepared("SELECT * FROM Sessione WHERE codice_campagna = ? ORDER BY data ASC", 's', ['TESTCAMP1'])],
        ['label' => 'missing campaign', 'codice' => 'NO_CAMP', 'expected' => executeDirectPrepared("SELECT * FROM Sessione WHERE codice_campagna = ? ORDER BY data ASC", 's', ['NO_CAMP'])],
        ['label' => 'empty campaign', 'codice' => '', 'expected' => executeDirectPrepared("SELECT * FROM Sessione WHERE codice_campagna = ? ORDER BY data ASC", 's', [''])],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => getSessioniByCampagna($case['codice']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function test_getPersonaggiByCampagna(): bool
{
    beginTestSection('getPersonaggiByCampagna');
    $cases = [
        ['label' => 'existing campaign', 'codice' => 'TESTCAMP1', 'expected' => executeDirectPrepared("SELECT * FROM Personaggio WHERE codice_campagna = ? ORDER BY utente ASC", 's', ['TESTCAMP1'])],
        ['label' => 'missing campaign', 'codice' => 'NO_CAMP', 'expected' => executeDirectPrepared("SELECT * FROM Personaggio WHERE codice_campagna = ? ORDER BY utente ASC", 's', ['NO_CAMP'])],
        ['label' => 'empty campaign', 'codice' => '', 'expected' => executeDirectPrepared("SELECT * FROM Personaggio WHERE codice_campagna = ? ORDER BY utente ASC", 's', [''])],
    ];

    $success = true;
    foreach ($cases as $case) {
        $actual = wrapCall(fn() => getPersonaggiByCampagna($case['codice']));
        if (!runCase($case['label'], $case['expected'], $actual)) {
            $success = false;
        }
    }

    endTestSection($success);
    return $success;
}

function runAllQueryTests(): void
{
    prepareTestFixtures();
    $results = [
        test_searchByUsername(),
        test_login(),
        test_registerUser(),
        test_emailExists(),
        test_usernameExists(),
        test_updateUserProfile(),
        test_getUserData(),
        test_getUserCampagne(),
        test_getCampagnePubbliche(),
        test_getCampagnaByCodice(),
        test_getUserCharacters(),
        test_getSessioniByCampagna(),
        test_getPersonaggiByCampagna(),
    ];

    $allOk = array_reduce($results, fn($carry, $item) => $carry && $item, true);
    global $testHtml;
    $testHtml .= '<li><strong>Summary</strong><ul><li>' . ($allOk ? 'ALL TESTS: Ok' : 'ALL TESTS: Sbagliato') . '</li></ul></li>';
    $testHtml .= '</ul>';
    echo '<div><h2>Query Tests</h2>' . $testHtml . '</div>';
}

runAllQueryTests();
