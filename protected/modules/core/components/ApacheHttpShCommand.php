<?php

class ApacheHttpShCommand extends HttpShCommand
{
	public function setupVhost($params)
	{
		return $this->query('setup-vhost.sh', array(
			'domain' => $params['domain'],
			'document_root' => $params['document_root'],
			'log_directory' => $params['log_directory'],
			'vhost_options' => array_filter(array_map('trim', explode("\n", $params['vhost_options']))),
		));
	}
}
