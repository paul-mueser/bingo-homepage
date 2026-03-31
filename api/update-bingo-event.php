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

$eventid = $data['eventid'];
$increase = $data['increase'];

// Fetch user from database
$stmt = $conn->prepare("SELECT amounthappened FROM bingoevent WHERE EXISTS (SELECT 1 FROM participants WHERE participants.userid = ? AND participants.gameid = bingoevent.bingogameid) AND id = ?");
$stmt->bind_param("ii", $decoded->data->id, $eventid);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    http_response_code(403);
    echo json_encode(["error" => "User isn't participating in this bingo game or the event hasn't been found"]);
    $conn->close();
    exit();
}

$amountHappened = $data['amounthappened'];
if ($increase) {
    $amountHappened++;
} else {
    $amountHappened--;
}

$stmt = $conn->prepare("UPDATE bingoevent SET amounthappened = ? WHERE id = ?");
$stmt->bind_param("ii", $amountHappened, $eventid);

if($stmt->execute()) {
    $stmt->close();
    // Recalculate the points for the user and update the database
    $stmt = $conn->prepare("SELECT playerid FROM bingoboards WHERE eventid = ?");  
    $stmt->bind_param("i", $eventid);
    $stmt->execute();
    $result = $stmt->get_result();
    $playerids = [];
    while ($row = $result->fetch_assoc()) {
        $playerids[] = $row['playerid'];
    }
    $stmt->close();

    foreach ($playerids as $playerid) {
        $stmt = $conn->prepare("SELECT bingoboards.x_row, bingoboards.y_col, bingoevent.amounthappened >= bingoevent.amountneeded AS is_done, bingocategory.points FROM bingoboards LEFT JOIN bingoevent ON bingoboards.eventid = bingoevent.id LEFT JOIN bingocategory ON bingoevent.bingocategoryid = bingocategory.catagoryid WHERE bingoboards.playerid = ?");
        $stmt->bind_param("i", $playerid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Calculate the points for the player
        $points = 0;
        $bingoCard = [];
        while ($row = $result->fetch_assoc()) {
            $bingoCard[$row['x_row']][$row['y_col']] = $row;
        }

        $stmt->close();

        $diagonal1Done = true;
        $diagonal2Done = true;
        for ($i = 1; $i <= 5; $i++) {
            $rowDone = true;
            $colDone = true;
            for ($j = 1; $j <= 5; $j++) {
                if (!$bingoCard[$i][$j]['is_done']) {
                    $rowDone = false;
                } else {
                    $points += $bingoCard[$i][$j]['points'];
                }
                if (!$bingoCard[$j][$i]['is_done']) {
                    $colDone = false;
                }
            }

            if ($rowDone) {
                $points += 100;
            }
            if ($colDone) {
                $points += 100;
            }

            if (!$bingoCard[$i][$i]['is_done']) {
                $diagonal1Done = false;
            }

            if (!$bingoCard[$i][6 - $i]['is_done']) {
                $diagonal2Done = false;
            }
        }

        // Update the points in the database
        $stmt = $conn->prepare("UPDATE participants SET score = ? WHERE userid = ? AND gameid = (SELECT bingogameid FROM bingoevent WHERE id = ?)");
        $stmt->bind_param("iii", $points, $playerid, $eventid);
        $stmt->execute();
    }

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Record updated successfully and points recalculated'
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