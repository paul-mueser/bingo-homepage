<?php
header("Content-Type: application/json");

$DB_HOST = '{{DB_HOST}}';
$DB_USER = '{{DB_USER}}';
$DB_PASS = '{{DB_PASS}}';
$DB_NAME = '{{DB_NAME}}';

// Database connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Get request body
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'];
$password = $data['password'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert user into database
$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["message" => "User registered"]);
} else {
    echo json_encode(["error" => "Username already in use"]);
}

$stmt->close();
$conn->close();
?>
