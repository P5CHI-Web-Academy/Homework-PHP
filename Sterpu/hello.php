<?php

if ($_SERVER['argc'] < 3 || $_SERVER['argc'] % 2 == 0) {
	die('Missing arguments.');
}

$result = 'Hello ';

for ($i = 1; $i < $_SERVER['argc']; $i += 2) {
	if ($_SERVER['argv'][$i] == 'm' || $_SERVER['argv'][$i] == 'M') {
		$result .= 'Mr ';
	} elseif ($_SERVER['argv'][$i] == 'f' || $_SERVER['argv'][$i] == 'F') {
		$result .= 'Ms ';
	} else {
		die("Incorrect order.");
	}

	$result .= ucfirst(strtolower($_SERVER['argv'][$i + 1])) . ' and ';
}

print(substr($result, 0, -5) . "!\n");