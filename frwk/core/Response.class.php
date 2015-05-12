<?php
interface Response
{
}

class BaseResponse implements Response
{
	protected $_headers = array ();
	
	public function send()
	{
		foreach ( $this->_headers as $header => $values )
		{
			foreach ( ( array ) $values as $value )
			{
				$this->sendHeader( $header, $value );
			}
		}
	}
	
	public function sendReply($msg)
	{
		echo $msg;
	}
	
	public function header($header = null, $value = null)
	{
		if ($header === null)
		{
			return $this->_headers;
		}
		$headers = is_array($header) ? $header : array($header => $value);
		foreach ($headers as $header => $value)
		{
			if (is_numeric($header))
			{
				list($header, $value) = array($value, null);
			}
			if (is_null($value))
			{
				list($header, $value) = explode(':', $header, 2);
			}
			$this->_headers[$header] = is_array($value) ? array_map('trim', $value) : trim($value);
		}
		return $this->_headers;
	}

	public function redirect($url)
	{
		$this->header('Location',WEB_ROOT.$url);
		$this->send();
	}
		
	protected function sendHeader($name, $value = null)
	{
		if (! headers_sent ())
		{
			if ($value === null)
			{
				header ( $name );
			} else
			{
				header ( "{$name}: {$value}" );
			}
		}
	}
}
?>
