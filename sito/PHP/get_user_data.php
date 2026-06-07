<?php
session_start();
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'your_database_name';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Errore database']);
    exit;
}

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Non loggato']);
    exit;
}

$username = $_SESSION['username'];

$stmt = $pdo->prepare("SELECT username, email, description FROM Utente WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode([
        'success' => true,
        'username' => $user['username'],
        'email' => $user['email'],
        'description' => $user['description']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Utente non trovato']);
}
?>