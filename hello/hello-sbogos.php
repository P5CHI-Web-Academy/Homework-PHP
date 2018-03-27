<?php

if ($_SERVER['argc'] < 3 || !($_SERVER['argc'] & 1)) {
	print 'Missing arguments!'; exit();
}

$result = $_SERVER['argv'];
$count = $_SERVER['argc'];

for($i = 1; $i < $count; $i++) {

	if ($i % 2 == 1) {

		if (strtolower($result[$i]) != 'f' && strtolower($result[$i]) != 'm')  {

			print 'Uknown title!'; exit();
		}
	}
}

print 'Hello ';

for($i = 1; $i < $count; $i++) {

	if ($i % 2 == 1) {

		print strtolower($result[$i]) == 'f' ? 'Ms. ' : 'Mr. ';
	} else {

		print ucfirst($result[$i]);

		if ($i != $count-1) {

			print ' and ';
		}
	}
}

print '!';
