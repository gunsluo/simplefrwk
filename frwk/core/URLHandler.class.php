<?php
class URLHandler
{
    public static function getServerUrlRoot()
    {
        return 'http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].self::getWebRoot();
    }
    
    public static function geturl()
    {
        if (!empty($_SERVER['PATH_INFO']))
        {
            return $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
        }
    
        return $uri;
    }
    
    public static function trans2UnixPath($path)
    {
        return str_replace('\\', '/', $path);
    }
    
    public static function getWebRoot()
    {
        //return str_replace('frwk/index.php', '', $_SERVER['PHP_SELF']);
        return str_replace(self::trans2UnixPath($_SERVER['DOCUMENT_ROOT']), '', self::trans2UnixPath(self::getAppDir()));
    }
    
    public static function getAppDir()
    {
        //return rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/'.ltrim(self::getWebRoot(),'/');
        return str_replace('frwk'.DIRECTORY_SEPARATOR.'core', '', dirname(__FILE__));
    }
    
    public static function isHttpXRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    private static function parseActionInfoFromURI($uri = NULL)
    {
        $pos = strpos($uri, '?');
        if($pos !== false){
            $uri = substr($uri,0,$pos);
        }
        $pos = strpos($uri, '/');
        if($pos === 0){
           $uri = substr($uri,  1);
        }
        $actionInfo = new stdClass();
        $actionArray = explode("/", $uri);
        if(count($actionArray) < 3){
            list($actionInfo->action, $actionInfo->method) =  $actionArray;
        }else{
            list($actionInfo->method, $actionInfo->action) =  array_reverse($actionArray);
        }
        return $actionInfo;
    }
    public static function parseActionInfo(BaseRequest $request)
    {
        return self::parseActionInfoFromURI($request->geturl());
    }
    
    private static function parseScriptInfoFromURI($url = NULL)
    {
        $interrupt = false;
    
        foreach ( Configure::$DIRECTORY_INDEXS as $key => $value ) {
            if ($url == NULL) {
                $value = '/' . $value;
            } else {
                if (substr ( $url, - 1 ) == '/') {
                    $value = $url . $value;
                } else {
                    $pos = strpos($url,'?');
                    if($pos !== false)
                        $value = substr($url,0,$pos);
                    else
                        $value = $url;
                    $interrupt = true;
                }
            }
            if(strpos($value, '/') === 0){
                $value = $_SERVER['DOCUMENT_ROOT'] . $value;
            }else{
                $value = $_SERVER['DOCUMENT_ROOT']. '/' . $value;
            }
            if (file_exists ( $value )) {
                return $value;
            }
            if ($interrupt) {
                return false;
            }
        }
    
        return false;
    }
    public static function parseScriptInfo(BaseRequest $request)
    {
        $scriptInfo = new stdClass();
        $script = self::parseScriptInfoFromURI($request->geturl());
        $scriptInfo->script = $script ? $script : self::getAppDir() .Configure::$REQUEST_PAGE_NOT_FOUND;
        return $scriptInfo;
    }
}
define('WEB_ROOT', URLHandler::getWebRoot());
define('APP_DIR', URLHandler::getAppDir());
?>
