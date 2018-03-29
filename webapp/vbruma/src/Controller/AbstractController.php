<?php

namespace App\Controller;

abstract class AbstractController
{
    protected $templatePath = __DIR__.'/../../src/templates/';

    protected function redirect(string $path)
    {
        header('Location: http://' . $path);
        ob_end_flush();
        die();
    }
}
