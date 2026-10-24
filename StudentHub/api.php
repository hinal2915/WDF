<?php
header("Content-Type: application/json");
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $students]);
        break;

    case 'POST':
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $course = trim($input['course'] ?? '');

        if (empty($name) || empty($email) || empty($course)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO students (name, email, course) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $course);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Student added successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add student.']);
        }
        break;

    case 'PUT':
        $id = $input['id'] ?? 0;
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $course = trim($input['course'] ?? '');

        if (empty($id) || empty($name) || empty($email) || empty($course)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required for update.']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, course = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $course, $id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Student updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update student.']);
        }
        break;

    case 'DELETE':
        $id = $input['id'] ?? 0;
        
        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Student ID is required.']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Student deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete student.']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        break;
}
$conn->close();
?>