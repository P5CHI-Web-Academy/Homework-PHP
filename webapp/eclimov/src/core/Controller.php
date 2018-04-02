<?php

namespace Core;

use \Model\User;

class Controller
{
    protected $m_user;

    public function __construct()
    {
        $this->m_user = new User();
    }
}