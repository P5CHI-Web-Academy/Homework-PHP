<?php

if ($_SERVER['argc'] % 2 == 0 || $_SERVER['argc'] == 1) {
    echo 'Missing arguments.';
    exit();
}

$titlesMap = [
    'm' => 'Mr',
    'f' => 'Ms',
];

$params = $_SERVER['argv'];
\array_shift($params);
$nr = \count($params);

$result = [];
for ($i = 0; $i < $nr; $i += 2) {
    $titleType = \strtolower($params[$i]);
    $name = \ucfirst(\strtolower($params[$i + 1]));

    if (!isset($titlesMap[$titleType])) {
        die('Unknown title.');
    }

    $result[] = \sprintf('%s %s', $titlesMap[$titleType], $name);
}

echo 'Hello '.\join(' and ', $result).'!';