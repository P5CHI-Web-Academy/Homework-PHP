<?php

#define('ROOT', dirname(__FILE__));
class HomeController
{
	
	public function actionIndex()
	{
		echo ($content = file_get_contents(ROOT.'/../src/views/home/index.html'));
	}
}