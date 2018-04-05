<?php

namespace App\service;

use App\model\Client;

class ServiceForAuthorization
{
    function isAuth()
    {
        return isset($_SESSION['client_name']);
    }

    function makeAuth($req)
    {
        if (!isset($req['client_name']) || !isset($req['client_password'])) {
            return false;
        }

        $clientInstance = new Client();
        $client = $clientInstance->get($req['client_name'], $req['client_password']);

        if($client !== null) {
            $_SESSION['client_name'] = $req['client_name'];

            return true;
        }
        return false;
    }
    function makeLogout(): bool
    {
        if (isset($_SESSION['client_name'])) {
            unset($_SESSION['client_name']);
        }

        return true;
    }
}