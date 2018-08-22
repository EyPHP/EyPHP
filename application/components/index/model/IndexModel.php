<?php
/**
 * index.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-07-25 17:29
 */

namespace App\home\index\model;

class IndexModel
{

    public $className;

    public function __construct()
    {
        $this->className = $this;
    }

    public function getMySelf(){
        return $this->className;
    }
}