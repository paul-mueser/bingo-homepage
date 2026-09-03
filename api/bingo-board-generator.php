<?php
include 'bingo-eventlist-generator.php';

function generateBingoBoard($gameid) {
    include 'secrets.php';
    $board = [];

    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(["error" => "Database connection failed"]));
    }

    $stmt = $conn->prepare("SELECT id, bingocategoryid FROM bingoevent WHERE bingogameid = ?");
    $stmt->bind_param("i", $gameid);
    $stmt->execute();
    $result = $stmt->get_result();
    $eventdata = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (!$eventdata) {
        http_response_code(404);
        echo json_encode(["error" => "No events found for this game"]);
        $conn->close();
        exit();
    }

    $stmt = $conn->prepare("SELECT cat_distribution FROM bingogame WHERE gameid = ?");
    $stmt->bind_param("i", $gameid);
    $stmt->execute();
    $result = $stmt->get_result();
    $cat_distribution = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if (!$cat_distribution) {
        http_response_code(404);
        echo json_encode(["error" => "No category distribution found for this game"]);
        exit();
    }

    if (!isset($cat_distribution['cat_distribution'])) {
        http_response_code(500);
        echo json_encode(["error" => "Category distribution is not set for this game"]);
        exit();
    }

    $cat_distribution = json_decode($cat_distribution['cat_distribution'], true);

    $categories = generateBingoEventList($eventdata, $cat_distribution);

    for ($i = 1; $i < 6; $i++) {
        if (!isset($categories[$i])) {
            http_response_code(500);
            echo json_encode(["error" => "Category $i is not set for this game"]);
            exit();
        }

        if (!isset($categories[$i]['events']) || count($categories[$i]['events']) < $categories[$i]['count']) {
            http_response_code(500);
            echo json_encode(["error" => "Not enough events for category $i"]);
            exit();
        }
    }

    $selectedEvents = [];

    for ($i = 1; $i < 6; $i++) {
        $events = $categories[$i]['events'];
        shuffle($events);
        $selectedEvents = array_merge($selectedEvents, array_slice($events, 0, $categories[$i]['count']));
    }

    shuffle($selectedEvents);

    $bingoBoard = array_chunk($selectedEvents, 5);

    return $bingoBoard;
}
?>