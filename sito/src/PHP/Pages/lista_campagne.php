<?php

require_once BASE_PATH . "/src/PHP/Helper/Helper.php";

$titolo = 'lista delle campagne';
$descrizione = 'Scopri le campagne disponibili e unisciti a quelle che ti interessano!';
$keywords = 'campagne, gioco, unisciti, scopri, disponibili';

$header = headerPlaceholder($titolo, $descrizione, $keywords, "lista_campagne");
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/lista_campagne.html");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

$content = $header . $page . $footer;

echo $content;