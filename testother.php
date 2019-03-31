<?php

$scores = [626262900, 296169083, 58726625, 56531119, 39371535, 20774915, 10270462, 6067412];


function getPercentile($array, $percentile)
{
    $percentile = min(100, max(0, $percentile));
    $array = array_values($array);
    sort($array);
    $index = ($percentile / 100) * (count($array) - 1);
    $fractionPart = $index - floor($index);
    $intPart = floor($index);

    $percentile = $array[$intPart];
    $percentile += ($fractionPart > 0) ? $fractionPart * ($array[$intPart + 1] - $array[$intPart]) : 0;

    return $percentile;
}

$p75 = getPercentile($scores, 99);
echo "Percentile: $p75<br><br><br>";

$percentiles = [0.1, 0.25, 0.5, 0.75, 0.9, 0.95, 0.99];



foreach ($scores as $score) {
    echo "Score: $score<br>";
    echo 'Is in 75th: ', $score >= $p75 === true ? 'true' : 'false';
    echo '<br><br>';
}