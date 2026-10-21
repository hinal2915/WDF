<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function uploadPoster($file) {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if ($file['size'] > $max_size) {
        $_SESSION['error'] = "Error: File size must be less than 2MB.";
        return false;
    }

    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error'] = "Error: Only JPG, PNG, and GIF files are allowed.";
        return false;
    }
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('poster_') . '.' . $ext;
    $destination = 'uploads/' . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $new_filename;
    } else {
        $_SESSION['error'] = "Error: Failed to move uploaded file.";
        return false;
    }
}
if ($action === 'create') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $date = trim($_POST['event_date']);
    
    $poster_name = uploadPoster($_FILES['poster']);
    
    if ($poster_name) {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, poster_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $desc, $date, $poster_name);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Event successfully added!";
        } else {
            $_SESSION['error'] = "Database error: " . $conn->error;
        }
    }
    header("Location: manage_events.php");
    exit();
}
if ($action === 'update') {
    $id = $_POST['event_id'];
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $date = trim($_POST['event_date']);
    $poster_name = $_POST['existing_poster'];

    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $new_poster = uploadPoster($_FILES['poster']);
        if ($new_poster) {
            $poster_name = $new_poster; 
        } else {
            header("Location: manage_events.php");
            exit(); 
        }
    }

    $stmt = $conn->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, poster_image = ? WHERE event_id = ?");
    $stmt->bind_param("ssssi", $title, $desc, $date, $poster_name, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Event successfully updated!";
    }
    header("Location: manage_events.php");
    exit();
}
if ($action === 'delete') {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT poster_image FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $file_path = 'uploads/' . $row['poster_image'];
        if (file_exists($file_path)) unlink($file_path); 
    }

    $stmt_del = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt_del->bind_param("i", $id);
    
    if ($stmt_del->execute()) {
        $_SESSION['message'] = "Event and poster completely deleted.";
    }
    header("Location: manage_events.php");
    exit();
}
?>