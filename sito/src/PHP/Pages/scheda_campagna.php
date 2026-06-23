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

    try{
            // Verifica se è stato passato il codice campagna
            if(!isset($_GET['codice']) || empty($_GET['codice'])){
                header("Location: /campagne");
                exit();
            }
            // Validazione del codice campagna (formato: lettere maiuscole, underscore, numeri, max 16 caratteri)
            $codice_campagna = $_GET['codice'];
            //$codice_campagna = 'CAMP01_CRIMSON'; //togliere
            if(!preg_match('/^[A-Z0-9_]{1,16}$/', $codice_campagna)){
                header("Location: /campagne");
                exit();
            }
            
            $campagna = getCampagnaByCodice($codice_campagna);

            if(!$campagna){
                header("Location: /campagne");
                exit();
            }
            
            // Verifica se l'utente è membro della campagna o è il dungeon master
            if(isset($_SESSION['username'])) {
                $username = $_SESSION['username'];
                $is_member = false;
                $is_dm = ($campagna['dungeon_master'] === $username);
                
                if(!$is_dm) {
                    $user_campagne = getUserCampagne($username);
                    if($user_campagne) {
                        foreach($user_campagne as $uc) {
                            if($uc['codice_campagna'] === $codice_campagna) {
                                $is_member = true;
                                break;
                            }
                        }
                    }
                }
            }
            else { //nel caso $_SESSION['username'] non sia ancora dichiarata, da sistemare
                $username = null;
                $is_member = null;
                $is_dm = null;
            }
            
            // Se non è membro e non è il DM, e la campagna non è pubblica, reindirizza
            // La visibilità nel database è un boolean (0/1 o true/false)
            $is_public = (bool)$campagna['visibilita'];
            if(!$is_member && !$is_dm && !$is_public){
                header("Location: /campagne");
                exit();
            }

            //Se è DM, i tasti di elimina, modifica, invita, aggiungi sono utilizzabili
            if($is_dm) {
                $page = str_replace("disabled", "", $page);
            }
            
            // Popola i placeholder nella pagina
            $page = str_replace("[NOME_CAMPAGNA]", htmlspecialchars($campagna['nome'], ENT_QUOTES, 'UTF-8'), $page);
            $page = str_replace("[TIPOLOGIA]", htmlspecialchars($campagna['tipologia'], ENT_QUOTES, 'UTF-8'), $page);
            $page = str_replace("[DURATA]", htmlspecialchars($campagna['durata'], ENT_QUOTES, 'UTF-8'), $page);
            $page = str_replace("[CODICE_CAMPAGNA]", htmlspecialchars($campagna['codice_campagna'], ENT_QUOTES, 'UTF-8'), $page);
            //$page = str_replace("[PASSWORD]", htmlspecialchars($campagna[''], ENT_QUOTES, 'UTF-8'), $page);
            $page = str_replace("[DESCRIZIONE]", htmlspecialchars($campagna['descrizione'], ENT_QUOTES, 'UTF-8'), $page);
            
            // Ottieni i personaggi della campagna
            $membri = getMembriByCampagna($codice_campagna);

            // Popola il blocco di Membri
            $dm_html = '<li>' . htmlspecialchars($campagna['dungeon_master'], ENT_QUOTES, 'UTF-8') . ' - Dungeon Master</li>';
            $page = str_replace("[DUNGEON_MASTER]", $dm_html, $page);

            $membri_html = "";
            if($membri && count($membri) > 0) {
                foreach($membri as $mb) {
                    $membri_html .= '<li>' . htmlspecialchars($mb['utente'], ENT_QUOTES, 'UTF-8') . ' - ' . htmlspecialchars($mb['nome'], ENT_QUOTES, 'UTF-8') . '</li>';
                }
            }
            else {
                $membri_html = '<li>Nessun membro in questa campagna, prova ad invitare un amico!</li>';
            }
            $page = str_replace("[MEMBRI]", $membri_html, $page);
            

            // Ottieni le sessioni della campagna
            $sessioni = getSessioniByCampagna($codice_campagna);

            // Popola il blocco sessioni
            $bloccoSessioni_html = "";
            if($sessioni && count($sessioni) > 0){
                $totale_sessioni = count($sessioni);
                for($i = 1; $i <= $totale_sessioni; $i++){
                    $bloccoSessioni_html .= '<li' . '><a href="/scheda_sessione?codice=' . urlencode($codice_campagna) . '&sessione=' . $i . '">' . 'Sessione ' . $i . '</a></li>';
                }
            }
            
            $page = str_replace("[SESSIONI]", $bloccoSessioni_html, $page);

        } catch (Exception $e){
            header("Location: /500");
            exit();
        }

    $content = $header . $page . $footer;

    echo $content;
?>