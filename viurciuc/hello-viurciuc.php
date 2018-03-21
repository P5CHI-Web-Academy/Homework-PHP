<?php

if ($_SERVER['argc'] % 2 == 0) {
	echo 'Missing arguments.';
	exit(0);
}

if ($_SERVER['argc'] == 1) {
	echo 'Missing arguments.';
	exit(0); 
}

for ($i = 1; $i < $_SERVER['argc']; $i += 2) {
	if ($_SERVER['argv'][$i] != 'm' && $_SERVER['argv'][$i] != 'M' && $_SERVER['argv'][$i] != 'f' && $_SERVER['argv'][$i] != 'F') {
		echo "Unknown title.";
		exit(0);
	}
}

echo 'Hello ';

for ($i = 1; $i <= $_SERVER['argc'] - 1; $i += 2) {
	
	if ($_SERVER['argv'][$i] == 'm' || $_SERVER['argv'][$i] == 'M') {
		echo 'Mr '. \ucfirst(\strtolower($_SERVER['argv'][$i + 1]));
	} elseif ($_SERVER['argv'][$i] == 'f' || $_SERVER['argv'][$i] == 'F') {
		echo 'Ms '. \ucfirst(\strtolower($_SERVER['argv'][$i + 1]));
	}

	if ($i < $_SERVER['argc'] - 2) {
		echo ' and ';
	}
}

echo "!\n";