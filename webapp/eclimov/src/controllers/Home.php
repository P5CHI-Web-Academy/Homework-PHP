<?php

namespace Controller;

use Core\Controller;
use Service\Templater;


class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (isset($_SESSION['user_id'])) {
            $user_info = $this->m_user->get_user_info($_SESSION['user_id']);
            echo Templater::process_template(
                'home',
                array('user_name' => $user_info['name'])
            );
        } else {
            header('Location: /login');
        }
    }
}