<?php include_once 'URLHandler.class.php';?>
<?php
interface Request
{
	
}

class BaseRequest implements Request
{
   	public $params = array();

   	public function __construct()
    {
        $this->params['url'] = URLHandler::geturl();
        
//         $pos = strpos($this->params['url'],UrlUnit::getWebRoot());
//         if($pos !== false){
//         	$this->params['relative_url'] = substr_replace($this->params['url'], '', $pos, strlen(UrlUnit::getWebRoot()));
//         }else{
//         	$this->params['relative_url'] = $this->params['url'];
//          }        
//         $pos = strpos($this->params['relative_url'],'?');
//         if($pos !== false)
//         	$this->params['relative_file'] = substr($this->params['relative_url'],0,$pos);
//         else
//         	$this->params['relative_file'] = $this->params['relative_url'];
        $this->params['isHttpXRequest'] = URLHandler::isHttpXRequest();
    }

   	public function geturl()
    {
        return $this->params['url'];
    }

//     public function getRelativeUrl()
//     {
//     	return $this->params['relative_url'];
//     }
    
//     public function getRelativeFile()
//     {
//     	return $this->params['relative_file'];
//     }
    
   	public function isHttpXRequest()
    {
        return $this->params['isHttpXRequest'];
    }

    /* 2.0 */
    protected function doget(){}
    protected function dopost(){}
    protected function dofiles(){}
}
?>

