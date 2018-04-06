<?php

namespace App\Controllers;

use App\Models\User;
use App\Components\Auth;
/**
 * Class LoginController
 * @package Controllers
 */
class LoginController
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function index()
    {
        if (empty($_SESSION['auth'])) {
            require dirname(__DIR__).'/Views/beforeLogin/index.php';
        } else {
            $content = file_get_contents(dirname(__DIR__).'/Views/afterLogin/index.php');
            $content = preg_replace('/{login}/', $_SESSION['name'], $content);
            $linksContainer = '';
            if (isset($_SESSION['githubProfile'])) {
                $linksContainer = file_get_contents(dirname(__DIR__).'/Views/partials/github-link.php');
                $linksContainer = preg_replace('/{link}/', $_SESSION['githubProfile'], $linksContainer);
            }
            $content = preg_replace('/{linksContainer}/', $linksContainer, $content);
            echo $content;
        }
    }

    public function postLogin()
    {

        $login = $_POST['login'];
        $password = $_POST['password'];

        $dataVerification = new User;

        if ($dataVerification->loginValidator($login, $password)) {

            $this->auth->login($login);
            $this->auth->findGitHubProfile($login);

            header("Location: /");
        } else {
            header('Location: /?status=error');
        }
    }

    public function logout()
    {
        $this->auth->logout();
        header('Location: /');
    }

}