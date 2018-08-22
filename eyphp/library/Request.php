<?php
/**
 * Request.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-08-22 15:38
 */

namespace Core\library;


class Request
{
    /**
     * 获取当前请求的方式
     *
     * @static
     * @access public
     * @return	string
     */
    static public function getMethod()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        return $method;
    }


    /**
     * 获取某种请求方式的某种类型参数的值
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @param	string	$type		参数类型
     * @return	mixed
     */
    static public function getVar($name, $default = null, $method = 'default', $type = 'none')
    {
        $method = strtoupper($method);

        switch ($method)
        {
            case 'GET' :
                $input = &$_GET;
                break;
            case 'POST' :
                $input = &$_POST;
                break;
            case 'FILES' :
                $input = &$_FILES;
                break;
            case 'COOKIE' :
                $input = &$_COOKIE;
                break;
            case 'ENV'    :
                $input = &$_ENV;
                break;
            case 'SERVER'    :
                $input = &$_SERVER;
                break;
            default:
                $input = &$_REQUEST;
                $method = 'REQUEST';
                break;
        }

        $var = (isset($input[$name]) && $input[$name] !== null) ? Request::_clearVar($input[$name], $type) : $default;

        return $var;
    }


    /**
     * 获取整型的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	int
     */
    static public function getInt($name, $default = 0, $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'int');
    }


    /**
     * 获取浮点型的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	float
     */
    static public function getFloat($name, $default = 0.0, $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'float');
    }

    /**
     * 获取布尔型的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	bool
     */
    static public function getBool($name, $default = false, $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'bool');
    }

    /**
     * 获取只允许字母的字符串,不区分大小写的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	string
     */
    static public function getWord($name, $default = '', $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'word');
    }

    /**
     * 获取只允许字母的字符串,不区分大小写, 以及 '.', '_' 和 '-'符号，不允许用'.'号开头的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	string
     */
    static public function getCmd($name, $default = '', $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'cmd');
    }

    /**
     * 获取对字符串中的单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）,进行转义的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	string
     */
    static public function getString($name, $default = '', $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'string');
    }

    /**
     * 获取只允许数字,下划线和字母的字符串,不区分大小写的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	string
     */
    static public function getAlnum ($name, $default = '', $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'alnum');
    }

    /**
     * 获取只允许日期时间格式的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	string
     */
    static public function getDateTime ($name, $default = '', $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'datetime');
    }


    /**
     * 获取安全的数组结构的参数
     *
     * @static
     * @access public
     * @param	string	$name		要获取的参数名
     * @param	mixed	$default	该参数的默认值
     * @param	string	$method		请求方式
     * @return	array
     */
    static public function getArray ($name, $default = array(), $method = 'default')
    {
        return Request::getVar($name, $default, $method, 'array');
    }


    /**
     * 设置某种请求方式的参数的值
     *
     * @static
     * @access public
     * @param	string	$name			要设置的参数名
     * @param	mixed	$value			该参数的值
     * @param	string	$method			请求方式
     * @param	bool	$overwrite		是否覆盖原参数
     * @return	mixed
     */
    static public function setVar ($name, $value = null, $hash = 'method', $overwrite = true)
    {
        if(!$overwrite && array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
        }

        $hash = strtoupper($hash);
        if ($hash === 'METHOD') {
            $hash = strtoupper($_SERVER['REQUEST_METHOD']);
        }

        $previous	= array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

        switch ($hash)
        {
            case 'GET' :
                $_GET[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'POST' :
                $_POST[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'COOKIE' :
                $_COOKIE[$name] = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'FILES' :
                $_FILES[$name] = $value;
                break;
            case 'ENV'    :
                $_ENV['name'] = $value;
                break;
            case 'SERVER'    :
                $_SERVER['name'] = $value;
                break;
        }

        return $previous;
    }

    /**
     * 批量设置某种请求方式的参数的值
     *
     * @static
     * @access public
     * @param	array	$array			要设置的参数的键值对
     * @param	string	$hash			请求方式
     * @param	bool	$overwrite		是否覆盖原参数
     * @return	void
     */
    static public function set( $array, $hash = 'default', $overwrite = true )
    {
        foreach ($array as $key => $value) {
            Request::setVar($key, $value, $hash, $overwrite);
        }
    }

    /**
     * 过滤所有全局外部数据的危险数据
     *
     * @static
     * @access public
     * @return	void
     */
    static public function clean()
    {
        Request::_cleanArray( $_FILES );
        Request::_cleanArray( $_ENV );
        Request::_cleanArray( $_GET );
        Request::_cleanArray( $_POST );
        Request::_cleanArray( $_COOKIE );
        Request::_cleanArray( $_SERVER );

        if (isset( $_SESSION )) {
            Request::_cleanArray( $_SESSION );
        }

        if (isset($_COOKIE)){
            Request::_cleanCookie($_COOKIE);
        }

        $REQUEST	= $_REQUEST;
        $GET		= $_GET;
        $POST		= $_POST;
        $COOKIE		= $_COOKIE;
        $FILES		= $_FILES;
        $ENV		= $_ENV;
        $SERVER		= $_SERVER;

        if (isset ( $_SESSION )) {
            $SESSION = $_SESSION;
        }

        foreach ($GLOBALS as $key => $value)
        {
            if ( $key != 'GLOBALS' ) {
                unset ( $GLOBALS [ $key ] );
            }
        }
        $_REQUEST	= $REQUEST;
        $_GET		= $GET;
        $_POST		= $POST;
        $_COOKIE	= $COOKIE;
        $_FILES		= $FILES;
        $_ENV 		= $ENV;
        $_SERVER 	= $SERVER;

        if (isset ( $SESSION )) {
            $_SESSION = $SESSION;
        }
    }


    /**
     * 过滤外部数据中的危险参数名
     *
     * @static
     * @access private
     * @param	array	&$array			要过滤的数据数组
     * @param	bool	$globalise		是否把数据加载到全局GLOBALS中
     * @return	mixed
     */
    private static function _cleanArray( &$array, $globalise=false )
    {
        static $banned = array( '_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals' );

        foreach ($array as $key => $value)
        {
            $failed = in_array( strtolower( $key ), $banned );

            $failed |= is_numeric( $key );
            if ($failed) {
                if (strtolower($key) == '_cookie'){
                    setcookie($key, null, time()-1);
                }
                unset($array[$key]);
                //zexit( '不允许的变量 <b>' . implode( '</b> or <b>', $banned ) . '</b> 传递通过脚本！' );
            }
            if ($globalise) {
                $GLOBALS[$key] = $value;
            }
        }
    }



    /**
     * 过滤掉cookie中的非法参数
     *
     * @static
     * @access private
     * @param	array	&$array			要过滤的数据数组
     * @return	mixed
     */
    private static function _cleanCookie( &$array)
    {
        foreach ($array as $key => $value)
        {
            if(preg_match("/[^a-zA-Z0-9_-]/", $key)){
                setcookie($key, null, time()-1);
                unset($array[$key]);
            }
        }
    }




    /**
     * 对请求的参数进行过滤操作
     *
     * @static
     * @access private
     * @param	mixed	$source		参数数据
     * @param	string	$type		数据类型
     * @return mixed
     */
    private static function _clearVar ($source, $type = 'STRING')
    {
        $type = strtoupper($type);

        switch ($type)
        {
            /**
             * 整型
             */
            case 'INT':
                preg_match('/-?[0-9]+/', (string)$source, $match);
                $result = @(int)$match[0];
                break;
            /**
             * 浮点型
             */
            case 'FLOAT':
                preg_match('/-?[0-9]+(\.[0-9]+)?/', (string) $source, $match);
                $result = @ (float) $match[0];
                break;
            /**
             * 布尔型
             */
            case 'BOOL':
                $result = @(bool)$source;
                break;
            /**
             * 只允许字母的字符串,不区分大小写
             */
            case 'WORD':
                $result = (string) preg_replace('/[^A-Z_]/i', '', $source);
                break;

            /**
             * 只允许数字,下划线和字母的字符串,不区分大小写
             */
            case 'ALNUM':
                $result = (string) preg_replace('/[^A-Z0-9_]/i', '', $source);
                break;


            /**
             * 只允许日期时间格式
             */
            case 'DATETIME':
                preg_match('/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/', (string) $source, $m);
                $result = @ (string) $m[0];
                break;

            /**
             * 只允许字母的字符串,不区分大小写, 以及 '.', '_' 和 '-'符号，不允许用'.'号开头
             */
            case 'CMD':
                $result = (string) preg_replace('/[^A-Z0-9_\.-]/i', '', $source);
                $result = ltrim($result, '.');
                break;
            /**
             * 对字符串中的单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）,进行转义
             */
            case 'STRING':
                $result = get_magic_quotes_gpc() ? trim((string)$source) : addslashes(trim((string)$source));
                break;

            /**
             * 获取安全的数组结构
             */
            case 'ARRAY':
                $result = (array) $source;
                $result = get_magic_quotes_gpc() ? $result : self::addslashesDeep($result);

                if (!is_array($result))
                {
                    $result = array($result);
                }
                break;
            case 'VAR':
                $pattern = '/(^[a-z_]{1}[a-z0-9_]*)/i';
                preg_match($pattern, (string)$source, $match);
                $result = trim(@(string)$match[0]);
                break;

            /**
             * 默认进行强制转换成安全的字符串
             */
            default:
                $result = addslashes((string)$source);
                break;
        }
        return $result;
    }




    function _addslashesDeep ($source)
    {
        $source = is_array( $source ) ? array_map( array( 'Request', '_addslashesDeep' ), $source ) : addslashes($source );
        return $source;
    }

    function _stripslashesDeep ($source)
    {
        $source = is_array( $source ) ? array_map( array( 'Request', '_stripslashesDeep' ), $source ) : stripslashes($source );
        return $source;
    }


}