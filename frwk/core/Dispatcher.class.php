<?php include_once 'Request.class.php';?>
<?php include_once 'Response.class.php';?>
<?php include_once 'Controller.class.php';?>
<?php include_once 'EventListener.interface.php';?>
<?php include_once 'EventManager.class.php';?>
<?php include_once 'Filter.interface.php';?>
<?php include_once 'BaseFilter.class.php';?>
<?php include_once 'ConfigHandler.class.php';?>
<?php

class Dispatcher implements EventListener
{
	protected $_eventManager;
	
	public function __construct()
   	{
   		$this->eventManager();
    }

    protected function redirect($url)
    {
    	include $url;
    }
    
    protected function getController($request, $response) {
        $controller = false;
        try {
            $reflection = new ReflectionClass(Configure::$BASE_CONTROLLER);
            if ($reflection->isAbstract() || $reflection->isInterface())
            {
                $controller =  false;
            }else{
                $controller = $reflection->newInstance($request, $response);
            }
        } catch (Exception $e) {
            echo $e;
        }finally {
            return $controller;
        }
    	//return new Controller($request,$response);
    }
    
 	protected function invoke(Controller $controller)
 	{
 		$controller->init();
 		$controller->invokeAction();
 		$controller->destroy();
 	}
 	
	public function dispatch(BaseRequest $request, BaseResponse $response)
    {
    	$this->_attachFilters($request,$response);
    	$isRun = $this->getEventManager()->dispatch('Dispatcher.beforeDispatch');
    	if($isRun){
    	    $controller = $this->getController($request, $response);
    	    $this->invoke($controller);
    	}
        $this->getEventManager()->dispatch('Dispatcher.afterDispatch');
    }
    
    public function implementedEvents() {
    	return array('Dispatcher.beforeDispatch' => 'beforeFilter','Dispatcher.afterDispatch' => 'afterFilter');
    }
    
    protected function _attachFilters(BaseRequest $request, BaseResponse $response)
    {
//     	$filters = Configure::$DISPATCHER_FILTERS;
    	$filters = ConfigHandler::getFilters();
    	if(empty($filters))
    		return;
    	try {
    	    foreach ($filters as $filtername)
    	    {
    	        $class = new ReflectionClass($filtername);
    	        //     		foreach ($this->implementedEvents() as $eventKey => $function)
    	            //     		{
    	            //     			$this->_eventManager->attach(new Event($eventKey,$class, array($request,$response)));
    	            //     		}
    	         $this->_eventManager->attach(new Event('Dispatcher.beforeDispatch',$class, array($request,$response)));
    	         $this->_eventManager->attach(new Event('Dispatcher.afterDispatch',$class, array($request,$response)));
             }
    	} catch (Exception $e) {
    	    echo $e;
    	}
    }
    
    protected function eventManager()
    {
    	if (!$this->_eventManager)
    	{
    		$this->_eventManager = EventManagerFactory::instance();
    		$this->_eventManager->attachListener($this);
    	}
    }
    
    public function getEventManager()
    {
    	return $this->_eventManager;
    }
}

?>

