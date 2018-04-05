<?php

namespace Controller;

use Core\Controller;
use Service\Templater;

class Login extends Controller
{
    public function __construct(\Model\User $m_user, Templater $t_templater)
    {
        parent::__construct($m_user, $t_templater);

        if (isset($_POST['login'], $_POST['password'])) {
            $this->login($_POST['login'], $_POST['password']);
        } else {
            unset($_SESSION['user_id']);
            $variables = array(
                'login_message' => 'Please, log in',
                'color' => 'black',
            );
            $this->output_template($variables);
        }
    }

    private function login($login, $password): void
    {
        if ($user_id = $this->m_user->authorize_user($login, $password)) {
            $_SESSION['user_id'] = $user_id;
            header('Location: /home');
        } else {
            $variables = array(
                'login_message' => 'Incorrect login or password!',
                'color' => 'red',
            );
            $this->output_template($variables);
        }
    }

    public function output_template($variables){
        echo $this->t_templater->process_template('login', $variables);
    }
}