<?php

class MisterOrMistress {

    function checkParity() {
        if ($_SERVER['argc'] == 1 || $_SERVER['argc'] % 2 == 0) {
            echo "Missing arguments.";
            return false;
        } else {
            return true;
        }
    }

    function sayHello() {

        $male = ["m", "M"];
        $female = ["f", "F"];

        for ($i = 1; $i <= $_SERVER['argc'] - 1; $i += 2) {
            if ($_SERVER['argv'][$i] == $male[0] or $_SERVER['argv'][$i] == $male[1]) {
                echo "Hello Ms " . ucfirst($_SERVER['argv'][$i + 1]);

            } elseif ($_SERVER['argv'][$i] == $female[0] or $_SERVER['argv'][$i] == $female[1]) {
                echo "Hello Mr " . ucfirst($_SERVER['argv'][$i + 1]);

            } else
                echo "Unknown title.";
        }
    }
}

$greeting = new MisterOrMistress();

if ($greeting->checkParity()) {
    $greeting->sayHello();
}
