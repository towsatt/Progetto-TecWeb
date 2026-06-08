<?php
$titolo = "- Home - Dungeons & Donkeys Reunion";
$descrizione = "Un posto perfetto per i vari appassionati di Campagne, dove tenere conto dei progressi, dei personaggi, delle sessioni e molto altro!";
$keywords = "Dungeons & Donkeys Reunion, D&D, Dungeons & Dragons, Campagne, Sessioni, RPG, dungeon master, avventura, NPC, trame, gioco di ruolo";

include __DIR__ . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR . "header.html";
echo file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "structure" . DIRECTORY_SEPARATOR . "home.html");
include __DIR__ . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR . "footer.html";
?>