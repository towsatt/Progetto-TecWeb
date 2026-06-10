<?php

require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

function generaCodiceCampagna(): string
{
    $byteCount = (int) ceil(16 * 3 / 4);
    // todo da controllare nel DB che non ci siano già codici uguali, anche se è molto improbabile
    return substr(base64_encode(random_bytes($byteCount)), 0, 16);
}

function headerPlaceholder(string $titolo, string $descrizione, string $keywords, string $source = "home"): string
{
    $content = file_get_contents(BASE_PATH . "/src/HTML/template/header.html");
    $content = str_replace("[TITOLO]", $titolo, $content);
    $content = str_replace("[DESCRIZIONE]", $descrizione, $content);
    $content = str_replace("[KEYWORDS]", $keywords, $content);

    $home = "<a href=\"/home\"><span lang=\"en\">Home</span></a>";
    $visualizza_campagne = "<a href=\"/campagne\">Visualizza Campagne</a>";
    $crea_campagna = "<a href=\"/crea_campagna\">Crea Campagna</a>";
    $forum = ""; // <li><a href="./structure/forum.php"><span lang="en">Forum</span></a></li>
    $about_us = "<li><a href=\"/about_us\" type=\"info\">Info</a></li>";
    $logo = "<img src=\"/assets/imgs/header_e_footer/logo.png\" href=\"/\" alt=\"Logo\" width=\"90\" height=\"70\">";
    $breadcrumb = "<li>Home</li>\n<li><span aria-current=\"page\"></span></li>";

    switch ($source) {
        case "home":
            $home = "<p lang=\"en\">Home</p>";
            $logo = "<img src=\"/assets/imgs/header_e_footer/logo.png\" alt=\"Logo\" width=\"90\" height=\"70\">";
            break;
        case "lista_campagne":
            $visualizza_campagne = "<p>Visualizza Campagne</p>";
            $breadcrumb = "<li><a href=\"/home\">Home</a></li>\n<li>Visualizza Campagne</li>\n<li><span aria-current=\"page\"></span></li>";
            break;
        case "crea_campagna":
            $crea_campagna = "<p>Crea Campagna</p>";
            $breadcrumb = "<li><a href=\"/home\">Home</a></li>\n<li><a href=\"/campagne\">Visualizza Campagne</a></li>\n<li>Crea Campagna</li>\n<li><span aria-current=\"page\"></span></li>";
            break;
        // case "forum":
        //     $forum = "<p>Forum</p>";
        //     break;
        case "su_di_noi":
            $about_us = "<li><a href=\"/home\">Home</a></li>\n<li><span aria-current=\"page\"></span></li>";
            break;
        default:
            break;
    };

    $content = str_replace("[HOME]", $home, $content);
    $content = str_replace("[VISUALIZZA_CAMPAGNE]", $visualizza_campagne, $content);
    $content = str_replace("[CREA_CAMPAGNA]", $crea_campagna, $content);
    $content = str_replace("[FORUM]", $forum, $content);
    $content = str_replace("[ABOUT_US]", $about_us, $content);
    $content = str_replace("[LOGO]", $logo, $content);
    $content = str_replace("[BREADCRUMB]", $breadcrumb, $content);
    return $content;
}