<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 3/28/18
 * Time: 5:19 PM
 */

namespace Webapp;

use arhone\template\Template;

class Webapp
{
    public function run()
    {
        if (isset($_COOKIE['auth'])) {
            $this->hello($_COOKIE['auth']);
        } else {
            $this->showAuth();
        }
    }

    public function showAuth()
    {
        $Template = new Template();

        echo $Template->render(
            __DIR__.'/../views/tpls/form.tpl',
            [
            ]
        );
    }

    public function hello(string $name)
    {
        $Template = new Template();

        echo $Template->render(
            __DIR__.'/../views/tpls/hello.tpl',
            [
                'name' => $name,
            ]
        );
    }
}
