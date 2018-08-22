<?php
/**
 * Loader.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-08-22 10:24
 */

namespace Core;
use App;
use Core\library;

class Run
{

    // 路由
    public $url = array();

    // 路由配置
    public $router = array();

    public $module = null;
    public $controller = null;
    public $action = null;

    public $com = null;

    public function __construct($config = array())
    {
        // 获取路由配置
        $config = library\Factory::getConfig();
        $this->router = $config->getConfig('','Router');
        //实例化路由类
        library\Router::init($this->router);

        $this->url = library\Router::makeUrl();

        $this->module = $this->url['module']?:'home';
        $this->controller = $this->url['controller']?:'index';
        $this->action = $this->url['action']?:'lst';

        // 创建实例类
        $this->createClass();

    }

    /**
     * 生成实例类对象
     */
    public function createClass(){
        $class = ucfirst($this->controller).'Controller';
        $this->com = str_replace('/','\\',"App/" . $this->module . "/" . $this->controller . "/" . $class);
        
    }

    public function start()
    {
        //实例化改类
        $com = new $this->com();
        // 执行该方法
        $com->{$this->action}();
    }

}