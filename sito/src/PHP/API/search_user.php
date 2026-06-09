<?php

require_once __DIR__ . '/../connessione_DB.php';
require_once __DIR__ . '/../Queries/Queries.php';

$query = $_GET['query'] ?? '';

header('Content-Type: application/json');

echo json_encode(
    searchByUsername($query)
);