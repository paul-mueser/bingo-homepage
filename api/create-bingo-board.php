<?php
header("Content-Type: multipart/form-data");
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

$gameid = isset($_POST['gameid']) ? intval($_POST['gameid']) : -1;

if ($gameid <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid game ID"]);
    exit();
}

$playerid = isset($_POST['playerid']) ? intval($_POST['playerid']) : -1;

if ($playerid <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid player ID"]);
    exit();
}

if (!isset($_FILES['files']) || $_FILES['files']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(["error" => "No events file uploaded"]);
    exit();
}

$stmt = $conn->prepare("SELECT * FROM bingogame WHERE gameid = ?");
$stmt->bind_param("i", $gameid);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all();
$stmt->close();

if (count($data) === 0) {
    http_response_code(409);
    echo json_encode(["error" => "Game does not exist"]);
    exit();
}

$handle = fopen($_FILES['files']['tmp_name'], 'r');
if ($handle === false) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to open uploaded file"]);
    exit();
}

while (($row = fgetcsv($handle)) !== false) {
    $x_row = $row[0];
    $y_col = $row[1];
    $eventid = $row[2];

    $stmt = $conn->prepare("INSERT INTO `bingoboards` (`playerid`, `x_row`, `y_col`, `eventid`, `bingogameid`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiii", $playerid, $x_row, $y_col, $eventid, $gameid);
    $stmt->execute();
    $stmt->close();
}

http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Board uploaded successfully'
]);

fclose($handle);

$conn->close();
?>