<?php
$in=$_SERVER['argv'];
$count=$_SERVER['argc'];
$out = 'Hello ';
if ($count% 2 == 0 || $count == 1) {
print 'Missing arguments!';
exit();
}
for ($i = 1; $i <= $count - 1; $i += 2) {
    $name = ucfirst(strtolower($in[$i + 1]));
if ($in[$i] == 'm' || $in[$i] == 'M') {
$out = $out. 'Mr '. $name;
} elseif ($in[$i] == 'f' || $in[$i] == 'F') {
$out = $out. 'Ms '. $name;
} else {
print 'Unknown title!';
exit();
}
$out = $out. ' and ';
}
echo $out . '!';