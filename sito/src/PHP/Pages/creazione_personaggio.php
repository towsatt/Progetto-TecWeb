<?php
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";
require_once BASE_PATH . "/src/PHP/Controllers/PersonaggioController.php";

$titolo = "Creazione Personaggio - Un1co";
$descrizione = "Crea un personaggio su Un1co per iniziare la tua partita.";
$keywords = "creazione, personaggio, un1co, gioco";
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/creazione_personaggio.html");
session_start();
try{
    //verificare che l'utente sia loggato, altrimenti reindirizzare alla pagina di login
    if (!isset($_SESSION['user_id'])) {
        header("Location: login");
        exit();
    }

    // verifico se fa parte della campagna isUserMemberOfCampagna  e se è dungeon master isUserDungeonMaster
    if(isUserMemberOfCampagna($_SESSION['user_id'], $_GET['campagna_id']) || isUserDungeonMaster($_SESSION['user_id'], $_GET['campagna_id'])) {
        if(isUserDungeonMaster($_SESSION['user_id'], $_GET['campagna_id'])) {
            header("Location: dashboard.php?campagna_id=" . $_GET['campagna_id']);
            exit();
    } else  if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Chiamata alla funzione createCharacter con i dati del form
            PersonaggioController::createCharacter($_SESSION['user_id'], $_GET['campagna_id'], $_POST['namech'] ?? '', $_POST['classe'] ?? '', $_POST['razza'] ?? '', $_POST['eta'] ?? '', $_POST['altezza'] ?? '', $_POST['peso'] ?? '', $_POST['occhi'] ?? '', $_POST['capelli'] ?? '', $_POST['carnagione'] ?? '', $_POST['aspetto'] ?? '', $_POST['aleorg'] ?? '', $_POST['storia'] ?? '', $_POST['lp'] ?? '', $_POST['forza'] ?? '', $_POST['destrezza'] ?? '', $_POST['costit'] ?? '', $_POST['intel'] ?? '', $_POST['sagg'] ?? '', $_POST['carisma'] ?? '', $_POST['ispiraz'] ?? '', $_POST['bdcomp'] ?? '', $_POST['percpas'] ?? '', $_POST['valuta'] ?? '', $_POST['clarm'] ?? '', $_POST['speed'] ?? '', $_POST['dadivita'] ?? '', $_POST['maxptifer'] ?? '', $_POST['incantesimi'] ?? '');
        }
            
        
    } else {
        // L'utente non ha i permessi per accedere alla pagina, e lo reindirizzo alla sua area personale 
        header("Location: area_personale");
        exit();
    }

}
catch(Exception $e){
    header("Location: 500.php");
    exit();
}



$header = headerPlaceholder($titolo, $descrizione, $keywords, "area_personale");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html"); 

//devo fare un str_replace per sostituire i placeholder con i valori reali, ad esempio [NOME_PERSONAGGIO] con il nome del personaggio appena creato, [CLASSE_PERSONAGGIO] con la classe del personaggio, ecc.
$content = $header . $page . $footer;
echo $content;


// Esempio di utilizzo della funzione searchByUsername
echo implode('', array_map(fn($v) => "<p>$v[username]</p>", searchByUsername("Dun")));