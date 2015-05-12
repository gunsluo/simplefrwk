<?php

interface Filter
{
	public function beforeFilter(BaseRequest $request, BaseResponse $response);
	public function afterFilter(BaseRequest $request, BaseResponse $response);
}

?>