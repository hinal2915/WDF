<?php
session_start();
require_once 'db_crud.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'create') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);

    $stmt = $conn->prepare("INSERT INTO students (name, email, course) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $course);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "New student successfully added!";
    } else {
        $_SESSION['error'] = "Error adding student: " . $conn->error;
    }
    header("Location: manage_students.php");
    exit();
}
if ($action === 'update') {
    $id = $_POST['student_id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);

    $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, course = ? WHERE student_id = ?");
    $stmt->bind_param("sssi", $name, $email, $course, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Student record successfully updated!";
    } else {
        $_SESSION['error'] = "Error updating student: " . $conn->error;
    }
    header("Location: manage_students.php");
    exit();
}
if ($action === 'delete') {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Student record completely deleted.";
    } else {
        $_SESSION['error'] = "Error deleting student: " . $conn->error;
    }
    header("Location: manage_students.php");
    exit();
}
?>