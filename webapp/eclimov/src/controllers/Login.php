<?php

namespace Controller;

use Core\Controller;
use Service\Templater;

class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_POST['login'], $_POST['password'])) {
            $this->login();
        } else {
            unset($_SESSION['user_id']);
            echo Templater::process_template(
                'login',
                array(
                    'login_message' => 'Please, log in',
                    'color' => 'black',
                )
            );
        }
    }

    private function login(): void
    {
        if ($user_id = $this->m_user->authorize_user($_POST['login'], $_POST['password'])) {
            $_SESSION['user_id'] = $user_id;
            header('Location: /home');
        } else {
            echo Templater::process_template(
                'login',
                array(
                    'login_message' => 'Incorrect login or password!',
                    'color' => 'red',
                )
            );
        }

    }
}