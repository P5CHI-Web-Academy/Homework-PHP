<?php

$router->get('', 'PagesController@index');

$router->get('login', 'AuthController@index');
$router->post('login', 'AuthController@login');

$router->get('register', 'AuthController@register');
$router->post('register', 'AuthController@store');

$router->post('logout', 'AuthController@logout');

