<?php

use App\Core\Registry;

use App\Core\Database\{QueryBuilder, Connection};

session_start();

Registry::bind('database', new QueryBuilder(Connection::make()));
