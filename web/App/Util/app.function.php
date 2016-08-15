<?
/**
* 获取配置信息
*
* @param	string	$name（文件名不带后缀：.config.php）
* @param	string	$key （数组的键）
* @return	array
*/
function config($name, $key)
{
	$file   = ConfigDir."/Other/{$name}.config.php";
	$config = file_exists($file) ? require($file) : array();

	return isset($config[$key]) ? $config[$key] : array();
}


/**
* 获取访问者ip
*
* @return  string
*/
function getClientIp()
{
	if ( getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown') )
	{
		$onlineip = getenv('HTTP_CLIENT_IP');
	}
	elseif ( getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown') )
	{
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	}
	elseif ( getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown') )
	{
		$onlineip = getenv('REMOTE_ADDR');
	}
	elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown') )
	{
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return $onlineip;
}


/**
* 日志记录
*
* @param	string	$content	日志内容
* @param	string  $file		日志文件名
* @param	string	$dir		日志存放目录
* @return	void
*/
function writeLog($content, $file = 'log.log', $dir = '')
{
	if ( preg_match('/php/i',$file) ) {
		return ;
	}

	$logDir = $dir ? $dir : LogDir;

	if (!file_exists($logDir) ) {
		@mkdir($logDir);
		@chmod($logDir, 0777);
	}

	if ( is_array($content) ) {
		$content = var_export($content, true);
	} else {
		$content = "【".date("Y-m-d H:i:s", time())."】\t\t".$content."\r\n";
	}
	file_put_contents($logDir.'/'.$file, $content, FILE_APPEND);
}

/**
* 获取分页条
*
* @param	array   $pager   组合要素
* @param	bool    $script  是否带有下拉
* @return	string
*/
function getPageBar($pager, $script = false)
{
	if ( empty($pager) || !is_array($pager) ) {
		return '';
	}

	$html = '<div class=""><div class="col-md-3 pagination dataTables_info text-right">共'.$pager['recordNum'].'条，'. '当前第'.$pager['current'].'/' . $pager['pageNum'] . '页' . '</div>';
	$html .= '<div class="col-md-9 "><ul class="col-md-9 pagination">';
	if ( $pager['pageNum'] > 10 ) {
		$html .= '<li><a href="' . $pager['first'] . '">首页</a></li></li>' .
		'<li class="prev"><a href="' . $pager['pre']   . '">上页</a></li>' .
		'<li class="next"><a href="' . $pager['next']  . '">下页</a></li><li>' . $pager['point'].
		'</li><li><a href="' . $pager['last']  . '">尾页</a></li>' ;
	} elseif ($pager['pageNum'] > 1) {
		$html .= '<li><a href="' . $pager['first'] . '">首页</a></li>' .
		'<li class="prev"><a href="' . $pager['pre']   . '">上页</a></li>' .
		'<li class="next"><a href="' . $pager['next']  . '">下页</a></li>' .
		'<li><a href="' . $pager['last']  . '">尾页</a></li>' ;
	}
	$html .= '</ul>';

	if ($pager['pageNum'] > 1)
		$html .= $script ? '<div class="col-md-3 col-md-offset-3 pagination">'.$pager['jump'].'</div>' : '';

	$html .= '</div>';

	return $html;
}

/**
* 数组格式转换[2维转化成1维]
*
* @param  array  $list  2维数组
* @param  array  $cols  2维数组中的列名[array(id)、array(id,name)]
* @return array
*/
function arrayOne($list, $cols = array())
{
	if ( empty($list) || (empty($cols) || !is_array($cols)) ) return $list;

	$temp   = array();
	$length = count($cols);
	foreach ($list as $data) {
		if ( $length == 1 ) {
			$temp[] = isset($data[$cols[0]]) ? $data[$cols[0]] : '';
		} else {
			$temp[$data[$cols[0]]] = isset($data[$cols[1]]) ? $data[$cols[1]] : '';
		}
	}
	return $temp;
}

/**
* 字符串1是否为字符串2的子串
*
* @param  string	$str1	字符串1
* @param  string	$str2	字符串2
* @return bool
*/
function strExist($str1, $str2)
{
	return !(strpos($str2, $str1) === FALSE);
}


/**
* 返回数组中指定的一列 (可见php5.5新函数array_column)
* @static
* @access public
* @param array $array 需要取出数组列的多维数组（或结果集）
* @param string $column_key 需要返回值的列，它可以是索引数组的列索引
* @param string $index_key 作为返回数组的索引/键的列，它可以是该列的整数索引，或者字符串键值
* @return array
*/
function arrayColumn(array $array, $column_key, $index_key=null){
	if(function_exists('array_column')){
		return array_column($array, $column_key, $index_key);
	}
	$result = array();
	foreach($array as $arr){
		if(!is_array($arr)) continue;

		if(is_null($column_key)){
			$value = $arr;
		}else{
			$value = $arr[$column_key];
		}
		if(!is_null($index_key)){
			$key = $arr[$index_key];
			$result[$key] = $value;
		}else{
			$result[] = $value;
		}
	}
	return $result;
}

function C($key)
{
	return LoadConfig::get($key);
}



/**
* URL跳转
*
* @param	string  $desc		消息文本
* @param	string  $url		跳转地址
* @param	string  $scripts	待执行的多个JS文件地址
* @param	int		$seconds	停留时间(秒)
* @return  void
*/
function goUrl($desc, $url, $scripts = array(), $seconds = 1)
{
	$msgFile = ResourceDir.'/default/redirect.html';
	$path    = '/'.ResourceDir.'/';
	$js      = '';

	foreach ( $scripts as $script ) {
		$js .= $script;
	}

	$tipInfo  = "$desc <br>请稍后,系统正在自动跳转........";
	$gotoUrl  = "<meta http-equiv='Refresh' content='$seconds; url=$url'>";
	require($msgFile);
	exit();
}

/**
* 获取某长度的随机字符编码
* 除纯数字与纯字母选项，其他都不包括(I,i,o,O,1,0)
* @author	Xuni
* @since	2015-11-05
*
* @param	int		$len	编码长度
* @param	string	$format	格式（ALL：大小写字母加数字，CHAR：大小写字母，NUMBER：纯数字，默认为小写字母加数字）
* @return	array
*/
function randCode($len, $format='')
{
	$is_abc = $is_numer = 0;
	$password = $tmp ='';
	switch($format){
		case 'ALL':
			$chars='ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789';
			break;
		case 'CHAR':
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
			break;
		case 'NUMBER':
			$chars='0123456789';
			break;
		default :
			$chars='abcdefghjklmnpqrstuvwxyz23456789';
			break;
	}
	mt_srand((double)microtime()*1000000*getmypid());
	while(strlen($password)<$len){
		$tmp =substr($chars,(mt_rand()%strlen($chars)),1);
		if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
			$is_numer = 1;
		}
		if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
			$is_abc = 1;
		}
		$password.= $tmp;
	}
	if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
		$password = randCode($len,$format);
	}
	return $password;
}


/**
* 判断用户类型
* @author	garrett
* @since	2015-04-24
*
* @param	string	$account 用户帐号
* @return	int    类型  1 代表邮箱 2 代表手机 3 代表帐号错误
*/
function isCheck( $account )
{
	$mobile = "/^(13|14|15|16|17|18)\d{9}$/";
	$email  = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a(www.111cn.net)-z]{2,3}([.][a-z]{2})?/i";
	if(preg_match($email ,$account))
	{
		return 1;

	} elseif (preg_match( $mobile ,$account))
	{
		return 2;
	} else
	{
		return 3;
	}
}


/**
* 数据调试
* @author garrett
* @param array $data 数据
* @param string $pattern 输出类型
* @return void
*/
function debug( $data , $pattern = '')
{
	echo "<pre>";
	die(empty($pattern) ? print_r($data) : var_dump($data));
}

/**
* 获取字符长度
* @author garrett
* @param array $data 数据
* @param string $pattern 输出类型
* @return void
*/
function length($str , $iconv = 'utf-8')
{
	return iconv_strlen($str , $iconv);
}




/**
* url参数加密
*
* @author 	garrett
* @since  	2015-4-27  上午10:28
*
* @access 	public
* @param   string $str  加密内容
*
* @return boolean
*/
function urlParamEncode($str)
{
	$data = Encrypt::encode($str);
	return base64_encode($data);
}

/**
 * url参数解密
 *
 * @author 		martin
 * @since  		2016/3/8
 *
 * @access 	public
 * @param string $str  解密内容
 *
 * @return boolean
 */
function urlParamDecode($str)
{
	$data = base64_decode($str);
	return Encrypt::decode($data);
}
/**
* 生成URL地址中的参数
*
* @author   garrett
* @since    2015-4-27
*
* @access   public
* @param    string $email	邮箱
* @param    int    $time   生成时间
* @param 	 int    $uid	用户ID
*
* @return string
*/
function makeToken($email, $time, $uid = 0)
{
	$params = $email . '||' . $uid ;
	$key 	= md5($time);
	return urlParamEncode($key . '||' . $params);
}

/**
* 字符串截取
*
* @author   garrett
* @since    2015-4-27
*
* @access   public
* @param    string $email	邮箱
* @param    int    $time   生成时间
* @param 	 int    $uid	用户ID
*
* @return string
*/
function mbSub( $conect , $min = 0 , $leng = 100 , $en='utf-8')
{
	$output = mb_substr($conect , $min , $leng , $en);
	if(mb_strlen($conect,$en) > $leng ){
		$output .= "...";
	}
	return $output;
}

/**
* 半角转化为全角
*
* 更换数据
* @author 		garrett
* @since  		2015-6-26
*
* @access 	public
* @param string $str  解密内容
*
* @return boolean
*/
function symbol( $name )
{
	$arr = array(
		'０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
		'５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
		'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
		'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
		'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
		'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
		'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
		'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
		'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
		'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
		'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
		'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
		'ｙ' => 'y', 'ｚ' => 'z',
		'（' => '(', '）' => ')', '［' => '[', '］' => ']', '【' => '[',
		'】' => ']', '〖' => '[', '〗' => ']', '「' => '[', '」' => ']',
		'『' => '[', '』' => ']', '｛' => '{', '｝' => '}', '《' => '<',
		'》' => '>',
		'％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
		'：' => ':', '。' => '.', '、' => ',', '，' => ',',
		'；' => ';', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
		'＂' => '"', '＇' => '`', '｀' => '`', '｜' => '|', '〃' => '"',
		'　' => ' ' , '＆'=>'&'
	);
	foreach( $arr as $key=>$val)
	{
		$name = str_replace($key , $val , $name);
	}

	return $name;
}



/**
* 设置模块的查询参数
*
* 返回时跳转到之前列表页
* @author 		garrett
* @since  		2015-6-26
*
* @access 	public
* @param string $str  解密内容
*
* @return boolean
*/
function moduleParam($model, $action="index", $params=array())
{
	if( empty($params) ){
		return Session::get($model.$action);
	}


	$str = "/".$model."/".$action."/?".http_build_query($params);
	Session::set($model.$action, $str, 3600);
	//Log::write($str,'log123123123.log');
	//Log::write($_SERVER['REQUEST_URI'],'logaaaaa.log');
	//return "/".$model."/".$action."/?".http_build_query($params);
}



/**
* 模拟HTTP请求
*
* @param	string	$url		请求的地址
* @param	string	$method		0为GET、1为POST
* @param	string	$param		提交的参数
* @param	int		$timeout	超时时间（秒）
* @return	string
*/
function httpRequest($url, $method = 0, $param = '', $timeout = 10)
{
	$userAgent ="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
	$ch        = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	if ( $method == 1 ) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt( $ch,CURLOPT_HTTPHEADER, array(
		'Accept-Language: zh-cn',
		'Connection: Keep-Alive',
		'Cache-Control: no-cache',
	));
	$document = curl_exec($ch);
	$info     = curl_getinfo($ch);
	if ( $info['http_code'] == "405" ) {
		curl_close($ch);
		return 'error';
	}
	curl_close($ch);
	return $document;
}

function strSplit($string)
{
	$chars = array();
	$count = mb_strlen($string, 'UTF-8');
	for ( $i = 1; $i <= $count; $i++ ) {
		$chars[] = mb_substr($string, $i - 1, 1, 'UTF-8');
	}

	return $chars;
}
/**
* 获取ip
* @since    2016-01-18
* @return   string
*/
function getIp()
{
	if(getenv('HTTP_CLIENT_IP')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR')) {
		$onlineip = getenv('REMOTE_ADDR');
	} else {
		$onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
	}
	return $onlineip;
}
/**
* 获取随机数
* @author   haydn
* @since    2015-10-12
* @param    int         $length     随机长度
* @param    bool        $isnum      是否数字
* @return   string      $str
*/
function getRandChar($length = 8,$isnum = false)
{
	$str    = '';
	$strPol = $isnum == true ? "0123456789" : "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	$max    = strlen($strPol)-1;
	for($i=0;$i<$length;$i++){
		$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}
	return $str;
}
/**
* 密码md5加密
* @author   haydn
* @since    2015-10-12
* @param    string      $password  密码
* @return   string      $string
*/
function passwordMd5($password)
{
	$string = md5($password);
	return $string;
}
/**
* 密码md5加密
* @author   haydn
* @since    2016-01-20
* @param    string      $password   密码
* @param    string      $salt       密码签名
* @return   string      $string
*/
function getPasswordMd5($password,$salt = '')
{
	$string = md5(md5($password).$salt);
	return $string;
}
/**
* 数字转换成秒
* @author   haydn
* @since    2016-01-26
* @param    int	      	$num   		数字
* @return   int      	$sec
*/
function toSec($num)
{
	$sec = $num*3600*1000;
	return $sec;
}
/**
* 验证来源是否正确
* @author   haydn
* @since    2016-01-27
* @return   bool		验证来源的合法性（true:合法 false:非法）
*/
function checkSource()
{
	$is = checkJsApiCode();
	return $is;

}
/**
* 验证js跨域
* @author   haydn
* @since    2016-01-27
* @return   bool		验证来源的合法性（true:合法 false:非法）
*/
function checkJsApiCode()
{
	$is			= false;
	$timestamp	= $_GET['timestamp'];
	if( time() - $timestamp < 2000 ){
		$nonceStr	= $_GET['nonceStr'];
		$signature	= $_GET['signature'];
		$surl		= $_GET['surl'];
		$referer 	= $_SERVER["HTTP_REFERER"];
		$jsapiToken = 'chaofnwang';
		$key		= sha1("jsapi_ticket={$jsapiToken}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$referer}");
		if( $key == $signature ){
			$is = true;
		}
	}
	return $is;
}
/**
* 获取用户key
* @author   haydn
* @since    2016-01-27
* @return   string		$cookid用户的COOK
*/
function getUserKey()
{
	$ukey	= C('PUBLIC_USER');
	$cookid = LoginAuth::get($ukey);
	return $cookid;
}
/**
* 创建目录
* @author   haydn
* @since    2016-01-27
* @param    string  $filename	目录地址
* @return   bool
*/
function mkdirs($filename)
{
	$dir	= $filename;
	$errmsg = '';
	$dir 	= explode('/', $dir);
	foreach($dir as $v){
		if($v){
			@$d .= $v . '/';
			if(!is_dir($d)){
				$state = mkdir($d, 0777);
			}
		}
	}
	return true;
}

/**
* 二维数组按照某个键名的排序
* @access	haydn
* @since	2016-03-02
* @param 	array 		$arr	原数组
* @param 	string 		$keys	排序key
* @param 	string 		$type	排序方式
*/

function array_sort($arr,$keys,$type='asc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = iconv('UTF-8','GBK',$v[$keys]);
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	foreach ($keysvalue as $k=>$v){
		$new_array[$k] = $arr[$k];
	}
	return $new_array;
}

/**
* 截取浮点型[不四舍五入]
* @access	martin
* @since	2016/3/2
* @param 	float 		$f		数组
* @param 	int 		$len	长度
* @param 	float 		$data	返回浮点型
*/
function getFloatValue($f,$len)
{
	$tmpInt=intval($f);

	$tmpDecimal	=$f-$tmpInt;
	$str		= "$tmpDecimal";
	$subStr		=strstr($str,'.');
	if($subStr == false){
		return $tmpInt.".00";
	}elseif(strlen($subStr) < $len+1){
		$repeatCount=$len+1-strlen($subStr);
		$str	=$str."".str_repeat("0",$repeatCount);
		return $tmpInt."".substr($str,1,1+$len);
	}else{
		return $tmpInt."".substr($str,1,1+$len);
	}
}
/**
* 手机、邮箱、字符串加密
* @access	haydn
* @since	2016/3/4
* @param 	string 		$string	加密字符串
* @return	string		$string	加密后字符串
*/
function encrypt($string)
{
	$type = isCheck($string);
	if( $type == 1 ){
		$first	= strpos($string,'@');
		$length	= ($first - 1) > 4 ? 4 : ($first - 1);
		$strat	= ($first - 4) > 1 ? ($first - 4) : 1 ;
		$string	= substr_replace($string,"****", $strat, $length);
	}elseif( $type == 2 ){
		$string = substr_replace($string,"****",3,4);
	}else{
		$len 	= mb_strlen($string,'utf-8');
		if( $len >= 6 ){
			$str1 = mb_substr($string,0,2,'utf-8');
            $str2 = mb_substr($string,$len-2,2,'utf-8');
		}else{
			$str1 = mb_substr($string,0,1,'utf-8');
			$str2 = mb_substr($string,$len-1,1,'utf-8');
		}
		$string	= $str1.'****'.$str2;
	}
	return $string;
}
/**
* 导出excel的表单
* @access	haydn
* @since	2016/3/10
* @param 	string 		$data （serialize形式的数据）
* @return	void
*/
function excelForm($data)
{
	echo '<form id="form1" name="form1" method="post" action="'.CRM_EXPORT.'" style="display:none;">
			<textarea name="xlsjson" id="textarea" cols="145" rows="20">'.$data.'</textarea>
			<input type="submit" value="submit" />
		</form>
		<script>form1.submit(); window.close();</script>';

	exit;
}
/**
* 字符串截取
* @since	2016-04-01
* @author	haydn
* @param 	string		$str		字符串
* @param 	int			$length		截取长度
* @param 	string		$coding		字符串编码
* @return	string		$string		新字符串
*/
function strCutout($str,$length = 20,$coding = 'utf-8')
{
	$len 	= mb_strlen($str,$coding);
	$string = $len > $length ? mb_substr($str,0,$length,$coding) : $str;
	return $string;
}
/**
 * 清除数组中的空值 和 重复值
 * @author 	haydn
 * @param 	array $array
 * @return 	Array
 */
function removeEmptyArray($array)
{
	return array_diff(array_unique($array),array(null));
}
/**
 * 验证key是否存在
 * @author 	haydn
 * @param 	array $array	验证
 * @param 	array $param	被验证
 * @return 	Array
 */
function verifyArrayKey($array,$param)
{
	$msg = '';
	foreach( $verifyArr as $k => $v ){
		if( !array_key_exists($v,$param) ){
			$msg = $v.'不存在';
			break;
		}
	}
	return $msg;
}

/**
 * 得到客户端ip地址
 * @param int $type
 * @param bool $adv
 * @return mixed
 */
function get_client_ip($type = 0,$adv=true) {
	$type       =  $type ? 1 : 0;
	static $ip  =   NULL;
	if ($ip !== NULL) return $ip[$type];
	if($adv){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos    =   array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip     =   trim($arr[0]);
		}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip     =   $_SERVER['HTTP_CLIENT_IP'];
		}elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip     =   $_SERVER['REMOTE_ADDR'];
		}
	}elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip     =   $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u",ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}
?>