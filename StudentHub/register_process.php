<?php
// Initialize an array to hold any error messages
$errors = [];
$successMessage = "";

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Sanitize Inputs
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $name = sanitize_input($_POST["fullname"] ?? "");
    $email = sanitize_input($_POST["email"] ?? "");
    $mobile = sanitize_input($_POST["mobile"] ?? "");
    $course = sanitize_input($_POST["course"] ?? "");
    $year = sanitize_input($_POST["year"] ?? "");
    $gender = sanitize_input($_POST["gender"] ?? "");
    $password = sanitize_input($_POST["password"] ?? "");

    // 2. Server-Side Validation
    if (empty($name) || !preg_match("/^[a-zA-Z\s]{3,}$/", $name)) {
        $errors[] = "Valid name is required (letters only, min 3 chars).";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    if (empty($mobile) || !preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "A valid 10-digit mobile number is required.";
    }

    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // 3. Process and Store Data if No Errors
    if (empty($errors)) {
        $studentData = [
            $name, 
            $email, 
            $mobile, 
            $course, 
            $year, 
            $gender, 
            date("Y-m-d H:i:s") 
        ];

        // Define the CSV file path
        $file = 'student_records.csv';
        $fileExists = file_exists($file);
        $handle = fopen($file, 'a');

        if ($handle !== false) {
            if (!$fileExists) {
                fputcsv($handle, ['Name', 'Email', 'Mobile', 'Course', 'Year', 'Gender', 'Registration_Date']);
            }
            fputcsv($handle, $studentData);
            fclose($handle);
            
            $successMessage = "Registration successful! Your data has been securely saved.";
        } else {
            $errors[] = "Server error: Unable to save data at this time.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Status</title>
    <style>
        body { background: #eef7ff; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .message-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 90%; }
        .success { color: #198754; }
        .error { color: #dc3545; text-align: left; }
        .back-btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #0d6efd; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="message-box">
        <?php if (!empty($errors)): ?>
            <h2 style="color: #dc3545;">Registration Failed</h2>
            <ul class="error">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (!empty($successMessage)): ?>
            <h2 class="success">Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
            <p><?php echo $successMessage; ?></p>
        <?php else: ?>
            <h2>Invalid Request</h2>
            <p>Please submit the form directly.</p>
        <?php endif; ?>
        
        <br>
        <a href="registration.html" class="back-btn">Return to Registration</a>
    </div>
</body>
</html>