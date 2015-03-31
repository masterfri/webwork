<?php 

class HttpShResponse
{
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
		return $this->code == HttpShCommand::CODE_OK;
	}
	
	public function getCode()
	{
		return $this->code;
	}
	
	public function getData()
	{
		return $this->data;
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
	
	public function __construct($code=HttpShCommand::CODE_OK, $message='', $data=null, $raw='')
	{
		$this->code = $code;
		$this->message = $message;
		$this->data = $data;
		$this->raw = $raw;
	}
}
