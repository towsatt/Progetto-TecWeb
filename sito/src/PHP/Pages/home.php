<?php

require_once BASE_PATH . "/src/PHP/Helper/Helper.php";

$titolo = "- Home";
$descrizione = "Un posto perfetto per i vari appassionati di Campagne, dove tenere conto dei progressi, dei personaggi, delle sessioni e molto altro!";
$keywords = "D&D, Dungeons & Dragons, Campagne, Sessioni, RPG, dungeon master, avventura, NPC, trame, gioco di ruolo";

$header = headerPlaceholder($titolo, $descrizione, $keywords, "home");
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/home.html");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

echo $header . $page . $footer;