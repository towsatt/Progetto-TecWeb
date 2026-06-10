<?php

    $titolo = "- Su di noi - Un1co";
    $descrizione = "Scopri di più su Un1co, la piattaforma dedicata agli appassionati di giochi di ruolo. Conosci il nostro team e la nostra missione!";
    $keywords = "Un1co, su di noi, chi siamo, team, missione, giochi di ruolo";

    require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
    $header = headerPlaceholder($titolo, $descrizione, $keywords, "su_di_noi");
    $page = file_get_contents(BASE_PATH . "/src/HTML/structure/su_di_noi.html");
    $footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

    $content = $header . $page . $footer;
    echo $content;

?>