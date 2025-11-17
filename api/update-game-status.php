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

$gameid = $data['gameid'];
$status = $data['status'];


$stmt = $conn->prepare("SELECT * FROM bingogame WHERE gameid = ?");
$stmt->bind_param("s", $gameid);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all();
$stmt->close();

if (count($data) == 0) {
    http_response_code(409);
    echo json_encode(["error" => "Game does not exist"]);
    exit();
}

$stmt = $conn->prepare("UPDATE bingogame SET status = ? WHERE gameid = ?");
$stmt->bind_param("ii", $status, $gameid);

if($stmt->execute()) {
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Record updated successfully '
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error updating record'
    ]);
}

$stmt->close();
$conn->close();
?>