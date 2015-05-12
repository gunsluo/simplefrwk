<?php

class Event
{	
	protected $_name = null;
	
	protected $_subject = null;
	
	public $data = null;
	
	public function __construct($name, $subject = null, $data = null)
	{
		$this->_name = $name;
		$this->_subject = $subject;
		$this->data = $data;
	}
	
	public function name()
	{
		return $this->_name;
	}	
	public function subject()
	{
		return $this->_subject;
	}
	
	public function data()
	{
		return $this->data;
	}
}

?>