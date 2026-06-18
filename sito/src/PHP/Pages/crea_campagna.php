<?php
require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

$titolo = 'Pagina di creazione campagna';
$descrizione = 'Crea la tua campagna personalizzata e inizia a giocare con i tuoi amici!';
$keywords = 'campagna, gioco, amici, personalizzata, creazione';

$password = generaCodiceCampagna();

$header = headerPlaceholder($titolo, $descrizione, $keywords, "crea_campagna");
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/crea_campagna.html");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

session_start();
try{
if(!isset($_SESSION['username'])) {
    header("Location: login");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome_campagna = $_POST['nome_campagna'];
    $tipologia = $_POST['tipologia'];
    $durata = $_POST['durata'];
    $codice_campagna = $_POST['codice_campagna'];
    $password = $_POST['password'];
    $descrizione = $_POST['descrizione'];
    $dungeon_master = $_SESSION['username'];

    $result = setUserCampagna($nome_campagna, $tipologia, $durata, $codice_campagna, $password, $descrizione, $dungeon_master);
    if($result) {
        // Campagna creata con successo
        //Reindirizzata alla pagina per vedere la campagna
        header("Location: /dettaglio_campagna");
        exit();
    }
}
}catch(Exception $e){
    header("Location: 500.php");
    exit();
}

$content = $header . str_replace("[CODICE_CAMPAGNA]", $password, $page) . $footer;

echo $content;