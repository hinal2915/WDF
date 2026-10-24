<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
    session_unset();
    session_destroy();
    header("Location: login.php?msg=timeout");
    exit();
}
$_SESSION['last_activity'] = time();
if ($_SESSION['role'] !== 'student') {
    die("Access Denied: You do not have student permissions.");
}
?>
<h2 style="color: green;">Welcome to the Student Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>Your role is: <?php echo $_SESSION['role']; ?></p>
<a href="logout.php">Logout</a>