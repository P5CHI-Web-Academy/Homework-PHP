<?php

namespace App\Controllers;

use App\Models\User;

/**
 * Class LoginController
 * @package Controllers
 */
class LoginController
{
    public function postLogin()
    {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $dataVerification = new User;

        if ($dataVerification->loginValidator($login, $password)) {

            $_SESSION['name'] = $login;
            $_SESSION['auth'] = 'Success';

            header("Location: /");
        } else {
            header('Location: /?status=error');
        }
    }


    public function logout()
    {
        if (!empty($_SESSION)) {
            unset($_SESSION['auth']);
            unset($_SESSION['name']);
        }
        header('Location: /');
    }

}