<?php 

class HttpShResponse
{
	const CODE_OK = 0;
	const CODE_ERR_CURL = -1;
	const CODE_ERR_HTTP_CODE = -2;
	const CODE_ERR_BAD_RESPONSE = -3;
	
	const CODE_ERR_CREATE_DIR = 1;
	const CODE_ERR_CHANGE_MODE = 2;
	const CODE_ERR_CHANGE_OWNER = 3;
	const CODE_ERR_CREATE_FILE = 4;
	const CODE_ERR_RM_DIR = 5;
	const CODE_ERR_RM_FILE = 6;
	const CODE_ERR_NO_SUCH_FILE = 7;
	const CODE_ERR_CLEANUP_DIR = 8;
	
	const CODE_ERR_APACHE_RELOAD = 101;
	
	const CODE_ERR_GIT_INIT_REPO = 201;
	const CODE_ERR_GIT_ADD_REMOTE = 202;
	const CODE_ERR_GIT_UPDATE_REMOTE = 203;
	const CODE_ERR_GIT_WORKING_COPY = 204;
	const CODE_ERR_GIT_PULL = 205;
	const CODE_ERR_GIT_CHECKOUT = 206;
	const CODE_ERR_GIT_STAGE_FILE = 207;
	const CODE_ERR_GIT_COMMIT = 208;
	
	const CODE_ERR_MYSQL_CREATE_DB = 301;
	const CODE_ERR_MYSQL_GRANT_ACCESS = 302;
	const CODE_ERR_MYSQL_UPDATE_PASSWORD = 303;
	const CODE_ERR_MYSQL_DROP_DB = 304;
	const CODE_ERR_MYSQL_DROP_USER = 305;
	
	const CODE_ERR_CF_RELEASE = 401;
	const CODE_ERR_CF_CONFLICTS = 402;
	
	const CODE_ERR_NGINX_RELOAD = 501;
	
	protected $code;
	protected $message;
	protected $data;
	protected $raw;
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function getIsSuccess()
	{
		return $this->code == self::CODE_OK;
	}
	
	public function getCode()
	{
		return $this->code;
	}
	
	public function getData($key=null)
	{
		if (null === $key) {
			return $this->data;
		} else {
			return isset($this->data[$key]) ? $this->data[$key] : null;
		}
	}
	
	public function hasData($key=null)
	{
		if (null === $key) {
			return $this->data !== null;
		} else {
			return isset($this->data[$key]);
		}
	}
	
	public function getRaw()
	{
		return $this->raw;
	}
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function setRaw($response)
	{
		$this->raw = $response;
	}
	
	public function __construct($code=self::CODE_OK, $message='', $data=null, $raw='')
	{
		$this->code = $code;
		$this->message = $message;
		$this->data = $data;
		$this->raw = $raw;
	}
}
