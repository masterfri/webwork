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
	
	public function pullWorkCopy($params)
	{
		return $this->query('git-pull-work-copy.sh', array(
			'workpath' => $params['workpath'],
			'url' => $params['url'],
			'branch' => $params['branch'],
		));
	}
	
	public function pushWorkCopy($params)
	{
		return $this->query('git-push-work-copy.sh', array(
			'workpath' => $params['workpath'],
			'url' => $params['url'],
			'branch' => $params['branch'],
			'message' => $params['message'],
		));
	}
}
