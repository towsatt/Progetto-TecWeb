<?php
include_once __DIR__ . '/../connessione_DB.php';
include_once __DIR__ . '/../Queries/Queries.php';
include_once __DIR__ . '/../Handlers/ErrorHandler.php';
include_once __DIR__ . '/../Validators/InputValidator.php';
include_once __DIR__ . '/../Controllers/InputController.php';

class PersonaggioController {

    public static function createCharacter($user_id, $campagna_id,$namech, $classe, $razza, $eta, $altezza, $peso, $occhi, $capelli, $carnagione, $aspetto, $aleorg, $storia, $lp, $forza, $destrezza, $costit, $intel, $sagg, $carisma, $ispiraz, $bdcomp, $percpas, $valuta, $clarm, $speed, $dadivita, $maxptifer,$incantesimi) {
        try {
                $namech=InputController::validateCharacterName($_POST['namech']);
                $classe=InputController::validateEnumClasse($_POST['classe']); //controllare se va bene 
                $razza=InputController::validateEnumRazza($_POST['razza']);//controllare se va bene
                $eta=InputController::validateNonNegativeInteger($_POST['eta']);
                $altezza=InputController::validateNonNegativeInteger($_POST['altezza']);
                $peso=InputController::validateNonNegativeInteger($_POST['peso']);
                $occhi=InputController::validateMaxLength($_POST['occhi'], 30);
                $capelli=InputController::validateMaxLength($_POST['capelli'], 30);
                $carnagione=InputController::validateMaxLength($_POST['carnagione'], 30);
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

               
                //voce equipaggiamento iniziale coincide a forza però ricontrollo
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
            if(!empty($_POST)) {
                if (isset($_POST['namech']) && isset($_POST['classe']) && isset($_POST['razza']) && isset($_POST['eta']) && isset($_POST['altezza']) && isset($_POST['peso']) && isset($_POST['occhi']) && isset($_POST['capelli']) && isset($_POST['carnagione']) && isset($_POST['aspetto']) && isset($_POST['aleorg']) && isset($_POST['storia']) && isset($_POST['lp']) && isset($_POST['forza']) && isset($_POST['destrezza']) && isset($_POST['costit']) && isset($_POST['intel']) && isset($_POST['sagg']) && isset($_POST['carisma']) && isset($_POST['ispiraz']) && isset($_POST['bdcomp']) && isset($_POST['percpas']) && isset($_POST['valuta']) && isset($_POST['clarm']) && isset($_POST['speed']) && isset($_POST['dadivita']) && isset($_POST['maxptifer']) && isset($_POST['incantesimi'])) {
                    if (createCharacter($user_id, $campagna_id,$namech, $classe, $razza, $eta, $altezza, $peso, $occhi, $capelli, $carnagione, $aspetto, $aleorg, $storia, $lp, $forza, $destrezza, $costit, $intel, $sagg, $carisma, $ispiraz, $bdcomp, $percpas, $valuta, $clarm, $speed, $dadivita, $maxptifer,$incantesimi)) {
                        header("Location: dashboard.php?campagna_id=" . $_GET['campagna_id']);
                        exit();
                    } else {
                        throw new InvalidParameterError("Creazione personaggio fallita. Riprovare!");
                    }
                }
            
            }
        }
        catch (DatabaseError $e) {
            http_response_code(500);
        } catch (InputError $e) {
            http_response_code(400);
        } catch (Exception $e) {
            http_response_code(500);
        }
    }
}