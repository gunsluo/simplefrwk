<?php

class BaseAction
{
	protected $request;
	
	protected $response;
	
	public function __construct(BaseRequest $request,BaseResponse $response = null)
	{
		$this->request = $request;
		$this->response = $response;
	}
	
	public function getRequest()
	{
		return $this->request;
	}
	
	public function getResponse()
	{
		return $this->response;
	}
	
	public function redirect($url)
	{
		$this->response->header('Location',WEB_ROOT.$url);
		$this->response->send();
	}
	
	protected function sendReply($msg)
	{
		$this->response->sendReply($msg);
	}
	
	public function doAction()
	{
	    
	}
	
	public function beforAction(){
	}
	public function afterAction(){
	}
}

?>