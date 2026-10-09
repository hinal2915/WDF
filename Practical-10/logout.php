<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $conn = new mysqli('localhost', 'root', '', 'studenthub_db');
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
}
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

setcookie("remember_token", "", time() - 3600, "/");

session_destroy();

header("Location: login.php");
exit();
?>