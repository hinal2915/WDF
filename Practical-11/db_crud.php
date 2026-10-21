<?php
$conn = new mysqli('localhost', 'root', '', 'studenthub_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>