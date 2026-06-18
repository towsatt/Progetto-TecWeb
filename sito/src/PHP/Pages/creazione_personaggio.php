<?php
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";
require_once BASE_PATH . "/src/PHP/Controllers/PersonaggioController.php";
$titolo = "Creazione Personaggio - Un1co";
$descrizione = "Crea un personaggio su Un1co per iniziare la tua partita.";
$keywords = "creazione, personaggio, un1co, gioco";

session_start();

//verificare che l'utente sia loggato, altrimenti reindirizzare alla pagina di login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



echo implode('', array_map(fn($v) => "<p>$v[username]</p>", searchByUsername("Dun")));