<?php
/**
 * Config.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-08-22 11:11
 */

namespace Core\library;

class Config
{
    //获取配置
    public function getConfig($path = '',$name = ''){

        $config = array();
        //$fileName = substr(APP_PATH, 0, strlen(APP_PATH) - strlen(APP_DIR) + 1).DS.'install'.DS.RUNENV.DS.$configName.'.php';
        if($path == ''){
            $fileName = APP_PATH.DS.'application'.DS.'install'.DS.'config.php';
        }else{
            $fileName =  APP_PATH.DS.'application'.DS.str_replace(".",DS,$path).DS.'config.php';
        }

        if(file_exists($fileName)){
            $config = include $fileName;
        }else{
            echo 'Config File: '.$fileName.' not exits!';exit;
        }

        if($name){
            return $config[$name];
        }
        return $config;
    }
}