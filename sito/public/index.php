<?php

define('BASE_PATH', dirname(__DIR__));

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    '/' => 'home.php',
    '/area-personale' => 'area_personale.php',
    '/crea_campagna'  => 'crea_campagna.php',
    '/creazione_personaggio' => 'creazione_personaggio.php',
    '/campagne' => 'lista_campagne.php',
    '/dettaglio_campagna' => 'dettaglio_campagna.php',
    '/home'=> 'home.php',
    '/login' => 'login.php',
    '/registrazione' => 'registrazione.php',
    '/about_us' => 'su_di_noi.php',

];

// Logica di routing
if (array_key_exists($request_uri, $routes)) {
    require BASE_PATH . '/src/PHP/Pages/' . $routes[$request_uri];
} else {
    http_response_code(404);
    require BASE_PATH . '/src/PHP/Pages/404.php';
}