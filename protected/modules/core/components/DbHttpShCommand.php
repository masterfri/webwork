<?php

class DbHttpShCommand extends HttpShCommand
{
	public function setupDb($params)
	{
		return $this->query('setup-db.sh', array(
			'db_name' => $params['db_name'],
			'user_name' => $params['user_name'],
			'password' => $params['password'],
		));
	}
}
