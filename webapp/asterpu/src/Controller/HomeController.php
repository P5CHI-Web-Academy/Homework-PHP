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


/**
 * Class HomeController
 * @package WebApp\Controller
 */
class HomeController
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * HomeController constructor.
     * @param Twig_Environment $twig
     * @param Session $session
     */
    public function __construct(Twig_Environment $twig, Session $session)
    {
        $this->twig = $twig;
        $this->session = $session->getSegment("WebApp");
    }

    /**
     * Index page epoint
     */
    public function index()
    {
        if ($this->session->get('user_name', false)) {
            echo $this->twig->render('welcome.twig', ['user_name' => $this->session->get('user_name')]);
        } else {
            echo $this->twig->render('login.twig');
        }
    }

    /**
     * 404 endpoint
     */
    public function show404()
    {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        echo $this->twig->render('404.twig');
    }
}
