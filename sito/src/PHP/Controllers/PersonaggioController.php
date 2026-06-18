<?php
include_once __DIR__ . '/../connessione_DB.php';
include_once __DIR__ . '/../Queries/Queries.php';
include_once __DIR__ . '/../Handlers/ErrorHandler.php';
include_once __DIR__ . '/../Validators/InputValidator.php';
include_once __DIR__ . '/../Controllers/InputController.php';

//controllo di tutti i dati inseriti dall'utente in creazione_personaggio.html per la creazione del personaggio usando la libreria InputController per ciasuna dicitura presente del html
$namech=InputController::validateCharacterName($_POST['namech']);

$classe=InputController::validateEnumClasse($_POST['classe']); //controllare se va bene 
$razza=InputController::validateEnumRazza($_POST['razza']);//controllare se va bene

$eta=InputController::validateNonNegativeInteger($_POST['eta']);
$altezza=InputController::validateNonNegativeInteger($_POST['altezza']);
$peso=InputController::validateNonNegativeInteger($_POST['peso']);
$occhi=InputController::validateMaxLength($_POST['occhi'], 30);
$capelli=InputController::validateMaxLength($_POST['capelli'], 30);
$aspetto=InputController::validateMaxLength($_POST['aspetto'], 500);
$aleorg=InputController::validateMaxLength($_POST['aleorg'], 500);
$storia=InputController::validateMaxLength($_POST['storia'], 500);
$lp=InputController::validateMaxLength($_POST['lp'], 500); //lp=competenza e linguaggi
$forza=InputController::validateSkillPoints($_POST['forza']);
$destrezza=InputController::validateSkillPoints($_POST['destrezza']);
$costit=InputController::validateSkillPoints($_POST['costit']);
$intel=InputController::validateSkillPoints($_POST['intel']);
$sagg=InputController::validateSkillPoints($_POST['sagg']);
$carisma=InputController::validateSkillPoints($_POST['carisma']);
$ispiraz=InputController::validateNonNegativeInteger($_POST['ispiraz']);
$bdcomp=InputController::validateNonNegativeInteger($_POST['bdcomp']);
$percpas=InputController::validateRangeInteger($_POST['percpas'], 10, 20);//da capire quanto max
$valuta=InputController::validateMaxLength($_POST['valuta'], 30);
$clarm=InputController::validateMaxLength($_POST['clarm'], 30);
$speed=InputController::validateNonNegativeInteger($_POST['speed']);
$dadivita=InputController::validateHitDie($_POST['dadivita']);
$maxptifer=InputController::validateRangeInteger($_POST['maxptifer'], 0, 100);//da capire quanto max
$incantesimi=InputController::validateMaxLength($_POST['incantesimi'], 500);

$iniziativa=$destrezza;
//voce equipaggiamento iniziale coincide a forza però ricontrollo