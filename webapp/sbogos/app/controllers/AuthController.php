<?php

namespace App\Controllers;

use App\Core\Registry;

class AuthController {

    public function index()
    {
        
        return view('login');
    }

	public function login()
	{

        $matched = Registry::get('database')->login($_POST['username']);

    	if (password_verify($_POST['password'], $matched->password)) {
			$_SESSION['logged'] = 1;
            $_SESSION['user'] = $matched->name;
    	}

        return redirect('');
	}

	public function logout()
	{
	    
	    session_unset();

	    return redirect('');
	}

	public function register()
	{
	    
	    return view('register');
	}
	
	public function store()
	{
		
        Registry::get('database')->register($_POST['username'], bcrypt($_POST['password']));

        $matched = Registry::get('database')->login($_POST['username']);

    	if (password_verify($_POST['password'], $matched->password)) {
			$_SESSION['logged'] = 1;
            $_SESSION['user'] = $matched->name;
    	}

        return redirect('');
	}

}
