<?php

class CleanupHttpShCommand extends HttpShCommand
{
	public function cleanup($params)
	{
		$args = array();
		foreach ($params['options'] as $option) {
			switch ($option) {
				case 'files':
				case 'vhost':
				case 'git':
				case 'db':
				case 'dbuser':
					$args["delete_{$option}"] = 1;
					break;
			}
		}
		$args['domain'] = $params['domain'];
		$args['db_name'] = $params['db_name'];
		$args['db_user'] = $params['db_user'];
		$args['repo_name'] = $params['repo_name'];
		return $this->query('cleanup.sh', $args);
	}
}
