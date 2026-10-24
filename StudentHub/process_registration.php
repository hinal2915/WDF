<?php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'studenthub_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    
    if (empty($user) || empty($email) || empty($pass)) {
        die("<h3 style='color:red;'>Error: All fields are required.</h3><a href='register_user.html'>Go Back</a>");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<h3 style='color:red;'>Error: Invalid email format.</h3><a href='register_user.html'>Go Back</a>");
    }
    
    if (strlen($pass) < 8) {
        die("<h3 style='color:red;'>Error: Password must be at least 8 characters.</h3><a href='register_user.html'>Go Back</a>");
    }

    $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ss", $user, $email);
    $stmt_check->execute();
    $stmt_check->store_result();
    
    if ($stmt_check->num_rows > 0) {
        echo "<h3 style='color:red;'>Error: Username or Email already exists. Please choose another.</h3>";
        echo "<a href='register_user.html'>Go Back</a>";
        $stmt_check->close();
    } else {
        $stmt_check->close();
        
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        
        $insert_sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("sss", $user, $email, $hashed_password);
        
        if ($stmt_insert->execute()) {
            echo "<h3 style='color:#198754;'>Success: User '$user' registered securely!</h3>";
            echo "<a href='register_user.html'>Register another user</a>";
        } else {
            echo "<h3 style='color:red;'>Error: Could not register user. " . $conn->error . "</h3>";
        }
        
        $stmt_insert->close();
    }
}

$conn->close();
?>