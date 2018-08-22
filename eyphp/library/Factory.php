<?php
/**
 * Config.php
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



}