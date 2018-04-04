<?php

return array(
	'user' => 'user/index',
	'user/([0-9]+)' => 'user/profile/$1',
	'login' => 'user/login',
	'logout' => 'user/logout',
	'home' => 'home/index', 
);