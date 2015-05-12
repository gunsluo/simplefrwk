<?php include_once 'Event.class.php';?>
<?php include_once 'EventListener.interface.php';?>
<?php

class EventManager
{
	const DEFAULT_PRIORITY = 10;
	//public static $defaultPriority = self::DEFAULT_PRIORITY;
	protected $_listeners = array();
	protected $_events = array();
	
	public function attachListener(EventListener $listener,$priority = self::DEFAULT_PRIORITY)
	{
		foreach ($listener->implementedEvents() as $eventKey => $function)
		{
			if(!isset($this->_listeners[$eventKey]))
				$this->_listeners[$eventKey] = array(
						'listener'=>$listener,'priority'=>$priority,'fn'=>$function
				);
		}
		
	}
	
	protected function sortEvent(EventListener $listener)
	{
		/* sort by priority */
	}
	
	public function attach(Event $event)
	{	
		if(empty($this->_listeners[$event->name()]))
			return false;
		if(empty($this->_events[$event->name()]))
		{
			$this->_events[$event->name()] = array($event);
		}else{
			$this->_events[$event->name()] = array_merge($this->_events[$event->name()],array($event));
		}
	}
	
	public function dispatch($eventname)
	{
		if (empty($this->_listeners[$eventname]))
			return true;
		if (empty($this->_events[$eventname]))
			return true;
		foreach ($this->_events[$eventname] as $event)
		{
			$object = $event->subject()->newInstance();
			$method = $event->subject()->getMethod($this->_listeners[$eventname]['fn']);
			if(empty($event->data))
			{
				$result = $method->invoke($object);
			}else {
				$result = $method->invokeArgs($object,$event->data());
			}
			if($result === false)
				return false;
		}
		return true;
	}
}

class EventManagerFactory
{
	private static $_generalManager = null;

	public static function instance($manager = null) {
		if ($manager instanceof EventManager) {
			self::$_generalManager = $manager;
		}
		if (empty(self::$_generalManager)) {
			self::$_generalManager = new EventManager();
		}

		return self::$_generalManager;
	}
}

?>