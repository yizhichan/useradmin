<?
/**
* 用户中心接口
* 
* @access	public
* @package bi
* @author	Xuni
* @since	2015-11-05
*/
class CrmBi extends Bi
{
	/**
	* $host 定义接口地址
	* 本地测试环境：http://demo.chofn.com/
	* 线上生产环境：http://system.chofn.net/
	* @var string
	*/
	//public $api_key  = '216Iv321Sz247FDasasafff';//一只蝉求购信息key

	/**
	* 定义接口用户名
	* @var string
	*/
	public $user = '';

	/**
	* 定义接口密钥
	* @var string
	*/
	public $key  = '';
	public $api_key = array(
		'usercenter' 	=> '11afghaxbnPZ4gOxSO7lcLLsPu',
		'tmsystem' 		=> 'KU6IjH2tSzgg7FDa',
		'yizhchan' 		=> '216Iv321Sz247FDasasafff',
	);
	/**
	* 对外接口域名编号(默认为超凡网、其他待定)
	*/
	//public $apiId = 2;


	/**
	* 获取我的顾问信息
	* 
	* @access	public
	* @param	array	$query		查询条件
	*
	* @return	array   返回数据包
	*/
	public function getStaffInfo($query)
	{
		$param  = array(
			'api_type'  => 'getStaffInfo',
			'api_key'   => $this->api_key['usercenter'],
			'api_user'  => 'usercenter',
			'id'        => 100001,
			'map'       => serialize($query),
		);
		$url    = CRM_URL.'Api/usercenter.php';
		$json   = $this->request($param,$url);
		$data   = json_decode($json,true);
		return $data;
	}
	/**
	* 数据提交到分配系统
	* @since	2016-03-10
	* @author	haydn
	* @access	public
	* @param	int		$sid		顾问id
	* @param	string	$content	评价内容
	* @param	int		$isTrade	是否更换顾问(0：否 1：是)
	* @return	int   	返回分配系统的主键id
	*/
	public function sendCrmMail($sid,$account,$content,$isTrade = 0)
	{
		$param  = array(
			'api_type'  => 'sendCrmMail',
			'api_key'   => $this->api_key['usercenter'],
			'api_user'  => 'usercenter',
			'id'        => 100001,
			'sid'       => $sid,
			'account'   => $account,
			'content'   => $content,
			'isTrade'   => $isTrade,
		);
		$url    = CRM_URL.'Api/usercenter.php';
		$json   = $this->request($param,$url);
		$data   = json_decode($json,true);
		return $data;
	}

	/**
	* 数据提交到分配系统
	* 
	* @access	public
	* @param	array	$array	提交数据包
	*
	* @return	int   	返回分配系统的主键id
	*/
	public function networkJoin($array)
	{
		$param  = array(
			'api_type'  => 'networkJoin',
			'api_key'   => $this->api_key['tmsystem'],
			'api_user'  => 'tmsystem',
			'id'        => 100001,
		);
		$param	= array_merge($param,$array);
		$json   = $this->request($param);
		$data   = json_decode($json,true);
		return $data;
	}
	/**
	* 获取最新提交的数据
	* @author 	haydn
	* @since	2016-02-27
	* @param	string		$tel	电话
	* @return	json 
	*/
	public function getNewSubmit($tel)
	{
		$param  = array(
			'api_type'  => 'getNewSubmit',
			'api_key'   => $this->api_key['yizhchan'],
			'api_user'  => 'yizhchan',
			'id'        => 100001,
			'tel'		=> $tel
		);
		$url    = CRM_URL.'Api/yizhchan.php';
		$json   = $this->request($param,$url);
		$data   = json_decode($json,true);
		return $data;
	}
	/**
	* 获取已经成交和已经立案的商标
	* @author 	haydn
	* @since	2016-02-27
	* @param	array		$query		查询数组包  例如：$query['aid'] = array(1879941,1879884,1880011);
	* @param	int 		$pageIndex	第几页
	* @param	int 		$pageSize	每页显示
	* @return	json 
	*/
	public function getClinchBrand($query)
	{
		$data	= array_merge(array('aid' => $query['aid']),$query['search']);
		$param  = array(
			'api_type'  => 'getClinchBrand',
			'api_key'   => $this->api_key['yizhchan'],
			'api_user'  => 'yizhchan',
			'id'        => 100001,
			'map'		=> serialize($data),
			'pageSize'  => $query['search']['limit'],
			'pageIndex'	=> $query['search']['page'],
		);
		$url    = CRM_URL.'Api/yizhchan.php';
		$json   = $this->request($param,$url);
		$data   = json_decode($json,true);
		return $data;
	}
	/**
	* 发送短信(模板方式)
	* @author 	haydn
	* @since	2016-03-18
	* @param	string		$mobile		手机号
	* @param	string 		$content	内容
	* @param	int 		$tempId		模板id
	* @return	json 
	*/
	public function sendSmsMsg($mobile, $content, $tempId = 0)
	{
		$param  = array(
			'api_type'  => 'sendSmsMsg',
			'api_key'   => $this->api_key['usercenter'],
			'api_user'  => 'usercenter',
			'id'        => 100001,
			'mobile'	=> $mobile,
			'content'  	=> $content,
			'tempId'	=> $tempId,
		);
		$url    = CRM_URL.'Api/usercenter.php';
		$json   = $this->request($param,$url);
		$data   = json_decode($json,true);//print_r($data);
		return $data;
	}
	/**
	* 获取求购信息数量
	* @since	2016-03-31
	* @author	haydn
	* @param	string||array	$aidArr
	* @return	json 
	*/
	public function getWantTm($aidArr)
	{
		$param  = array(
			'api_type'  => 'getWantTm',
			'api_key'   => $this->api_key['yizhchan'],
			'api_user'  => 'yizhchan',
			'id'        => 100001,
			'aid'		=> serialize($aidArr),
		);
		$url    = CRM_URL.'Api/yizhchan.php';
		$json   = $this->request($param,$url);
		$data   = json_decode($json,true);//print_r($data);
		return $data;
	}
	/**
	* 获取商标日志
	* @since	2016-04-08
	* @author	haydn
	* @param	string	$tid	商标id
	* @param	string	$class	商标类别
	* @return	json 
	*/
	public function getTrademarkLog($tid,$class)
	{
		$param  = array(
			'api_type'  => 'getTrademarkLog',
			'api_key'   => $this->api_key['yizhchan'],
			'api_user'  => 'yizhchan',
			'id'        => 100001,
			'tid'		=> $tid,
			'class'		=> $class,
		);
		$url    = CRM_URL.'Api/yizhchan.php';
		$json   = $this->request($param,$url);
		$data   = json_decode($json,true);//print_r($data);
		return $data;
	}
	/**
	* 转移到第三顾问
	* @author  martin
	* @since   2015/11/6
	* 
	* @access  public
	* @param   $param 相关参数
	* @return  bool  
	*/
	public function request($param, $url = '')
	{
		$url    = !empty($url) ? $url : CRM_URL.'Api/';
		$data	= array('api_key' => md5($param['api_key'].$param['id']));
		unset($param['api_key']);
		$data	= array_merge($data,$param);
		$ch     = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		$result = curl_exec($ch);//print_r($result);
		if($result === false) {
			$result = curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}



	/**
	* 查询一只蝉的求购出售信息
	* @author  martin
	* @since   2016/1/26
	* 
	* @access  public
	* @param   $param 相关参数
	* @return  bool  
	*/
	public  function yizhichan($param)
	{
		$api_key 	= $this->api_key['yizhchan'];
		$ch 		= curl_init();
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, CRM_URL.'Api/yizhchan.php');
		curl_setopt(
			$ch, CURLOPT_POSTFIELDS,
			array_merge(
				array(
					'api_user' => 'yizhchan',
					'api_key' => md5($api_key.$param['id'])
				),
				$param
			)
		);
		$result = curl_exec($ch);//print_r($result);
		if($result === false) {
			$result = curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}



	/**
	* 获取网络信息
	* @since  	2016-01-11
	* @author 	haydn
	* @param	array 	$query		查询包
	* @param	int 	$pageIndex	第几页
	* @param	int 	$pageSize	每页显示
	* @return 	json 	$data 		数据包
	*/
	public function getNetwork($query)
	{
		$param  = array(
			'api_type'  => 'getNetwork',
			'id'        => 100001,
			'map'		=> serialize($query),
			'pageSize'  => empty($query['limit']) ? 1 : $query['limit'],
			'pageIndex'	=> empty($query['page']) ? 1 : $query['page'],
		);
		$json   = self::yizhichan($param);
		$data   = json_decode($json,true);
		//print_r($data);
		return $data;

	}
	//===========================实例1：获取网络信息====================
	//$query = array('13594688','13594688','13594688');
	//$json = $this->getNetwork($query);

	/**
	* 用网络id获取信息
	* @since  	2016-01-11
	* @author 	haydn
	* @param	int 	$aid	网络信息id
	* @return 	json 	$data 	数据包
	*/
	public function getBrandInfo($aid)
	{
		$param = array(
			'api_type'   => 'getBrandInfo',
			'id'         => 100001,
			'aid'		 => $aid,
		);
		$json   = self::yizhichan($param);
		$data   = json_decode($json,true);
		return $data;
	}
	//============================实例2：用网络id获取信息=========================
	//$json = $this->getBrandInfo(1880039);
	//$json = json_decode($json,true);
}
?>