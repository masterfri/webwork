<?php

class CFHttpShCommand extends HttpShCommand
{
	public function releaseWorkCopy($params)
	{
		return $this->query('cf-release.sh', array(
			'gitpath' => $params['gitpath'],
			'cfpath' => $params['cfpath'],
			'overwrite' => $params['overwrite'] ? 1 : '',
		));
	}
}
