<?php include_once 'configure.php';?>
<?php include_once 'core/Dispatcher.class.php';?>
<?php include_once 'core/App.class.php';?>
<?php
App::init();
spl_autoload_register(array('App', 'load'));
$dispatcher = new Dispatcher();
$dispatcher->dispatch(new BaseRequest(),new BaseResponse());
?>