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

// Table in this way (maybe x,y as point) (look if the text fits in the table)
//create table matrices_2d (
//    name varchar(20),
//    x int, 
//    y int, 
//    text varchar(20),
//    clicked boolean, 
//    primary key (name, x, y)
//);

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

$boardname = $data['boardname'];

// Fetch user from database
$stmt = $conn->prepare('SELECT bingoboards.x_row, bingoboards.y_col, bingoboards.eventid, bingoevent.event, bingoevent.amounthappened, bingoevent.amountneeded, bingoevent.amountbased, bingocategory.name AS "catagory", bingocategory.points FROM bingoboards LEFT JOIN bingoevent ON bingoboards.eventid = bingoevent.id LEFT JOIN bingocategory ON bingoevent.bingocategoryid = bingocategory.catagoryid WHERE bingoboards.name = ? ORDER BY bingoboards.y_col, bingoboards.x_row');
$stmt->bind_param("s", $boardname);
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