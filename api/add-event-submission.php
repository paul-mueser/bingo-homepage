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

$event = $data['event'];
$amountBased = $data['amountBased'];

$stmt = $conn->prepare("SELECT * FROM eventsubmissions WHERE event = ?");
$stmt->bind_param("s", $event);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(["error" => "Event already submitted"]);
    $conn->close();
    exit();
}

$stmt = $conn->prepare("INSERT INTO eventsubmissions (event, amountbased) VALUES (?, ?)");
$stmt->bind_param("si", $event, $amountBased);
$stmt->execute();
$stmt->close();

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Event successfully submitted'
]);

$conn->close();
?>