<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$header = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "HTML" . DIRECTORY_SEPARATOR . "template" . DIRECTORY_SEPARATOR . "header.html");

session_start();

if(isset($_SESSION['username'])) {
    $header = preg_replace(
        '/<button class="user"><i class=\'bx bx-user-circle\'><\/i><\/box-icon>Login\/Registrati<\/button>/',
        '<div class="user-info">
            <button class="user" href="../../PHP/area_personale.php"><img src="../../assets/User.png" alt="Vai all\'area personale" width="30" height="30">' . htmlspecialchars($_SESSION['username']) . '</button>
            <button class="logout" href="../../PHP/logout.php"><span lang="en">Logout</span></button>
        </div>',
        $header
    );
}

echo $header;
?>