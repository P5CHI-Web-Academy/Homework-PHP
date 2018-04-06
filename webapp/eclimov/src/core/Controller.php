<?php

namespace Core;

use \Model\User;
use \Service\Templater;

class Controller
{
    protected $m_user;
    protected $t_templater;

    public function __construct(User $m_user, Templater $t_templater)
    {
        $this->m_user = $m_user;
        $this->t_templater = $t_templater;
    }
}