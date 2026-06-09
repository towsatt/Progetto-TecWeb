<?php
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

echo implode('', array_map(fn($v) => "<p>$v[username]</p>", searchByUsername("Dun")));