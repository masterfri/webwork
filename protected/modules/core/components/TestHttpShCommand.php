<?php

class TestHttpShCommand extends HttpShCommand
{
	public function showEnv()
	{
		return $this->query('test-env.sh');
	}
}
