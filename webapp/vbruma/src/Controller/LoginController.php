<?php

namespace App\Controller;

use App\Services\API\AbstractGit;
use App\Services\API\GitInterface;
use App\Services\AuthService;
use App\Services\Session\SessionAdapter;
use App\Services\TemplateParser;

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
     * @param AbstractGit $gitService
     * @param SessionAdapter $session
     * @param TemplateParser $templateParser
     */
    public function __construct(AuthService $auth, AbstractGit $gitService,
        SessionAdapter $session, TemplateParser $templateParser)
    {
        parent::__construct($session, $templateParser);

        $this->auth = $auth;
        $this->gitService = $gitService;
    }

    /**
     * @param array $request
     * @throws \Exception
     */
    public function index(array $request = [])
    {
        if ($this->auth->isAuthenticated()) {
            if ($this->session->get('userGitLink') === null) {
                $this->session->set(
                    'userGitLink',
                    $this->gitService->getProfileLink($this->session->get('username'))
                );

                $this->session->set(
                    'userRepos',
                    $this->gitService->getRepositoriesWithCommitCount($this->session->get('username'))
                );
            }

            // sort if specified
            $repositoryInfo = $this->session->get('userRepos');

            if (isset($request['orderBy'])) {
                usort ($repositoryInfo, function($a, $b) use ($request) {
                    if ($a[$request['orderBy']] == $b[$request['orderBy']]) {
                        return 0;
                    }

                    return ($a[$request['orderBy']] < $b[$request['orderBy']]) ? -1 : 1;
                } );
            }

            $this->renderTemplate('main/index.php', [
                'gitLink' => $this->session->get('userGitLink'),
                'username' => $this->session->get('username'),
                'gitRepoInfo' => $repositoryInfo
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

