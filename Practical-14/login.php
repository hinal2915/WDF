<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $stmt = $conn->prepare("SELECT id, role FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['username'] = $username;

        $action = "Logged in successfully";
        $audit = $conn->prepare("INSERT INTO audit_logs (user_id, action) VALUES (?, ?)");
        $audit->bind_param("is", $row['id'], $action);
        $audit->execute();

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><title>Login - StudentHub</title></head>
<body style="font-family: Arial; padding: 50px; max-width: 400px; margin: auto;">
    <h2>System Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required style="width: 100%; padding: 8px; margin-bottom: 10px;"><br>
        <label>Password:</label><br>
        <input type="password" name="password" required style="width: 100%; padding: 8px; margin-bottom: 15px;"><br>
        <button type="submit" style="padding: 10px; width: 100%; background: #0d6efd; color: white; border: none;">Login</button>
    </form>
</body>
</html>