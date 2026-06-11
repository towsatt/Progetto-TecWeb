<?php
$titolo = "- Login - Un1co";
$descrizione = "Accedi al tuo account per gestire il tuo profilo";
$keywords = "Accedi, Un1co, Campagne, Sessioni, login, accesso";

require_once BASE_PATH . "/src/PHP/Helper/Helper.php";
require_once BASE_PATH . "/src/PHP/Controllers/AuthController.php";
require_once BASE_PATH . "/src/PHP/Queries/Queries.php";

$header = headerPlaceholder($titolo, $descrizione, $keywords, "login");
$page = file_get_contents(BASE_PATH . "/src/HTML/structure/login.html");
$footer = file_get_contents(BASE_PATH . "/src/HTML/template/footer.html");

session_start();


if (!empty($_POST)) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $query = "SELECT * FROM Utente WHERE username = ?";

        try {
            $connection = DBAccess::getInstance();
            $stmt = $connection->prepare($query);
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            $result = $stmt->get_result();

            $user = $result->fetch_object();
            if ($user && password_verify($_POST['password'], $user->password)) {  // ← CONTROLLO SU $user
                $_SESSION['username'] = $user->username;
                header("Location: area_personale.php");
                exit();
            }
            else {
                $errore = "<p>Credenziali non corrette! Riprova!</p>";
                $page = str_replace("[ERRORE]", $errore, $page);
            }
        } catch (mysqli_sql_exception $e) {
            error_log("Errore DB durante la conferma login: " . $e->getMessage());
            throw new DatabaseError("Si è verificato un errore nel caricamento dei dati.");
        }
    } 
}


$content = $header . $page . $footer;
echo $content;
