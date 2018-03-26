<?php

if ($_SERVER['argc'] % 2 == 0 || $_SERVER['argc'] == 1) {
	die('Missing arguments.');
}

$result = 'Hello ';

for ($i = 1; $i <= $_SERVER['argc'] - 1; $i += 2) {
	
	if ($_SERVER['argv'][$i] == 'm' || $_SERVER['argv'][$i] == 'M') {
		$result .= 'Mr '. \ucfirst(\strtolower($_SERVER['argv'][$i + 1]));
	} elseif ($_SERVER['argv'][$i] == 'f' || $_SERVER['argv'][$i] == 'F') {
		$result .= 'Ms '. \ucfirst(\strtolower($_SERVER['argv'][$i + 1]));
	} else {
		die("Unknown title.");
	}

	if ($i < $_SERVER['argc'] - 2) {
		$result .= ' and ';
	}
}

echo $result . "!\n";