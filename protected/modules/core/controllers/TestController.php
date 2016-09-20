<?php

class TestController extends AdminController 
{
	public function actionHttpSh()
	{
		$host = GeneralOptions::instance()->httpsh_host;
		$port = GeneralOptions::instance()->httpsh_port;
		$login = GeneralOptions::instance()->httpsh_login;
		$passw = GeneralOptions::instance()->httpsh_password;
		
		$command = new TestHttpShCommand($login, $passw, $host, $port);
		$response = $command->showEnv();
		
		$this->render('httpsh', array(
			'response' => $response,
		));
	}
	
	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('httpSh'),
				'roles' => array('admin'),
			),
			array('deny'),
		);
	}
}
