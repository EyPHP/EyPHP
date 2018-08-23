<?php
/**
 * 工厂类
 * Factory.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-08-22 11:11
 */
namespace Core\library;
use Core;
class Factory
{


    /**
     * @desc 加载配置类
     * @return Config|null
     */
    static public function &getConfig()
    {
        static $instance = null;
        if(!isset($instance)){
            $instance = new Config();
        }
        return $instance;
    }

    /**
     * @desc 工具类
     */
    static public function &getGeneral()
    {
        static $instance = null;
        if(!isset($instance)){
            $instance = new General();
        }
        return $instance;
    }

    /**
     * 创建SMARTY模版
     *
     */
    public static function &getTemplate()
    {
        static $instance;

        if (!isset($instance))
        {
            $instance = new \Smarty();
        }

        return $instance;
    }

}