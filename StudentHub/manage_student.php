<?php
session_start();
require_once 'db_crud.php';
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM students WHERE 1=1";
if (!empty($search)) {
    $search_term = "%" . $conn->real_escape_string($search) . "%";
    $sql .= " AND (name LIKE '$search_term' OR email LIKE '$search_term' OR course LIKE '$search_term')";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head><title>Student Management (CRUD)</title></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Student Management Dashboard</h2>
        <?php
    if (isset($_SESSION['message'])) {
        echo "<p style='color: green; font-weight: bold;'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    if (isset($_SESSION['error'])) {
        echo "<p style='color: red; font-weight: bold;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <!-- Search and Filter Form -->
    <form method="GET" action="" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search by name, email, or course..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 8px; width: 300px;">
        <button type="submit" style="padding: 8px 15px; background: #6c757d; color: white; border: none; cursor: pointer;">Search / Filter</button>
        <a href="manage_students.php" style="margin-left: 10px; text-decoration: none; color: #0d6efd;">Clear Search</a>
        
        <a href="student_form.php" style="float: right; padding: 8px 15px; background: #0d6efd; color: white; text-decoration: none; border-radius: 4px;">+ Add New Student</a>
    </form>

    <!-- Data Table -->
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: left; border-collapse: collapse;">
        <tr style="background: #f8f9fa;">
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['student_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                    <td>
                        <a href="student_form.php?id=<?php echo $row['student_id']; ?>" style="color: #0d6efd; text-decoration: none;">Edit</a> | 
                        
                        <!-- JavaScript Confirmation for Delete -->
                        <a href="crud_action.php?action=delete&id=<?php echo $row['student_id']; ?>" 
                           onclick="return confirm('Are you sure you want to permanently delete this student record?');" 
                           style="color: red; text-decoration: none;">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align: center;">No student records found.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>