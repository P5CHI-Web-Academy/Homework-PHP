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
                $repositories = $github_api->get_user_repositories($user_info['login']);
                $variables['repos_count'] = \count($repositories);
                foreach ($repositories as $repository){
                    $variables['repos'][] = array(
                        'name' => $repository['name'],
                        'updated_at' => date('Y-m-d', strtotime($repository['updated_at'])),
                        'html_url' => $repository['html_url'],
                        'commits' => \count(
                            $github_api->get_repository_commits($user_info['login'], $repository['name'])
                        )
                    );
                }
                if(isset($_GET['order_by'])){
                    $order_by = $_GET['order_by'];
                    usort($variables['repos'], function ($item1, $item2) use ($order_by){
                        return strtolower($item1[$order_by]) <=> strtolower($item2[$order_by]);
                    });
                }
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