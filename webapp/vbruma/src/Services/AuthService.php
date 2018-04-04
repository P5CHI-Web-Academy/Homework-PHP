<?php

namespace App\Services;


use App\Model\UserRepository;

class AuthService
{
    /**
     * @var array User info
     */
    private $user = [];

    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return isset($_SESSION['username']);
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

        $userRepository = new UserRepository();
        $userInfo = $userRepository->get($request['username'], $request['password']);


        if ($userInfo !== null) {
            $this->user = $userInfo;
            $_SESSION['username'] = $this->user['username'];

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
        if (isset($_SESSION['username'])) {
            unset($_SESSION['username']);
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
