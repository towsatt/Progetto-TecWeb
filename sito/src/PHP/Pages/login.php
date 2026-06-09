<?php
    $titolo = "- Login - Dungeons & Donkeys Reunion";
    $descrizione = "Accedi al tuo account per gestire il tuo profilo";
    $keywords = "Accedi, Dungeons & Donkeys Reunion, Campagne, Sessioni, login, accesso";

    require_once BASE_PATH . "/src/PHP/Controllers/AuthController.php";

    session_start();

    if (AuthController::isLogged()) {
            header("Location: area_personale.php");
    }
    