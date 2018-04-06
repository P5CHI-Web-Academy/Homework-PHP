<?php

namespace App\Services;


use App\Model\UserRepository;
use App\Services\Session\SessionAdapter;

class AuthService
{
    /**
     * @var array User info
     */
    private $user = [];

    /**
     * @var SessionAdapter
     */
    private $session;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AuthService constructor.
     *
     * @param SessionAdapter $session
     * @param UserRepository $userRepository
     */
    public function __construct(SessionAdapter $session, UserRepository $userRepository)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return ($this->session->get('username') !== null);
    }

    /**
     * Authenticate user by username/password pair
     *
     * @param array $request
     * @return bool
     */
    public function authenticate(array $request = []): bool
    {
        if (! isset($request['username']) || empty($request['username'])
        || ! isset($request['password']) || empty($request['password'])) {
            return false;
        }

        $userInfo = $this->userRepository->get($request['username'], $request['password']);

        if ($userInfo !== null) {
            $this->user = $userInfo;
            $this->session->set('username', $this->user['username']);

            return true;
        }

        return false;
    }

    /**
     * Logout user
     *
     * @return bool
     */
    public function logout(): bool
    {
        if ($this->session->get('username') !== null) {
            $this->session->set('username', null);
        }

        return true;
    }

    /**
     * @return array
     */
    public function user()
    {
        return $this->user;
    }
}
