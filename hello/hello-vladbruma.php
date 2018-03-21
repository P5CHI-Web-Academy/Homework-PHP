<?php

// declarations

$parameterCount = $_SERVER['argc'] - 1; // ignore function name as parameter count
$parameterArray = $_SERVER['argv'];
$scriptName     = array_shift($parameterArray);

$appendUsageMessage = function ($message, $scriptName) {
    return $message . " (Example usage: php $scriptName f joan)\n";
};

$titleIsValid = function($title) {
    $acceptableTitles = ['f', 'm'];

    return in_array(strtolower($title), $acceptableTitles);
};

$filterTitle = function($title) {
    $shortToFullArr = [
        'f' => 'Ms',
        'm' => 'Mr'
    ];

    $title = strtolower($title);

    return isset($shortToFullArr[$title]) ? $shortToFullArr[$title] : $title;
};

$filterName = function($name) {
    return ucfirst(strtolower($name));
};


// main logic

// check input count
if ( $parameterCount < 2 || $parameterCount % 2 == 1) {
    echo $appendUsageMessage('Invalid number of arguments supplied', $scriptName);
    exit(0);
}


// iterate and combine title/name pairs
$namesArr = array();
for ( $i = 0; $i < $parameterCount; $i+= 2 ) {
    $title = $parameterArray[$i];
    $name  = $parameterArray[$i + 1];

    // validate
    if (! $titleIsValid($title)) {
        echo $appendUsageMessage("Invalid title: '$title'", $scriptName);
        exit(0);
    }

    // filter and combine a pair
    $namesArr[] = $filterTitle($title) . ' ' . $filterName($name);
}

// combine pairs and display output
echo "\nHello " . implode(' and ', $namesArr) . "!\n\n";


