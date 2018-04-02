<?php
/**
 * Copyright (c) 2018.
 *
 * @author        Alexander Sterpu <alexander.sterpu@gmail.com>
 *
 */

namespace WebApp\Controller;

use Aura\Session\Session;
use Twig_Environment;
use WebApp\Model\UserRepository;

/**
 * Class AuthController
 * @package WebApp\Controller
 */
class AuthController
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Session
     */
    private $session;

    /**
     * AuthController constructor.
     * @param UserRepository $repository
     * @param Twig_Environment $twig
     * @param Session $session
     */
    public function __construct(UserRepository $repository, Twig_Environment $twig, Session $session)
    {
        $this->repository = $repository;
        $this->twig = $twig;
        $this->session = $session->getSegment("WebApp");
    }

    /**
     *  Login endpoint
     */
    public function login()
    {
        $loginError = false;
        if (isset($_POST['name']) && isset($_POST['password'])) {
            $user = $this->repository->getUserByNameAndPassword($_POST['name'], $_POST['password']);

            if ($user) {
                $this->session->set('user_id', $user->getId());
                $this->session->set('user_name', $user->getName());
                $this->session->set('user_email', $user->getEmail());
                header('Location: /');
                die();
            } else {
                $loginError = 'Неправильный логин или пароль';
            }
        }

        echo $this->twig->render('login.twig', ['loginError' => $loginError]);
    }
}
