<?php
/**
 * index.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-07-25 17:29
 */

namespace App\home\index;

use App\home\index\model;
use Core\library\Controller;
use Core\library\Request;


class IndexController extends Controller
{
    public function index(){
        $model = new model\IndexModel();
        echo '<pre>';
        var_dump($model->getMySelf());
        echo 1;
    }

    public function lst(){
        $img = Request::getString('img',1);
        $this->assign('img',$img);
        $this->display('index.html');

    }
}