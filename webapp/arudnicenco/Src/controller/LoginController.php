<?php

namespace App\controller;

use App\view;
use App\service;

class LoginController
{

    private $view_path = __DIR__.'/../../Src/view/';
    private $aut;

    private function makeRedirect($path)
    {
        header("Location: http://".$path);
        ob_end_flush();

    }

    function containIndex()
    {
        if ($this->isAuth()) {
            include $this->view_path.'/../../main/index.php';
        }
        include $this->view_path.'/../../login/index.php';
    }

    function makeLogin($req)
    {
        $host = $_SERVER['HTTP_HOST'];

        if ($this->aut->isAuth()) {
            $this->makeRedirect($host);
        } elseif ($this->aut->makeAuth($req) != true) {
            $_SESSION['error'] = 'Username and(or) password is invalid!!!';
            $this->makeRedirect($host);
        }
        $this->makeRedirect($host);
    }

    function __construct(ServiceAut $aut)
    {
        $this->aut = $aut;
    }

    function makeLogout()
    {
        $host = $_SERVER['HTTP_HOST'];

        $this->aut->makeLogout();
        $this->makeRedirect($host);
    }
}