<?php

namespace App\Controller;

use App\Services\API\GitInterface;
use App\Services\AuthService;
use App\Services\Session\SessionAdapter;

class LoginController extends AbstractController
{
    /**
     * @var AuthService
     */
    private $auth;

    /**
     * @var GitInterface
     */
    private $gitService;

    /**
     * LoginController constructor.
     *
     * @param AuthService $auth
     * @param GitInterface $gitService
     * @param SessionAdapter $session
     */
    public function __construct(AuthService $auth, GitInterface $gitService, SessionAdapter $session)
    {
        parent::__construct($session);

        $this->auth = $auth;
        $this->gitService = $gitService;
    }

    public function index(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            if ($this->session->get('userGitLink') === null) {
                $this->session->set(
                    'userGitLink',
                    $this->gitService->getProfileLink($this->session->get('username'))
                );
            }

            $this->renderTemplate('main/index.php', [
                'gitLink' => $this->session->get('userGitLink'),
                'username' => $this->session->get('username')
            ]);

            return;
        }

        $this->renderTemplate('login/index.php', []);
    }

    public function login(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            $this->redirect('/');
        }

        if (! $this->auth->authenticate($request)) {
            $this->session->set('error', 'Username and/or password is invalid');

            $this->redirect('/');
        }

        $this->redirect('/');
    }

    public function logout(array $request = [])
    {
        $this->auth->logout();

        $this->redirect('/');
    }
}

