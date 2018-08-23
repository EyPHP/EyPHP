<?php
/**
 * config.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-07-25 17:11
 */

return array(
    // 路由设置
    'Router' => array(
        'URL_MODE' => 0,
        'VAR_CONTROLLER' => 'com',
        'VAR_ACTION' => 't',
        'VAR_MODULE' => 'module'
    ),

    'database' => array(
        'host' => 3306
    ),
    'appName' => 'demo',

    'smarty' => array(
        'cache' => false, // 关闭后端缓存
    )
);