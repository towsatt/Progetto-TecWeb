<?php
    require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
    require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

    $titolo = 'Scheda Personaggio';
    $descrizione = 'Visualizza la scheda del personaggio';
    $keywords = 'personaggio, scheda, visualizza';
    
    $header = headerPlaceholder($titolo, $descrizione, $keywords, "scheda_personaggio");
    $page = file_get_contents(BASE_PATH . "/src/HTML/structure/scheda_persoanggio.html");
    $footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");
    
    session_start();

        try{

            
            
            //verificare se l'utente loggato coincide con il persoanggio che si è fatto l'accesso 
            //verificare se è dungeon master che può eliminare il personaggio

            //cod_campagna valido ed esistente
            //cod_campagna+cod_utente coincide con un personaggio -> visualizza+ modifica dati
            //cod_campagna+cod_utente==dungeon_master) ->visualizza+modifica+elimina
            //se è solo un utente a caso visualizza (in toeria non serve controllo se campagna pubblica perchè fatta prima )
            
            if(!isset($_GET['codice']) || empty($_GET['codice'])){
                header("Location: /campagne");
                exit();
            }
            $codice_campagna=$_GET['codice'];
            if(!isset($_GET['idutente']) || empty($_GET['idutente'])){
                header("Location: /campagne");
                exit();
            }
            $id_utente=$_GET['idutente'];

   
            $username=$_SESSION['username'];

            // carica personaggio per i controlli
            $personaggio=getPersonaggioByCodici($codice_campagna,$id_utente);

            // se l'utente loggato coincide con l'id utente della scheda
            // verifica se i campi del personaggio sono vuoti: in tal caso imposta
            // un messaggio e reindirizza alla pagina di creazione personaggio

            //può andare bene come soluzione?
            if($username===$id_utente){
                $isEmpty = empty($personaggio) || empty(array_filter($personaggio, function($v){ return $v !== null && $v !== ''; }));
                if($isEmpty){
                    $_SESSION['message'] = 'crea personaggio';
                    header("Location: /creazione_personaggio");
                    exit();
                }
            }

            $page=str_replace("[NOME_PERSONAGGIO]", htmlspecialchars($personaggio['nome'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[CLASSE]", htmlspecialchars($personaggio['classe'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[RAZZA]", htmlspecialchars($personaggio['razza'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[LIVELLO]", htmlspecialchars($personaggio['livello'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[ESPERIENZA]", htmlspecialchars($personaggio['esperienza'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[ETA]", htmlspecialchars($personaggio['eta'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[ALTEZZA]", htmlspecialchars($personaggio['altezza'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[PESO]", htmlspecialchars($personaggio['peso'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[OCCHI]", htmlspecialchars($personaggio['occhi'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[CARNAGIONE]", htmlspecialchars($personaggio['carnagione'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[CAPELLI]", htmlspecialchars($personaggio['capelli'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[ASPETTO]", htmlspecialchars($personaggio['aspetto'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[ALLEORG]", htmlspecialchars($personaggio['alleorg'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[STORIA]", htmlspecialchars($personaggio['storia'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[TESORO]", htmlspecialchars($personaggio['tesoro'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[COMPLING]", htmlspecialchars($personaggio['compling'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[FORZA]", htmlspecialchars($personaggio['forza'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[DESTREZZA]", htmlspecialchars($personaggio['destrezza'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[COSTIT]", htmlspecialchars($personaggio['costit'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[INTELL]", htmlspecialchars($personaggio['intell'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[SAGG]", htmlspecialchars($personaggio['sagg'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[CARISMA]", htmlspecialchars($personaggio['carisma'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[ISPIRAZIONE]", htmlspecialchars($personaggio['ispirazione'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[BONUSCOMP]", htmlspecialchars($personaggio['bonuscomp'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[PERCPASS]", htmlspecialchars($personaggio['percpass'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[EQUIINIZ]", htmlspecialchars($personaggio['forza'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[VALUTA]", htmlspecialchars($personaggio['valuta'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[CLASSARM]", htmlspecialchars($personaggio['classarm'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[INIZIATIVA]", htmlspecialchars($personaggio['destrezza'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[VELOCITA]", htmlspecialchars($personaggio['velocita'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[DADIVITA]", htmlspecialchars($personaggio['dadivita'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[MAXPTIFER]", htmlspecialchars($personaggio['maxptifer'], ENT_QUOTES, 'UTF-8'), $page);
            $page=str_replace("[INCANTESIMI]", htmlspecialchars($personaggio['incantesimi'], ENT_QUOTES, 'UTF-8'), $page);


        } catch (Exception $e){
            header("Location: /500");
            exit();
        }
    

    
    

    $content= $header . $page . $footer;
    echo $content;