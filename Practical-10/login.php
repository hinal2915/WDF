<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');

if (isset($_SESSION['user_id'])) {
    redirectUser($_SESSION['role']);
} elseif (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        loginUser($user['id'], $user['username'], $user['role']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            
            session_regenerate_id(true);
            
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie("remember_token", $token, time() + (86400 * 30), "/"); // 30 days
                
                $update_token = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $update_token->bind_param("si", $token, $user['id']);
                $update_token->execute();
            }
            
            loginUser($user['id'], $user['username'], $user['role']);
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}

function loginUser($id, $username, $role) {
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    $_SESSION['last_activity'] = time(); // For session timeout
    redirectUser($role);
}

function redirectUser($role) {
    if ($role === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Secure Login</title></head>
<body style="font-family: Arial; padding: 20px;">
    <div style="max-width: 300px; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        <h2>Login</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'timeout') echo "<p style='color:red;'>Session expired. Please login again.</p>"; ?>
        
        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
            
            <label>Password:</label>
            <input type="password" name="password" required style="width: 100%; margin-bottom: 10px; padding: 8px;">
            
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label><br><br>
            
            <button type="submit" style="padding: 10px; background: #0d6efd; color: white; border: none; width: 100%;">Login</button>
        </form>
    </div>
</body>
</html>