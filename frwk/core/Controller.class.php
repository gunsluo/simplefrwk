<?php include_once 'AjaxAction.class.php';?>
<?php
class Controller
{
	private $request;	
	private $response;
	
	public function __construct(BaseRequest $request,BaseResponse $response = null)
	{
	    $this->request = $request;
	    $this->response = $response;
	}
	
	protected function handlerActionFlow($action, $doAction)
	{
	    if(empty($action )){
	        return;
	    }
	    if(empty($doAction)){
	        $doAction = "doAction";
	    }

	    try {
	        //include '/home/luoji/work/webshop/src/admin/actions/LoginAction.class.php';
	        $reflection = new ReflectionClass($action);
	        if($reflection->hasMethod($doAction) === false){
	            return;
	        }
	        $class = $reflection->newInstance($this->request,$this->response);
	        if($class instanceof BaseAction){
	            $class->beforAction();
	            $class->{$doAction}();
	            $class->afterAction();
	        }
	    } catch (Exception $e) {
	        echo $e;
	    }
	}
	
	protected function isAction($action)
	{
	    if($this->request->isHttpXRequest()){
	        return true;
	    }
	    return  (preg_match('/.*Action$/', $action) === 1) ? true : false;
	}
	
	protected function handlerScriptFlow($script)
	{
	    include_once $script;
	}
	
	public function invokeAction()
	{
	    //判断请求类型
	    $actionInfo = URLHandler::parseActionInfo($this->request);
	    if($this->isAction($actionInfo->action)){
	        //$actionInfo = URLHandler::parseActionInfo($this->request);
	        $this->handlerActionFlow($actionInfo->action, $actionInfo->method);
	    }else {
	        $scriptInfo = URLHandler::parseScriptInfo($this->request);
	        $this->handlerScriptFlow($scriptInfo->script);
	    }
	}
	
	public function init(){}
	public function destroy(){}
}
?>