<?php
header("Content-Type: application/json");

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