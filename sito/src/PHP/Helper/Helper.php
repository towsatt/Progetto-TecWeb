<?php

require_once BASE_PATH . "/src/PHP/Queries/Queries.php";
function generaCodiceCampagna(): string
{
    $byteCount = (int) ceil(16 * 3 / 4);
    return substr(base64_encode(random_bytes($byteCount)), 0, 16);
}

function headerPlaceholder(string $titolo, string $descrizione, string $keywords, string $source = "home"): string
{
    $content = file_get_contents(BASE_PATH . "/src/HTML/template/header.html");
    $content = str_replace("[TITOLO]", $titolo, $content);
    $content = str_replace("[DESCRIZIONE]", $descrizione, $content);
    $content = str_replace("[KEYWORDS]", $keywords, $content);

    $home = "<a href=\"?page=home\"><span lang=\"en\">Home</span></a>";
    $visualizza_campagne = "<a href=\"?page=lista_campagne\">Visualizza Campagne</a>";
    $crea_campagna = "<a href=\"?page=crea_campagna\">Crea Campagna</a>";
    $forum = ""; // <li><a href="./structure/forum.php"><span lang="en">Forum</span></a></li>
    $about_us = "<li><a href=\"?page=su_di_noi\" type=\"info\">Info</a></li>";
    match ($source) {
        "home" => $home = "<p lang=\"en\">Home</p>",
        "mostra_campagne" => $visualizza_campagne = "<p>Visualizza Campagne</p>",
        "crea_campagna" => $crea_campagna = "<p>Crea Campagna</p>",
        // "forum" => $forum = "<p>Forum</p>",
        "su_di_noi" => $about_us = "<p>Info</p>",
        default => null
    };

    $content = str_replace("[HOME]", $home, $content);
    $content = str_replace("[VISUALIZZA_CAMPAGNE]", $visualizza_campagne, $content);
    $content = str_replace("[CREA_CAMPAGNA]", $crea_campagna, $content);
    $content = str_replace("[FORUM]", $forum, $content);
    $content = str_replace("[ABOUT_US]", $about_us, $content);

    return $content;
}