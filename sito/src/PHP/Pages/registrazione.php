<?php
require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
require_once BASE_PATH . "/src/PHP/Controllers/AuthController.php";
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";


$titolo = "Registrazione - Un1co";
$descrizione = "Crea un account su Un1co per accedere a tutte le funzionalità del sito.";
$keywords = "registrazione, un1co, account";

session_start();

$header = headerPlaceholder($titolo, $descrizione, $keywords, "registrazione");
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/registrazione.html");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");


if (!empty($_POST)) {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        if (registerUser($_POST['username'], $_POST['email'], $_POST['password'])) {
            header("Location: login.php");
            exit();
        } else {
            throw new InvalidParameterError("Registrazione fallita. Riprovare!");
        }
    }
}

echo $header . $page . $footer;
?>