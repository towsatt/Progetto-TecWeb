<?php
    $titolo = "- Area Personale - Dungeons & Donkeys Reunion";
    $descrizione = "Gestisci il tuo profilo, le tue campagne e le tue sessioni";
    $keywords = "Area Personale, Dungeons & Donkeys Reunion, Campagne";

    require_once BASE_PATH . "/src/PHP/Controllers/AuthController.php";
    require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
    require_once BASE_PATH . "/src/PHP/Queries/Queries.php";
    session_start();
if (!isset($_SESSION['username'])) {
    header("Location: /login");
    exit();
}

try {
    // Carica i dati dal database (solo una volta)
    $userData = getUserData($_SESSION['username']);
    if (!$userData) {
        // Utente non trovato – distruggi sessione e reindirizza
        session_destroy();
        header("Location: /login");
        exit();
    }

    $email = htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars($userData['description'] ?? 'Descriviti! (max 500 caratteri)', ENT_QUOTES, 'UTF-8');
    $profile_img = htmlspecialchars($userData['profile_img'] ?? '/assets/imgs/default_profile.png', ENT_QUOTES, 'UTF-8');

    // Leggi il template HTML
    $page = file_get_contents(BASE_PATH . "/src/HTML/structure/area_personale.html");

    // Sostituisci i placeholder (usa sempre htmlspecialchars)
    $page = str_replace("[NICKNAME]", $username, $page);
    $page = str_replace("[USERNAME]", '<input type="text" id="nickname" value="' . $username . '" readonly disabled>', $page);
    $page = str_replace("[PASSWORD]", '<input type="password" id="password" value="********" readonly disabled>', $page);
    $page = str_replace("[EMAIL]", '<input type="email" id="email" value="' . $email . '" maxlength="70" readonly disabled>', $page);
    $page = str_replace("[DESCRIPTION]", '<textarea id="description" maxlength="500" rows="4" readonly disabled>' . $description . '</textarea>', $page);
    $page = str_replace("[PROFILE_IMG]", '<img src="' . $profile_img . '" alt="Foto Profilo" width="100" height="100">', $page);

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
    } catch (Exception $e) {
        header("Location: /500");
        exit();
    }

?>