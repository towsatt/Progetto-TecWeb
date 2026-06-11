<?php

require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

session_start();

$titolo = 'Lista delle campagne';
$descrizione = 'Guarda le campagne pubbliche e quelle a cui partecipi';
$keywords = 'campagne, gioco, unisciti, scopri, disponibili';

// Ottieni il contenuto del template HTML
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/lista_campagne.html");

// Ottieni le campagne pubbliche (visibilità = true)
try{

$campagne = getCampagnePubbliche();
$rowsHtml = '';

if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $campagne_user = getUserCampagne($username);
    if($campagne_user && count($campagne_user) > 0) {
        foreach($campagne_user as $campagna_user){
            if($campagna_user['visibilita'] === true){
                //Se la visibilità è true, rimuovi la campagna dalle campagne pubbliche
                $campagne = array_filter($campagne, function($campagna) use ($campagna_user) {
                    return $campagna['codice_campagna'] !== $campagna_user['codice_campagna'];
                });
            }
        }
        $campagne = [...$campagne_user, ...$campagne];
    }

}

if ($campagne && count($campagne) > 0 ) {
    foreach ($campagne as $campagna) {
        // Tronca la descrizione a 50 caratteri per l'introduzione
        $introDescrizione = strlen($campagna['descrizione']) > 50 
            ? substr($campagna['descrizione'], 0, 50) . '...' 
            : $campagna['descrizione'];
        
        // Crea una riga cliccabile per ogni campagna
        $codiceCampagna = htmlspecialchars($campagna['codice_campagna'], ENT_QUOTES, 'UTF-8');
        $rowsHtml .= '<tr onclick="window.location.href=\'/scheda_campagna?codice=' . $codiceCampagna . '\'" style="cursor: pointer;">';
        $rowsHtml .= '<td>' . htmlspecialchars($campagna['nome']) . '</td>';
        $rowsHtml .= '<td>' . htmlspecialchars($campagna['tipologia']) . '</td>';
        $rowsHtml .= '<td>' . htmlspecialchars($campagna['durata']) . '</td>';
        $rowsHtml .= '<td>' . htmlspecialchars($introDescrizione) . '</td>';
        $rowsHtml .= '</tr>';
    }
} else {
    $rowsHtml = '<tr><td colspan="5">Nessuna campagna pubblica disponibile al momento.</td></tr>';
}
}
// Sostituisci il placeholder con le righe generate
$page = str_replace('{{CAMPAGNE_ROWS}}', $rowsHtml, $page);

// Genera header e footer (nota: headerPlaceholder richiede già la sessione per decidere il menu)
$header = headerPlaceholder($titolo, $descrizione, $keywords, "lista_campagne");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

// Assembla la pagina completa
$content = $header . $page . $footer;

echo $content;