<?php
include './html/registrazione.html';
include 'connessione_DB.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT email FROM userdata WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();
    $checkUserStmt = $conn->prepare("SELECT username FROM userdata WHERE username = ?");
    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    
    if(!empty($username) && !empty($password) && !empty($confirmPassword) && !empty($email)) {
    // Il messaggio di errore deve vedersi tramite CSS nascosto    
    if ($password !== $confirmPassword) {
            $message = "Passwords do not match";
        } else if ($checkEmailStmt->num_rows > 0) {
            $message = "Email ID already exists";
        } else if($checkUserStmt->num_rows > 0) {
            $message = "Username already exists";
        } else {
            $stmt = $conn->prepare("INSERT INTO userdata (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashedPassword);
            if ($stmt->execute()) {
                $message = "Account created successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $message = "Some fields are empty";
    }

    $checkEmailStmt->close();
    $conn->close();
}
?>