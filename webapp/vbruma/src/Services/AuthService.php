<?php

namespace App\Services;


use App\Model\User;

class AuthService
{

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

        $userRepository = new User();
        $user = $userRepository->getUser($request['username'], $request['password']);

        if ($user !== null) {
            $_SESSION['username'] = $request['username'];

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
}
