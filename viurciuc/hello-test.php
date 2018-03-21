<?php

$userName = $_SERVER['argv'][1];

$testData = [
	[
		'in' => [],
		'out' => 'Missing arguments.',
	],
	[
		'in' => ['z'],
		'out' => 'Missing arguments.',
	],
	[
		'in' => ['m'],
		'out' => 'Missing arguments.',
	],
	[
		'in' => ['m', 'Test1', 'z'],
		'out' => 'Missing arguments.',
	],
	[
		'in' => ['m', 'Test1', 'f'],
		'out' => 'Missing arguments.',
	],
	[
		'in' => ['m', 'Test1'],
		'out' => 'Hello Mr Test1!',
	],
	[
		'in' => ['f', 'Test1'],
		'out' => 'Hello Ms Test1!',
	],
	[
		'in' => ['z', 'Test1'],
		'out' => 'Unknown title.',
	],
	[
		'in' => ['M', 'test1'],
		'out' => 'Hello Mr Test1!',
	],
	[
		'in' => ['F', 'test1', 'm', 'test2'],
		'out' => 'Hello Ms Test1 and Mr Test2!',
	],
	[
		'in' => ['f', 'test1', 'm', 'test2', 'f', 'test3', 'm', 'test4'],
		'out' => 'Hello Ms Test1 and Mr Test2 and Ms Test3 and Mr Test4!',
	],
];

function getTestResult($userName, $args, $expected)
{
	$out = [];
	exec('php hello-'.$userName.'.php '.$args, $out);
	
	if ($out[0] !== $expected) {
		return false;
	} else {
		return true;
	}
}

$failed = 0;
foreach ($testData as $data)
{
	$args = implode(' ', $data['in']);
	
	echo('Test "'.$args.'" => ');
	
	if (getTestResult($userName, $args, $data['out'])) {
		echo('OK');
	} else {
		echo('KO');
		$failed++;
	}
	
	echo("\n");
}

echo("\n\n".$failed.' of '.\count($testData).' tests failed.'."\n");
