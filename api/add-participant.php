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

$gameid = $data['gameid'];
$userid = $data['userid'];


$stmt = $conn->prepare("INSERT INTO participants (gameid, userid) VALUES (?, ?)");
$stmt->bind_param("ii", $gameid, $userid);
$stmt->execute();

$stmt->close();

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Participant added successfully'
]);

$conn->close();
?>