<?php
$titolo = "- Login - Un1co";
$descrizione = "Accedi al tuo account per gestire il tuo profilo";
$keywords = "Accedi, Un1co, Campagne, Sessioni, login, accesso";

require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
require_once BASE_PATH . "/src/PHP/Controllers/AuthController.php";
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

$header = headerPlaceholder($titolo, $descrizione, $keywords, "login");
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/login.html");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

session_start();

AuthController::login($_POST['username'] ?? '', $_POST['password'] ?? '');


$content = $header . $page . $footer;
echo $content;
?>