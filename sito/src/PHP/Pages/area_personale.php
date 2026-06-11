<?php
    $titolo = "- Area Personale - Dungeons & Donkeys Reunion";
    $descrizione = "Gestisci il tuo profilo, le tue campagne e le tue sessioni";
    $keywords = "Area Personale, Dungeons & Donkeys Reunion, Campagne";

    require_once BASE_PATH . "/src/PHP/Controllers/AuthController.php";
    require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
    require_once BASE_PATH . "/src/PHP/Queries/Queries.php";
    require_once BASE_PATH . "/src/HTML/structure/area_personale.html";

    session_start();
try{
    if (isset($_SESSION['username'])) {
        $page = file_get_contents(BASE_PATH . "/src/HTML/structure/area_personale.html");
        //Cambia nickname sull'h1
        $page = str_replace("[NICKNAME]", htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'), $page);

        // Valorizza il campo nickname / username
        $usernamePlaceholder = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
        $page = str_replace("[USERNAME]", "<input type=\"text\" id=\"nickname\" value=\"{$usernamePlaceholder}\" readonly disabled>", $page);

        //Cambia password con input disabilitato, modificabile solo tramite il pulsante "Modifica"
        $passwordPlaceholder = htmlspecialchars($_SESSION['password'] ?? '', ENT_QUOTES, 'UTF-8');
        $page = str_replace("[PASSWORD]", "<input type=\"password\" class=\"password-input\" id=\"password\" value=\"********\" data-real-password=\"{$passwordPlaceholder}\" readonly disabled>", $page);

        //Cambia descrizione con textarea disabilitato, modificabile solo tramite il pulsante "Modifica"
        $descriptionPlaceholder = htmlspecialchars($_SESSION['description'] ?? '', ENT_QUOTES, 'UTF-8');
        if ($descriptionPlaceholder === '') {
            $descriptionPlaceholder = 'Descriviti! (max 500 caratteri)';
        }
        $page = str_replace("[DESCRIPTION]", "<textarea id=\"description\" maxlength=\"500\" rows=\"4\" readonly disabled>{$descriptionPlaceholder}</textarea>", $page);

        // Valorizza il campo email
        $emailPlaceholder = htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8');
        $page = str_replace("[EMAIL]", "<input type=\"email\" id=\"email\" value=\"{$emailPlaceholder}\" maxlength=\"70\" readonly disabled>", $page);
        if(isset($_SESSION['profile_img'])) {
            $profileImgEscaped = htmlspecialchars($_SESSION['profile_img'], ENT_QUOTES, 'UTF-8');
            $page = str_replace("[PROFILE_IMG]", "<img src=\"{$profileImgEscaped}\" alt=\"Profilo\" width=\"100\" height=\"100\">", $page);
        } else {
            $page = str_replace("[PROFILE_IMG]", "<img src=\"/assets/imgs/default_profile.png\" alt=\"Profilo\" width=\"100\" height=\"100\">", $page);
        }

        // Ottieni i personaggi dell'utente
        $personaggi = getUserCharacters($_SESSION['username']);
        $personaggi_html = '';
        if($personaggi && count($personaggi) > 0){
            foreach($personaggi as $pg){
                $personaggi_html .= '<li><a href="/scheda_campagna?codice=' . htmlspecialchars($pg['codice_campagna'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($pg['nome'], ENT_QUOTES, 'UTF-8') . '</a> - ' . htmlspecialchars($pg['classe'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($pg['razza'], ENT_QUOTES, 'UTF-8') . ' (Lv. ' . htmlspecialchars($pg['livello'], ENT_QUOTES, 'UTF-8') . ')</li>';
            }
        } else {
            $personaggi_html = '<li>Nessun personaggio creato</li>';
        }
        $page = str_replace("[PERSONAGGI_LIST]", $personaggi_html, $page);

        // Ottieni le campagne dell'utente
        $campagne_user = getUserCampagne($_SESSION['username']);
        $campagne_html = '';
        if($campagne_user && count($campagne_user) > 0){
            foreach($campagne_user as $campagna){
                $campagna_details = getCampagnaByCodice($campagna['codice_campagna']);
                if($campagna_details){
                    $campagne_html .= '<li><a href="/scheda_campagna?codice=' . htmlspecialchars($campagna['codice_campagna'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($campagna_details['nome'], ENT_QUOTES, 'UTF-8') . '</a></li>';
                }
            }
        } else {
            $campagne_html = '<li>Nessuna campagna a cui partecipi</li>';
        }
        $page = str_replace("[CAMPAGNE_LIST]", $campagne_html, $page);

        $header = headerPlaceholder($titolo, $descrizione, $keywords, "area_personale");
        $footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

    
        $content = $header . $page . $footer;
        echo $content;
    } else {
        header("Location: login.php");
        exit();
    }
} catch (Exception $e) {
        header("Location: 500.php");
        exit();
}

?>