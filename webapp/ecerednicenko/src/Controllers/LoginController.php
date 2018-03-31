<?php

namespace App\Controllers;

use App\Models\User;

/**
 * Class LoginController
 * @package Controllers
 */
class LoginController
{
    public function checkLoginData()
    {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $dataVerification = new User;

        if ($dataVerification->loginValidator($login, $password)) {
            $content = file_get_contents(dirname(__DIR__).'/Views/afterLogin/index.php');
            $content = preg_replace('/{login}/', $login, $content);
            echo $content;
            return;
        } else {
            $_SESSION['flash'] = 'Я вас не звал!';
            header('Location: /?status=error');
        }
    }

}