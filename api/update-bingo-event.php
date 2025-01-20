<?php
header("Content-Type: application/json");

require './vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$secret_key = "{{JWT_SECRET}}";

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

$DB_HOST = '{{DB_HOST}}';
$DB_USER = '{{DB_USER}}';
$DB_PASS = '{{DB_PASS}}';
$DB_NAME = '{{DB_NAME}}';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed"]));
}

$data = json_decode(file_get_contents("php://input"), true);

$eventid = $data['eventid'];
$increase = $data['increase'];

// Fetch user from database
$stmt = $conn->prepare("SELECT amounthappened FROM bingoevent WHERE id = ?");
$stmt->bind_param("i", $eventid);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

$amountHappened = $data['amounthappened'];
if ($increase) {
    $amountHappened++;
} else {
    $amountHappened--;
}

$stmt = $conn->prepare("UPDATE bingoevent SET amounthappened = ? WHERE id = ?");
$stmt->bind_param("ii", $amountHappened, $eventid);

if($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Record updated successfully'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error updating record'
    ]);
}

$conn->close();
?>