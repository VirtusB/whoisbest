<?php

function array_orderby() {
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row) {
                $tmp[$key] = $row[$field];
            }
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    array_multisort(...$args);
    return array_pop($args);
}

function evalPlayer(array $player) {
    return $player['total_matches'] + $player['total_kills'] - $player['total_deaths'] + $player['total_headshots'] + $player['total_mvps'] + $player['time_played'] * ($player['headshot_percent'] + $player['accuracy'] + $player['kd_ratio'] + $player['win_ratio']);
}


$players = [];

$playerOne = [
    'headshot_percent' => 50.34,
    'accuracy' => 19.82,
    'time_played' => 730,
    'total_kills' => 57516,
    'kd_ratio' => 1.41,
    'total_matches' => 1833,
    'total_deaths' => 40707,
    'total_headshots' => 28955,
    'win_ratio' => 60.66,
    'total_mvps' => 3790
];

$playerTwo = [
    'headshot_percent' => 42,
    'accuracy' => 18.2,
    'time_played' => 1852,
    'total_kills' => 111360,
    'kd_ratio' => 1.07,
    'total_matches' => 4524,
    'total_deaths' => 103774,
    'total_headshots' => 46800,
    'win_ratio' => 50.61,
    'total_mvps' => 10583
];



array_push($players, $playerOne, $playerTwo);

$playersWithScore = [];

foreach ($players as $player) {
    $score = evalPlayer($player);
    $player['score'] = $score;
    $playersWithScore[] = $player;
}

$playersWithScore = array_orderby($playersWithScore, 'score', SORT_DESC);

echo '<pre>';

print_r($playersWithScore);

echo '</pre>';