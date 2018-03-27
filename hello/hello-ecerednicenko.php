<?php

$finalArr = [];
$hello = "Hello ";

if ($argc % 2 == 0 || $argc < 3) {
    die("Missing arguments.\n");
}

foreach ($argv as $key => $item) {
    $argv[$key] = strtolower($item);
}


for($i = 1; $i < $argc; $i += 2) {
    switch ($argv[$i]) {
        case 'm':
            $argv[$i] = 'Mr';
            break;
        case 'f':
            $argv[$i] = 'Ms';
            break;
        default:
            die("Unknown title.\n");
    }

    $argv[$i + 1] = ucfirst($argv[$i + 1]);

    $finalArr[] = $argv[$i] . ' ' . $argv[$i + 1];
}

$hello .= implode(' and ', $finalArr) . "!\n";

echo $hello;

