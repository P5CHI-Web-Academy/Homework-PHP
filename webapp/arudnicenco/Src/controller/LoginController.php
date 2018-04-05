<?php

namespace App\сontroller;

use App\service\ServiceForAuthorization;
use App\service\api\GitInterface;

class LoginController
{
    private $viewPath = __DIR__ . '/../../Src/view/';
    private function redirect(string $path)
    {
        header('Location: http://'.$path);
        ob_end_flush();
        die();
    }

    private $auth;
    private $gitService;

    function __construct(ServiceForAuthorization $auth, GitInterface $gitService)
    {
        $this->auth = $auth;
        $this->gitService = $gitService;
    }

    function index()
    {
        if ($this->auth->isAuth()) {
            $_SESSION['userGitLink'] =
                $_SESSION['userGitLink'] ?? $this->gitService->getProfileLink($_SESSION['username']);

            include $this->viewPath . '/main/index.php';

            return;
        }

        include $this->viewPath . '/login/index.php';
    }

    function login(array $request = [])
    {
        if ($this->auth->isAuth()) {
            $this->redirect($_SERVER['HTTP_HOST']);
        }
        if (! $this->auth->makeAuth($request)) {
            $_SESSION['error'] = 'Username and/or password is invalid';
            $this->redirect($_SERVER['HTTP_HOST']);
        }

        $this->redirect($_SERVER['HTTP_HOST']);
    }

    function logout()
    {
        $this->auth->logout();
        $this->redirect($_SERVER['HTTP_HOST']);
    }
}