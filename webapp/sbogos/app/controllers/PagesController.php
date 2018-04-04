<?php

namespace App\Controllers;

use App\Core\Registry;

class PagesController {

	public function index()
	{
        if ($_SESSION['logged'] != 1) {
            return redirect('login');
        }

        $user = Registry::get('database')->get($_SESSION['user']);

		return view('index', compact('user'));
	}
}
