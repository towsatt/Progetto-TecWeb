<?php
    $titolo = "- Area Personale - Dungeons & Donkeys Reunion";
    $descrizione = "Gestisci il tuo profilo, le tue campagne e le tue sessioni";
    $keywords = "Area Personale, Dungeons & Donkeys Reunion, Campagne";

    require_once BASE_PATH . "/src/PHP/Controllers/AuthController.php";

    session_start();

    try {
        if (!AuthController::isLogged()) {
            header("Location: login.php");
        }
    }