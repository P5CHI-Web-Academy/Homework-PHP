<?php
class Args
{
	public static function bass(){
		$arrArgs = $_SERVER['argv'];
		$cArgs = count($_SERVER['argv']);

		if ($_SERVER['argc'] < 3 || $_SERVER['argc'] % 2 == 0) {
			echo "Missing arguments.";
			exit(0);
		}

		for ($i=1; $i < $cArgs; $i++) {
			if ($i % 2 == 1 && strtolower($arrArgs[$i]) == 'f' || strtolower($arrArgs[$i]) == 'm') {
				echo ($i == 1 ? 'Hello ': '').(strtolower($arrArgs[$i]) == 'f' ? 'Ms ' : (strtolower($arrArgs[$i]) == 'm' ? 'Mr ' : ''));
			}
			elseif ($i % 2 == 0) {
				echo (ucfirst($arrArgs[$i]).($i % 2 == 0 && $i > 1 ? ($i != ($cArgs - 1) ? ' and ' : '') : ''));
			}
			else{
				echo 'Unknown title.';
				exit(-1);
			}
		}
		echo '!';
    }
}

Args::bass();
?>