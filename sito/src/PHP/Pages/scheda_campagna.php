<?php
    require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
    require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

    $titolo = 'Scheda Campagna';
    $descrizione = 'Visualizza la scheda della tua campagna';
    $keywords = 'campagna, scheda, visualizza';

    $header = headerPlaceholder($titolo, $descrizione, $keywords, "scheda_campagna");
    $page = file_get_contents(BASE_PATH . "/src/HTML/structure/scheda_campagna.html");
    $footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

    session_start();

    $content = $header . $page . $footer;

    echo $content;
?>