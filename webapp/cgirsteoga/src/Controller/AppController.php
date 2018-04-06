<?php

namespace Webapp\Controller;

use Webapp\Service\Security;
use Webapp\Service\UserReposInfoProvider;

class AppController extends AbstractController
{
    /**
     * @throws \Exception
     */
    public function index()
    {
        /** @var Security $security */
        $security = $this->services->get('security');

        if (!$security->isLoggedIn()) {
            $this->services->get('router')->redirect('/login');
        };

        $user = $security->getLoggedUser();

        /** @var UserReposInfoProvider $infoProvider */
        $infoProvider = $this->services->get('repos_info_provider');
        $profileLink = $infoProvider->getUserProfileLink($user->getUserName());

        $profileData = [];
        $repoData = [];
        $profile = false;
        if ($profileLink) {
            $profile = true;
            $profileData = [
                'link' => $profileLink,
                'linkTitle' => \sprintf('%s Profile', $user->getFullName()),
            ];
            $sortByField = $_GET['sort_by'] ?? '';
            $repoData = $infoProvider->getUserReposData($user->getUserName(), $sortByField);
        }

        $result = \array_merge(
            [
                'title' => \sprintf('Hello %s', $user->getFullName()),
                'fullName' => $user->getFullName(),
                'profile' => $profile,
                'repoData' => $repoData,
                'isLoggedIn' => true,
            ],
            $profileData
        );

        return $this->render('index.html', $result);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function login()
    {
        /** @var Security $security */
        $security = $this->services->get('security');
        if ($security->isLoggedIn()) {
            $this->services->get('router')->redirect('/');
        };

        $error = '';
        $userName = '';
        $title = 'User login';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userName = $_POST['user_name'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($security->login($userName, $password)) {
                $this->services->get('router')->redirect('/');
            } else {
                $error = 'Incorrect user/password';
                $title = $error;
            }
        }

        return $this->render(
            'login.html',
            [
                'user_name' => $userName,
                'error' => $error,
                'title' => $title,
                'profile' => '',
                'isLoggedIn' => false,
            ]
        );
    }

    /**
     * @throws \Exception
     */
    public function logout()
    {
        /** @var Security $security */
        $security = $this->services->get('security');

        $security->logout();

        $this->services->get('router')->redirect('/');
    }
}
