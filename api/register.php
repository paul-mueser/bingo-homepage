<?php
header("Content-Type: application/json");

$DB_HOST = '{{DB_HOST}}';
$DB_USER = '{{DB_USER}}';
$DB_PASS = '{{DB_PASS}}';
$DB_NAME = '{{DB_NAME}}';
$CORRECT_AUTH_CODE = '{{CORRECT_AUTH_CODE}}';

// Database connection
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}

// Get request body
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'];
$password = $data['password'];
$authCode = $data['authCode'];

if ($authCode !== $CORRECT_AUTH_CODE) {
    http_response_code(401);
    die(json_encode(["error" => "Invalid auth code"]));
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Insert user into database
$stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(["message" => "User registered"]);
} else {
    http_response_code(409);
    echo json_encode(["error" => "Username already in use"]);
}

$stmt->close();
$conn->close();
?>
