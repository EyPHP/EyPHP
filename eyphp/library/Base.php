<?php
/**
 * Base.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-08-23 15:57
 */

namespace Core\library;

class Base
{
    public $config = null;

    public function __construct()
    {
        $this->config = Factory::getConfig();
        $app_name = $this->config->getConfig('','appName');
        // 模板路径
        $template_dir = APP_PATH.DS.'application'.DS.'public'.DS.'template'.DS.$app_name;
        $compile_dir = APP_PATH.DS.'application'.DS.'cache'.DS.'compile_dir';
        $temp_cache_dir = APP_PATH.DS.'application'.DS.'cache'.DS.'cache_dir';
        $smarty = $this->config->getConfig('','smarty');
        $this->getView()->caching = $smarty['cache']?:false;
        $this->getView()->template_dir = $template_dir;
        $this->getView()->compile_dir = $compile_dir;
        $this->getView()->cache_dir = $temp_cache_dir;
    }

    public function getView(){
        return Factory::getTemplate();
    }

    public function display($tpl = ''){
        $this->getView()->display($tpl);
    }

    public function assign($k,$v){
        $this->getView()->assign($k,$v);
    }
}