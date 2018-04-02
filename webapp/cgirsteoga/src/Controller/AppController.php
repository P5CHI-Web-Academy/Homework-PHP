<?php

namespace Webapp\Controller;

use Webapp\Model\Security;

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

        $profileLink = $this->services->get('github_client')
                ->getProfileLink($user->getUserName()) ?? '';

        $profile = '';
        if ($profileLink) {
            $profile = $this->renderPartial(
                'profile.html',
                [
                    'link' => $profileLink,
                    'linkTitle' => sprintf('%s Profile', $user->getFullName()),
                ]
            );
        }

        $result = [
            'title' => sprintf('Hello %s', $user->getFullName()),
            'fullName' => $user->getFullName(),
            'profile' => $profile,
        ];

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
