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

// Fetch user from database
$stmt = $conn->prepare("SELECT * FROM bingogame WHERE EXISTS (SELECT 1 FROM participants WHERE participants.userid = ? AND participants.gameid = bingogame.gameid) ORDER BY gameid");
$stmt->bind_param("i", $decoded->data->id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'data'   => $data
]);

$stmt->close();
$conn->close();
?>