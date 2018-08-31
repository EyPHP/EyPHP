<?php
/**
 * Curl.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-08-31 8:40
 */

namespace Core\library;


class Curl{

    public $ch = null;

    public $curlInfo;

    protected $callback = null;

    public function __construct(){
        $this->ch = curl_init();
        $this->curlInfo = null;
    }

    public function setCallback($class, $method) {

        if (is_callable(array($class, $method)))
        {
            $this->callback['class'] = $class;
            $this->callback['method'] = $method;
        }
    }

    public function doRequest($method, $url, $vars, $expired = null) {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT']);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        if(isset($expired)){
            $expired = intval($expired);
            if ($expired > 0) {
                curl_setopt($this->ch, CURLOPT_TIMEOUT, $expired);
            }
        }
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, 'cookie.txt');
        if($method == 'POST') {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $vars);
        }
        ob_start();
        $data = curl_exec($this->ch);
        ob_end_clean();

        $this->curlInfo = curl_getinfo($this->ch);

        if($data) {
            if($this->callback) {
                $callback = $this->callback;
                $this->callback = array();
                return call_user_func(array($callback['class'], $callback['method']), $data);
            } else {
                return $data;
            }
        } else {
            $this->setError(curl_error($this->ch));
            return false;
        }
    }


    public function get($url, $expired = null) {
        return $this->doRequest('GET', $url, 'NULL', $expired);
    }

    public function post($url, $vars, $expired = null) {
        return $this->doRequest('POST', $url, $vars, $expired);
    }


}