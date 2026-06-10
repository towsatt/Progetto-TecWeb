<?php
require_once BASE_PATH . "/src/PHP/Helper/Helper.php";

$titolo = 'Pagina di creazione campagna';
$descrizione = 'Crea la tua campagna personalizzata e inizia a giocare con i tuoi amici!';
$keywords = 'campagna, gioco, amici, personalizzata, creazione';

$password = generaCodiceCampagna();

$header = headerPlaceholder($titolo, $descrizione, $keywords, "crea_campagna");
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/crea_campagna.html");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");


$content = $header . str_replace("[CODICE_CAMPAGNA]", $password, $page) . $footer;

echo $content;