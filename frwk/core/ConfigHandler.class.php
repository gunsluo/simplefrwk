<?php
class ConfigHandler
{
    private static function getModuleConfig($url)
    {
        $modules = Configure::$MODULES;
        foreach ($modules as $mName => $module)
        {
            if(preg_match($module['pattern'], $url) === 1){
                return$module;
            }
        }
        return false;
    }
    
    public static function getFilters()
    {
        $url = URLHandler::geturl();
        $module = self::getModuleConfig($url);
        if($module === false){
            return array();
        }
        return $module['filters'];
    }
    
    public static function getLoadPaths()
    {
        $url = URLHandler::geturl();
        $module = self::getModuleConfig($url);
        if($module === false){
            return array();
        }
        return $module['loadpath'];
    }
}
?>