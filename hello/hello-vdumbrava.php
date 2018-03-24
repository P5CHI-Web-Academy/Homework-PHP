<?php

$ArrayNames = [];

if ($argc % 2 == 0 || $argc < 3) {
    die("Missing arguments.\n");
}

for ($i = 1; $i < $argc; $i += 2) {
    $argv[$i] = strtolower($argv[$i]);
    if ($argv[$i] === 'f') {
        $argv[$i] = 'Ms';
    } elseif ($argv[$i] === 'f') {
        $argv[$i] = 'Mr';
    } else {
        die("Unknown title.\n");
    }
    $argv[$i + 1] = ucfirst($argv[$i + 1]);
    $ArrayNames[] = $argv[$i] . ' ' . $argv[$i + 1];
}

 print 'Hello ' . implode(' and ', $ArrayNames) . "!\n";

