<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'connessione_DB.php';

$db = new DBAccess();

try {
    $users = $db->researchUser('Dungeon');

    if ($users === null) {
        echo "Nessun utente trovato.\n";
    } else {
        echo "Trovati " . count($users) . " utente(i):\n";
        print_r($users);
    }
} catch (Exception $e) {
    fwrite(STDERR, "Errore: " . $e->getMessage() . "\n");
} finally {
    if (isset($db)) {
        $db->closeConnection();
    }
}
