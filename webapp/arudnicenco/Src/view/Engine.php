<?php
namespace App\view;

class Engine
{
    private function __construct($tp){}

    static function rendering($tp, array $params): void
    {
        $content = \file_get_contents($tp);
        foreach ($params as $key => $value) {
            $content = str_replace('{{ '.$key.' }}', $value, $content);
        }
        echo($content);
    }
}
