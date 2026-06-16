<?php
    require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
    require_once BASE_PATH . "/src/PHP/Queries/Queries.php";
    require_once BASE_PATH . "src/PHP/Pages/Lista_Campagne.php";

    $titolo = 'Scheda Campagna';
    $descrizione = 'Visualizza la scheda della tua campagna';
    $keywords = 'campagna, scheda, visualizza';

    $header = headerPlaceholder($titolo, $descrizione, $keywords, "scheda_campagna");
    $page = file_get_contents(BASE_PATH . "/src/HTML/structure/scheda_campagna.html");
    $footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

    session_start();

    try{
            // Verifica se è stato passato il codice campagna
            if(!isset($_GET['codice']) || empty($_GET['codice'])){
                header("Location: /campagne");
                exit();
            }

            // Validazione del codice campagna (formato: lettere maiuscole, underscore, numeri, max 16 caratteri)
            $codice_campagna = $_GET['codice'];
            if(!preg_match('/^[A-Z0-9_]{1,16}$/', $codice_campagna)){
                header("Location: /campagne");
                exit();
            }

            $campagna = getCampagnaByCodice($codice_campagna);

            if(!$campagna){
                header("Location: /campagne");
                exit();
            }

            // Ottieni le sessioni della campagna
            $sessioni = getSessioniByCampagna($codice_campagna);
            
            // Validazione del parametro sessione
            $sessione_corrente = 1;
            if(isset($_GET['sessione'])){
                $sessione_param = intval($_GET['sessione']);
                if($sessione_param > 0){
                    $sessione_corrente = $sessione_param;
                }
            }

            // Verifica se l'utente è membro della campagna o è il dungeon master
            $username = $_SESSION['username'];
            $is_member = false;
            $is_dm = ($campagna['dungeon_master'] === $username);

            $user_campagne = getUserCampagne($username);
            if($user_campagne){
                foreach($user_campagne as $uc){
                    if($uc['codice_campagna'] === $codice_campagna){
                        $is_member = true;
                        break;
                    }
                }
            }

            // Se non è membro e non è il DM, e la campagna non è pubblica, reindirizza
            // La visibilità nel database è un boolean (0/1 o true/false)
            $is_public = (bool)$campagna['visibilita'];
            if(!$is_member && !$is_dm && !$is_public){
                header("Location: /campagne");
                exit();
            }

            // Ottieni i personaggi della campagna
            $personaggi = getPersonaggiByCampagna($codice_campagna);

            // Popola i placeholder nella pagina
            $page = str_replace("[NOME_CAMPAGNA]", htmlspecialchars($campagna['nome'], ENT_QUOTES, 'UTF-8'), $page);
            $page = str_replace("[NUM_SESSIONE]", $sessione_corrente, $page);

            // Popola la descrizione della sessione corrente
            $descrizione_sessione = "";
            if($sessioni && count($sessioni) > 0){
                $index = $sessione_corrente - 1;
                if(isset($sessioni[$index])){
                    $descrizione_sessione = htmlspecialchars($sessioni[$index]['descrizione'], ENT_QUOTES, 'UTF-8');
                }
            }
            $page = str_replace("[DESCRIZIONE]", $descrizione_sessione, $page);

            // Popola la lista dei personaggi
            $personaggi_html = "";
            if($personaggi && count($personaggi) > 0){
                foreach($personaggi as $pg){
                    $personaggi_html .= '<li>' . htmlspecialchars($pg['nome'], ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($pg['classe'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($pg['razza'], ENT_QUOTES, 'UTF-8') . ' (Lv. ' . htmlspecialchars($pg['livello'], ENT_QUOTES, 'UTF-8') . ')</li>';
                }
            } else {
                $personaggi_html = '<li>Nessun personaggio in questa campagna</li>';
            }
            $page = str_replace("[Personaggi]", $personaggi_html, $page);

            // Popola la paginazione
            $paginazione_html = "";
            if($sessioni && count($sessioni) > 0){
                $totale_sessioni = count($sessioni);
                for($i = 1; $i <= $totale_sessioni; $i++){
                    $active_class = ($i === $sessione_corrente) ? ' class="active"' : '';
                    $paginazione_html .= '<li' . $active_class . '><a href="/scheda_campagna?codice=' . urlencode($codice_campagna) . '&sessione=' . $i . '">' . $i . '</a></li>';
                }
            }
            $page = str_replace("[PAGINAZIONE]", $paginazione_html, $page);
        } catch (Exception $e){
            header("Location: /500");
            exit();
        }
    

    $content = $header . $page . $footer;

    echo $content;
?>