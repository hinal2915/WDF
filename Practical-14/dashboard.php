<?php
session_start();
// Security Check: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'studenthub_db');
$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Dynamic Data Aggregation (Admin Only)
if ($role === 'admin') {
    $student_count = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
    $event_count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];
    $latest_students = $conn->query("SELECT * FROM students ORDER BY id DESC LIMIT 5");
    $recent_logs = $conn->query("SELECT audit_logs.*, users.username FROM audit_logs JOIN users ON audit_logs.user_id = users.id ORDER BY log_id DESC LIMIT 5");
}
?>
<!DOCTYPE html>
<html lang="en">
<head><title>Dashboard</title></head>
<body style="font-family: Arial; margin: 0; padding: 0; display: flex;">

    <!-- DYNAMIC SIDEBAR MENU BASED ON ROLE -->
    <div style="width: 250px; background: #343a40; min-height: 100vh; color: white; padding: 20px;">
        <h3>StudentHub</h3>
        <p>Welcome, <?php echo htmlspecialchars($username); ?> (<?php echo strtoupper($role); ?>)</p>
        <hr>
        <ul style="list-style: none; padding: 0; line-height: 2.5;">
            <li><a href="dashboard.php" style="color: white; text-decoration: none;">Dashboard Home</a></li>
            
            <?php if ($role === 'admin'): ?>
                <!-- Admin-Only Links -->
                <li><a href="#" style="color: #adb5bd; text-decoration: none;">⚙️ Manage Students</a></li>
                <li><a href="#" style="color: #adb5bd; text-decoration: none;">📅 Manage Events</a></li>
                <li><a href="#" style="color: #adb5bd; text-decoration: none;">🛡️ Audit Logs</a></li>
            <?php else: ?>
                <!-- Student-Only Links -->
                <li><a href="#" style="color: #adb5bd; text-decoration: none;">📚 My Profile</a></li>
                <li><a href="#" style="color: #adb5bd; text-decoration: none;">📅 View Events</a></li>
            <?php endif; ?>
            
            <li><a href="logout.php" style="color: #dc3545; text-decoration: none;">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT AREA -->
    <div style="flex: 1; padding: 30px;">
        <h2>Dashboard Overview</h2>

        <?php if ($role === 'admin'): ?>
            <!-- Admin Statistics Cards -->
            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div style="padding: 20px; background: #0d6efd; color: white; border-radius: 5px; flex: 1;">
                    <h4>Total Students</h4>
                    <h2><?php echo $student_count; ?></h2>
                </div>
                <div style="padding: 20px; background: #198754; color: white; border-radius: 5px; flex: 1;">
                    <h4>Total Events</h4>
                    <h2><?php echo $event_count; ?></h2>
                </div>
            </div>

            <!-- Latest Registrations Table -->
            <h3>Latest Registrations</h3>
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                <tr style="background: #f8f9fa;"><th>ID</th><th>Name</th><th>Course</th></tr>
                <?php while ($row = $latest_students->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <!-- Audit Logs Table -->
            <h3>Recent System Activity (Audit Log)</h3>
            <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                <tr style="background: #f8f9fa;"><th>Log ID</th><th>User</th><th>Action</th><th>Time</th></tr>
                <?php while ($log = $recent_logs->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $log['log_id']; ?></td>
                        <td><?php echo htmlspecialchars($log['username']); ?></td>
                        <td><?php echo htmlspecialchars($log['action']); ?></td>
                        <td><?php echo $log['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

        <?php else: ?>
            <!-- Student Content -->
            <div style="padding: 20px; background: #e9ecef; border-radius: 5px;">
                <h3>Welcome to the Student Portal!</h3>
                <p>You do not have administrative privileges. Use the sidebar to view your profile and upcoming events.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>