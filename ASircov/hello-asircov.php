<?php

if ($_SERVER['argc'] < 3 || $_SERVER['argc'] % 2 == 0)  {
	echo "Missing arguments.";
	exit;
}

for ($a = 1; $a < $_SERVER['argc']; $a+=2) {

	switch ($_SERVER['argv'][$a]) {
		case 'm':
			if ($a == 1) {
				echo "Hello ";
			}

				echo "Mr ";
				break;

		case 'M':
			if ($a == 1) {
				echo "Hello ";
			}

				echo "Mr ";
				break;

		case 'f':
			if ($a == 1) {
				echo "Hello ";
			}

				echo "Ms ";
				break;

		case 'F':
			if ($a == 1) {
				echo "Hello ";
			}

				echo "Ms ";
				break;

		default:
			echo "Unknown title.";
			exit;
	}

echo ucfirst(strtolower($_SERVER['argv'][$a + 1]) ) ;

	if ($a+2 != ($_SERVER['argc'])) {
		echo " and ";
	}
	else
		echo "!";
	
}
?>
