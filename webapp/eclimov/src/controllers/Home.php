<?php

namespace Controller;

use Core\Controller;
use Service\Templater;
use Service\Github;

class Home extends Controller
{
    public function __construct(\Model\User $m_user, Templater $t_templater, Github $github_api)
    {
        parent::__construct($m_user, $t_templater);

        if (isset($_SESSION['user_id'])) {
            $user_info = $this->m_user->get_user_info($_SESSION['user_id']);
            $variables = array('user_name'=>$user_info['name']);

            if($github_user=$github_api->get_user($user_info['login'])){
                $variables['github_profile_url'] = $github_user['url'];
            }

            $this->output_template($variables);
        } else {
            header('Location: /login');
        }
    }

    public function output_template($variables){
        echo $this->t_templater->process_template('home', $variables);
    }
}