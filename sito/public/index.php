<?php

define('BASE_PATH', dirname(__DIR__));
$page = $_GET['page'] ?? 'home';

require BASE_PATH . '/src/PHP/Pages/' . $page . '.php';