<?php

$b = $_SERVER['argv'];

$array_count = count($argv);

if ($array_count%2 !== 1 || $array_count == 1) die("Missing arguments" . PHP_EOL);

echo "Hello ";

for ($i=1; $i<$array_count-1; $i+=2) {

    $j=$i;

    if ($argv[$i] == 'm' || $argv[$i] == 'M') {

        $sex = 'Mr.';
    }
    elseif ($argv[$i] == 'f' || $argv[$i] == 'F') {

        $sex = 'Ms.';
    }
    else echo die ("Unknown title!" . PHP_EOL);

    if ($array_count>3 && $i < $array_count-2){

        $and = " and ";

    } else $and = "";


    echo $sex . " ";

    echo ucfirst($argv[$j+1]);

    echo $and;
}

echo "!" . PHP_EOL;
