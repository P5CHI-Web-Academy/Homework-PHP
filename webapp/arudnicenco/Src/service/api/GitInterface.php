<?php

namespace App\services\api;

interface GitInterface
{
    function getUser($username): array;

    function getProfileLink($username): string;
}