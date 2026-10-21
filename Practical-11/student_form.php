<?php
require_once 'db_crud.php';

$id = $_GET['id'] ?? '';
$name = $email = $course = '';
$is_edit = false;
if (!empty($id)) {
    $is_edit = true;
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $email = $row['email'];
        $course = $row['course'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><title><?php echo $is_edit ? 'Edit' : 'Add'; ?> Student</title></head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2><?php echo $is_edit ? 'Edit Student Record' : 'Register New Student'; ?></h2>
    
    <div style="max-width: 400px; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
        <form method="POST" action="crud_action.php">
            <!-- Hidden inputs to tell the action script what to do -->
            <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($id); ?>">
            
            <label>Full Name:</label><br>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required style="width: 95%; padding: 8px; margin: 5px 0 15px 0;"><br>
            
            <label>Email Address:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required style="width: 95%; padding: 8px; margin: 5px 0 15px 0;"><br>
            
            <label>Course:</label><br>
            <input type="text" name="course" value="<?php echo htmlspecialchars($course); ?>" required style="width: 95%; padding: 8px; margin: 5px 0 20px 0;"><br>
            
            <button type="submit" style="padding: 10px 15px; background: #198754; color: white; border: none; cursor: pointer; border-radius: 4px;">
                <?php echo $is_edit ? 'Update Record' : 'Save Record'; ?>
            </button>
            <a href="manage_students.php" style="margin-left: 15px; text-decoration: none; color: #6c757d;">Cancel</a>
        </form>
    </div>
</body>
</html>