<?php include_once 'Filter.interface.php';?>
<?php

class BaseFilter implements Filter
{	
	public function beforeFilter(BaseRequest $request, BaseResponse $response)
	{
		return true;
	}
	
	public function afterFilter(BaseRequest $request, BaseResponse $response)
	{
		return true;
	}
}

?>