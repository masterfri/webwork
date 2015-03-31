<?php

class GitHttpShCommand extends HttpShCommand
{
	public function setupGit($params)
	{
		return $this->query('setup-git.sh', array(
			'domain' => $params['domain'],
			'name' => $params['name'],
			'url' => $params['create'] == 1 ? '' : $params['url'],
		));
	}
	
	public function pull($params)
	{
		return $this->query('git-pull.sh', array(
			'domain' => $params['domain'],
			'url' => $params['url'],
			'branch' => $params['branch'],
		));
	}
}
