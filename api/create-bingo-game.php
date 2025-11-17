<?php
header("Content-Type: application/json");
include 'secrets.php';

require './vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

if (!isset($_COOKIE['token'])) {
    http_response_code(401);
    echo json_encode(["error" => "No token provided"]);
    exit();
}

$jwt = $_COOKIE['token'];

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    if ($decoded->data->isAdmin !== 1) {
        http_response_code(403);
        echo json_encode(["error" => "Insufficient permissions"]);
        exit();
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid token"]);
    exit();
}

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];

// Fetch user from database
$stmt = $conn->prepare("SELECT * FROM bingogame WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all();
$stmt->close();

if (count($data) > 0) {
    http_response_code(409);
    echo json_encode(["error" => "Game with this name already exists"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO bingogame (name) VALUES (?)");
$stmt->bind_param("s", $name);

if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Game created successfully '
    ]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to create game"]);
    
}

$stmt->close();
$conn->close();
?>