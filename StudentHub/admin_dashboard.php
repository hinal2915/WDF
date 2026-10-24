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

if ($_SESSION['role'] !== 'admin') {
    header("Location: student_dashboard.php"); 
    exit();
}
?>
<h2 style="color: darkblue;">Welcome to the Admin Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>You have full administrative access.</p>
<a href="logout.php">Logout</a>