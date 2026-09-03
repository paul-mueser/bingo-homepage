<?php
// eventdata is an array of events with their corresponding category IDs
// cat_distribution is an integer representing the distribution of categories for the bingo game
function generateBingoEventList($eventdata, $cat_distribution) {
    $eventList = [];
    $divVal = 100000000;
    for ($i = 1; $i < 6; $i++) {
        $catCount = (int)($cat_distribution / $divVal);
        $eventList[$i] = [
            "count" => $catCount,
            "events" => []
        ];
        $cat_distribution %= $divVal;
        $divVal /= 100;
    }

    for ($i = 0; $i < count($eventdata); $i++) {
        $catId = $eventdata[$i]['bingocategoryid'];
        array_push($eventList[$catId]['events'], $eventdata[$i]['id']);
    }

    return $eventList;
}
?>