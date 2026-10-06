<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $course = $_POST['course'];

    try {
        $sql = "INSERT INTO students (name, email, course) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $email, $course]);
        
        echo "<h3 style='color:#198754;'>Success: New student '$name' registered successfully!</h3>";
        
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) {
            echo "<h3 style='color:red;'>Error: The email '$email' already exists in the database.</h3>";
        } else {
            echo "<h3 style='color:red;'>Database Error: " . $e->getMessage() . "</h3>";
        }
    }
}
?>

<!-- Updated Form with Input Fields -->
<div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; margin-top: 20px; font-family: Arial, sans-serif;">
    <form method="POST" action="add_student.php" style="display: flex; flex-direction: column; gap: 10px;">
        <label>Name:</label>
        <input type="text" name="fullname" placeholder="John Doe" required style="padding: 8px;">
        
        <label>Email:</label>
        <input type="email" name="email" placeholder="newemail@student.com" required style="padding: 8px;">
        
        <label>Course:</label>
        <input type="text" name="course" value="B.Tech" required style="padding: 8px;">
        
        <button type="submit" style="padding: 10px; background: #198754; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 10px;">
            Register Student
        </button>
    </form>
</div>