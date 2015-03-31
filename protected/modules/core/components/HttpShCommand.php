<?php

abstract class HttpShCommand
{
	const CODE_OK = 0;
	const CODE_ERR_CURL = 1;
	const CODE_ERR_HTTP_CODE = 2;
	
	protected $_host;
	protected $_port;
	protected $_login;
	protected $_password;
	
	public function __construct($login, $password, $host='127.0.0.1', $port=2424)
	{
		$this->_host = $host;
		$this->_port = $port;
		$this->_login = $login;
		$this->_password = $password;
	}
	
	protected function handleResponse($response)
	{
		$code = 0;
		$message = '';
		$data = null;
		foreach (explode("\n", $response) as $line) {
			if ('>RETURN:' == substr($line, 0, 8)) {
				$line = explode(' ', ltrim(substr($line, 8)), 2);
				$code = intval(array_shift($line));
				$message = trim(array_shift($line));
			} elseif ('>DATA:' == substr($line, 0, 6)) {
				$line = explode(' ', ltrim(substr($line, 6)), 2);
				$key = trim(array_shift($line));
				$val = trim(array_shift($line));
				if (null === $data) {
					$data = array();
					$data[$key] = $val;
				} elseif (isset($data[$key])) {
					if (is_array($data[$key])) {
						$data[$key][] = $val;
					} else {
						$data[$key] = array($data[$key], $val);
					}
				} else {
					$data[$key] = $val;
				}
			}
		}
		return new HttpShResponse($code, $message, $data);
	}
	
	protected function normalizeParams($params)
	{
		$normalized = array();
		foreach ($params as $name => $param) {
			if (is_scalar($param)) {
				if ('' != $param) {
					$normalized[$name] = $param;
				}
			} else {
				$this->unwrapParam($param, $name, $normalized);
			}
		}
		return $normalized;
	}
	
	protected function unwrapParam($param, $name, &$normalized)
	{
		foreach ($param as $k => $v) {
			if (is_scalar($v)) {
				if ('' != $v) {
					$normalized["$name-$k"] = $v;
				}
			} else {
				$this->unwrapParam($v, "$name-$k", $normalized);
			}
		}
	}
	
	protected function createToken()
	{
		return sprintf('%s:%s', $this->_login, $this->_password);
	}
	
	public function query($path, $params=array())
	{
		$url = sprintf('http://%s:%s/%s', $this->_host, $this->_port, $path);
		$params = $this->normalizeParams($params);
		if ($params !== array()) {
			$url .= '?' . http_build_query($params);
		}
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Auth-Token: ' . $this->createToken()));
		$response = curl_exec($ch);
		if (false === $response) {
			$error = curl_error($ch);
			curl_close($ch);
			return new HttpShResponse(self::CODE_ERR_CURL, $error);
		} else {
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if (200 == $code) {
				$result = $this->handleResponse($response);
				$result->setRaw($response);
				return $result;
			} else {
				return new HttpShResponse(self::CODE_ERR_HTTP_CODE, Yii::t('core.httpsh', 'HTTPSH request has returned bad HTTP code: {code}', array(
					'{code}' => $code,
				)), array(
					'code' => $code,
				), $response);
			}
		}
	}
}
