<?php

namespace Webapp;

class Webapp
{
    public function hello(string $name)
    {
        echo('Hello '.$name.'!');

        return true;
    }

    //example of template rendering
    public function renderTemplate(string $fileName, array $vars = []): void
    {
        //1. load template contents as string
        $content = \file_get_contents($fileName);

        //2. replace {{ varname }} placeholders with real data
        foreach ($vars as $key => $value) {
            $content = str_replace('{{ '.$key.' }}', $value, $content);
        }

        //3. output processed template
        echo($content);
    }
}
