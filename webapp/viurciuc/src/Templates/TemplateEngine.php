<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 4/5/18
 * Time: 8:23 PM
 */

namespace App\Templates;

class TemplateEngine
{

    private function __construct($tp)
    {
    }

    /**
     * @param       $tp
     * @param array $params
     */
    public static function render($tp, array $params): void
    {
        $content = \file_get_contents($tp);
        foreach ($params as $key => $value) {
            $content = str_replace('{{ '.$key.' }}', $value, $content);
        }
        echo($content);
    }
}