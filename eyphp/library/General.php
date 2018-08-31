<?php
/**
 * 工具类
 * General.php
 * Created by PhpStorm.
 * User: chenli
 * Time: 2018-08-22 11:11
 */
namespace Core\library;

class General
{
	
	/**
	 * 验证邮箱
	 * @static 
	 * @access public
	 * @param string	$email	邮箱
	 * @return int
	 */
	static public function validateEmail($email)
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * 对密码进行验证
	 * @static 
	 * @access public
	 * @param string	$password	密码原文
	 * @param string	$encrypted	加密后的密码
	 * @return string
	 */
	static public function validatePassword($password, $encrypted)
	{
		if (!empty($password) && !empty($encrypted)){
			$arr = explode(':', $encrypted);
			if (count($arr) != 2){return false;}
			if (md5($arr[1].$password) == $arr[0]){
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * 将密码加密
	 * @static 
	 * @access public
	 * @param string	$password	要加密的密码原文
	 * @return string
	 */
	static public function encryptPassword($password)
	{
		$string = '';
		for ($i=0; $i<10; $i++) {
			$string .= self::rand();
		}
		
		$salt = substr(md5($string), 0, 2);
		
		$password = md5($salt . $password) . ':' . $salt;
		
		return $password;
	}
	
	
	/**
	 * 产生随机数
	 * @static 
	 * @access public
	 * @param int	$min	随机数最小值范围
	 * @param int	$max	随机数最大值范围
	 * @return string
	 */
	static public function rand($min = null, $max = null)
	{
		static $seed;
		if (!isset($seed)){
			mt_srand((double)microtime()*1000000);
			$seeded = true;
		}
		
		if (isset($min) && isset($max)){
			if ($min >= $max){
				return $min;
			}else{
				return mt_rand($min, $max);
			}
		}else{
			return mt_rand();
		}
	}
	
	
	/**
	 * @desc 创建随机密码
	 * @static 
	 * @access public
	 * @author zc
	 * @date 2013-09-18 17:55:17
	 * @return string
	 */
	static public function randPassword(){
		$salt = "46z3haZzegmn676PA3rUw2vrkhcLEn2p1c6gf7vp2ny4u3qqfqBh5j6kDhuLmyv9xf";
		srand((double)microtime()*1000000); 
		$password = '';
		for ($x = 0; $x < 7; $x++) {
			$num = rand() % 33;
			$tmp = substr($salt, $num, 1);
			$password = $password . $tmp;
		}
		return $password;
	}
	
	
	/**
	 * 获取访问者的IP地址
	 * @access stat
	 * @var resource
	 * @return sting
	 */
	static public function IP()
	{
	    if (isset($_SERVER)) {
			//优先BG自定义转发参数
			if(isset($_SERVER['HTTP_X_REAL_IP_BGPROXY'])){
				$ip = $_SERVER['HTTP_X_REAL_IP_BGPROXY'];
			}elseif (isset($_SERVER['HTTP_TRUE_CLIENT_IP'])){
	            $ip = $_SERVER['HTTP_TRUE_CLIENT_IP'];
	        }
	        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
	            $ip = $_SERVER['HTTP_CLIENT_IP'];
	        } else {
	            $ip = $_SERVER['REMOTE_ADDR'];
	        }
	    } else {
			if(getenv('HTTP_X_REAL_IP_BGPROXY')){
				$ip = getenv('HTTP_X_REAL_IP_BGPROXY');
			}elseif (getenv('HTTP_TRUE_CLIENT_IP')) {
	            $ip = getenv('HTTP_TRUE_CLIENT_IP');
	        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
	            $ip = getenv('HTTP_X_FORWARDED_FOR');
	        } elseif (getenv('HTTP_CLIENT_IP')) {
	            $ip = getenv('HTTP_CLIENT_IP');
	        } else {
	            $ip = getenv('REMOTE_ADDR');
	        }
	    }
        $arr = explode(',', $ip);
        $ip = $arr[0];
		return trim($ip);
	}
	
	
	/**
	 * 判断是否为手机浏览
	 * @access static
	 * @return bool
	 */
	static public function isMobile(){
		//如果Cookies中设置了不访问m站就返回假
		if ($_COOKIE['_mFullSite']){
			return false;
		}

		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		}
		
		//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset ($_SERVER['HTTP_VIA'])) {
			//找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		
		if (isset($_SERVER['HTTP_USER_AGENT'])){
			$keywords = array(
				'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh',
				'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo',
				'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian',
				'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone',
				'cldc', 'midp', 'wap', 'mobile', 
			);
			if (preg_match("/(" . implode('|', $keywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
				return true;
			}
		}
		
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) 
				&& (
					strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false 
					|| (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))
					)
			){
				return true;
			}
		}
		
		return false;
	}

	/**
	 * @desc html实体转换为html字符
	 * @author ChenLuoyong
	 * @date 2013-08-02
	 * @return string 格式化后的字符串
	 */
	static public function decodeSpecialchars($string){
		$string=str_replace('&gt;', '>', $string);
		$string=str_replace('&lt;', '<', $string);
		$string=str_replace('&#039;', "'", $string);
		$string=str_replace('&quot;', "\"", $string);
		$string=str_replace('&amp;', '&', $string);
	
		return $string;
	}
	
	
	
	/**
	 * 把xml对象转换成数组
	 * @static 
	 * @author zc 
	 * @date 2013-08-15 14:07:09
	 * @param object $xmlObj
	 * @return array
	 */
	static public function xmlObjToArray($xmlObj){
		$result = array();
		if (is_object($xmlObj)){
			$arr = get_object_vars($xmlObj);
			if ($arr){
				foreach ($arr as $k => $v){
					if (is_object($v)){
						$result[$k] = self::xmlObjToArray($v);
					}
					elseif (is_array($v)){
						foreach ($v as $vk => $vv){
							$v[$vk] = self::xmlObjToArray($vv);
						}
						$result[$k] = $v;
					}else{
						$result[$k] = $v;
					}
				}
			}else{
				return '';
			}
			
		}
		else
		{
			return $xmlObj;
		}
		return $result;
	}
	
	
	
	/**
	 * 获取当前日期
	 * @static 
	 * @author zc 
	 * @date 2013-08-16 12:01:20
	 * @param bool	 $is_china 	是否获取中国日期
	 * @param string $format 	日期格式
	 * @param bool	 $is_time	是否返回时间戳
	 * @param int	 $time		可设置具体时间点
	 * @return string
	 */
	static public function nowDate($is_china = false, $format = 'Y-m-d H:i:s', $is_time = false, $time = null){
		if ($is_china){
			$default_zone = date_default_timezone_get();
			$china_zone = 'Etc/GMT-8';
			date_default_timezone_set($china_zone);
		}
		$time = intval($time);
		$format = $format ? $format : 'Y-m-d H:i:s';
		$date = $time > 0 ? date($format, $time) : date($format);
		$date = $is_time ? strtotime($date) : $date;
		
		if ($is_china){
			date_default_timezone_set($default_zone);
		}
		
		return $date;
	}
	
	
	/**
	 * 把中国日期转换成服务器日期
	 * @static 
	 * @author zc 
	 * @date 2013-09-06 12:39:24
	 * @param bool	 $chinaDate 	中国日期
	 * @param string $format 	日期格式
	 * @param bool	 $is_time	是否返回时间戳
	 * @return string
	 */
	static public function chinaServerDate($chinaDate, $format = 'Y-m-d H:i:s', $is_time = false){
		//保存服务器时区
		$default_zone = date_default_timezone_get();
		
		//设置中国时区
		$china_zone = 'Etc/GMT-8';
		date_default_timezone_set($china_zone);
		//按中国时区转换成时间戳
		$time = strtotime($chinaDate);
		//还原服务器时区
		date_default_timezone_set($default_zone);
		
		if ($is_time){
			return $time;
		}
		
		$format = $format ? $format : 'Y-m-d H:i:s';
		$date = date($format, $time);
		
		return $date;
	}

    /**
     * @desc 判断是否是异步请求
     * @static
     * @return bool
     * @author LuoZhou
     * @date 2013-8-30 13:53
     */
    static public function isAjaxRequest()
    {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] ==='XMLHttpRequest' ? true : false;

        return $isAjax;
    }
    
	/**
     * @desc ob_start缓存压缩函数
     * @author zc
     * @date 2013-10-23 14:09:07
     * @static
     * @access public
     * @param string $buffer 缓存内容
     * @return string
     */
    static public function compress($buffer){
    	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		return $buffer;
    }
    
    
    
    /**
     * @desc 压缩Html内容去掉空白无用的字符
     * @author zc
     * @date 2013-10-23 14:09:07
     * @static
     * @access public
     * @param string $string	html内容
     * @return string
     */
    static public function compressHtml($string) {
	    $string = str_replace('\r\n', '', $string); //清除换行符
	    $string = str_replace('\n', '', $string); //清除换行符
	    $string = str_replace('\t', '', $string); //清除制表符
	    $pattern = array (
	        "/> *([^ ]*) *</", //去掉注释标记
	        "/[\s]+/",
	        "/<!--[^!]*-->/",
	        //"/\" /",   // 防止如  \" src 标签合并
	       	"/ \"/",
	        "'/\*[^*]*\*/'"
	    );
	    $replace = array (
	        ">\\1<",
	        " ",
	        "",
			//"\"",
	        "\"",
	        ""
	    );
	    return preg_replace($pattern, $replace, $string);
	}

	
	
	/**
	 * @desc 过滤XSS攻击代码
	 * @access public
	 * @author ThinkPHP
	 * @date 2015-02-05 13:56:30
	 * @param string $val 字符参数
	 * @return string
	 */
	static public function RemoveXSS($val) {  
	   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed  
	   // this prevents some character re-spacing such as <java\0script>  
	   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        //这行会过滤,
	   //$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
	 
	   // straight replacements, the user should never need these since they're normal characters  
	   // this prevents like <IMG SRC=@avascript:alert('XSS')>  
	   $search = 'abcdefghijklmnopqrstuvwxyz'; 
	   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';  
	   $search .= '1234567890!@#$%^&*()'; 
	   $search .= '~`";:?+/={}[]-_|\'\\'; 
	   for ($i = 0; $i < strlen($search); $i++) { 
	      // ;? matches the ;, which is optional 
	      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
	 
	      // @ @ search for the hex values 
	      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ; 
	      // @ @ 0{0,7} matches '0' zero to seven times  
	      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ; 
	   } 

	   // now the only remaining whitespace attacks are \t, \n, and \r 
	   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base'); 
	   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
	   $ra = array_merge($ra1, $ra2); 
	 
	   $found = true; // keep replacing as long as the previous round replaced something 
	   while ($found == true) { 
	      $val_before = $val; 
	      for ($i = 0; $i < sizeof($ra); $i++) { 
	         $pattern = '/'; 
	         for ($j = 0; $j < strlen($ra[$i]); $j++) { 
	            if ($j > 0) { 
	               $pattern .= '(';  
	               $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
	               $pattern .= '|';  
	               $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
	               $pattern .= ')*'; 
	            } 
	            $pattern .= $ra[$i][$j]; 
	         } 
	         $pattern .= '/i';
	         
	         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag  
	         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags  
	         if ($val_before == $val) {  
	            // no replacements were made, so exit the loop  
	            $found = false;  
	         }  
	      }  
	   }  
	   return $val;  
	}

    /**
     * @desc 加密解密函数
     * @param $string 明文或密文
     * @param string $operation 加密ENCODE或解密DECODE
     * @param string $key 密钥
     * @param int $expiry 密钥有效期
     * @param bool $rand 是否使用随机密钥
     * @return bool|string
     */
	static public function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0, $rand = true) {
	    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
	    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	    // 当此值为 0 时，则不产生随机密钥
	    $ckey_length = $rand?4:0;
	  
	    // 密匙
	    $key = md5($key); 
	  
	    // 密匙a会参与加解密
	    $keya = md5(substr($key, 0, 16));
	    // 密匙b会用来做数据完整性验证
	    $keyb = md5(substr($key, 16, 16));
	    // 密匙c用于变化生成的密文
	    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	    // 参与运算的密匙
	    $cryptkey = $keya.md5($keya.$keyc);
	    $key_length = strlen($cryptkey);
	    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
	    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
	    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	    $string_length = strlen($string);
	    $result = '';
	    $box = range(0, 255);
	    $rndkey = array();
	    // 产生密匙簿
	    for($i = 0; $i <= 255; $i++) {
	        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
	    }
	    // 用固定的算法，打乱密匙簿，增加随机性
	    for($j = $i = 0; $i < 256; $i++) {
	        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
	        $tmp = $box[$i];
	        $box[$i] = $box[$j];
	        $box[$j] = $tmp;
	    }
	    // 核心加解密部分
	    for($a = $j = $i = 0; $i < $string_length; $i++) {
	        $a = ($a + 1) % 256;
	        $j = ($j + $box[$a]) % 256;
	        $tmp = $box[$a];
	        $box[$a] = $box[$j];
	        $box[$j] = $tmp;
	        // 从密匙簿得出密匙进行异或，再转成字符
	        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	    }
	    if($operation == 'DECODE') {
	        // substr($result, 0, 10) == 0 验证数据有效性
	        // substr($result, 0, 10) - time() > 0 验证数据有效性
	        // substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
	        // 验证数据有效性，请看未加密明文的格式
	        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
	            return substr($result, 26);
	        } else {
	            return '';
	        }
	    } else {
	        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
	        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
	        return $keyc.str_replace('=', '', base64_encode($result));
	    }
	}

}
