<?php
/**
 * index.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-07-25 17:29
 */

namespace App\home\index;

use Core\library\Request;
use App\home\index\model;


class IndexController
{
    public function index(){
        $model = new model\IndexModel();
        echo '<pre>';
        var_dump($model->getMySelf());
        echo 1;
    }

    public function lst(){
        $img = Request::getString('img','s');
        var_dump($img);die;
        echo 'chenli';
    }
}