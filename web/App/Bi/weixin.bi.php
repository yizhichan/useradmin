<?php
/**
* API请求地址
* 本地测试环境：http://demo.chofn.com:88/
* 线上正式环境：http://system.chofn.net/
* @var string
*/
/**
* API请求用户名
* @var string
*/
define('WEIXIN_API_USER', 'chofnweb');

/**
* API请求KEY
* 测试环境key，发布到线上时找Tobey获取正式key
* @var string
*/
define('WEIXIN_API_KEY', '02piewdaKsml2OS0dadsmweD');

/*
* 微信二维码验证调用示例
*/
// $ucId = 43352;
// $ewm = weixin::bindweixin($ucId, 3);//获取二维码
// sleep(10); //等待扫描二维码
// $check = weixin::check($ewm['info']['checkId'], $ucId);//检查二维码
// print_r(array($ewm, $check));

/*
* 微信消息推送接口调用示例
*/
//$rtn = weixin::sendMsg(43352, '微信消息', 1);
//print_r($rtn);


class weixinBi extends Bi
{
	public function check($checkId, $ucId)
	{
		return $this->bindweixin($ucId, 1, $checkId);
	}

	/**
	* 获取关注二维码并检查状态
	* @param unknown $ucId
	* @param number $type
	* @param number $checkId
	* @param number $userType 0CRM，1超凡网，2知友
	* @return unknown
	*/
	public function bindweixin($ucId, $type = 1, $checkId = 0, $userType = 1)
	{
		$fieldName = $userType == 1 ? 'ucId' : ($userType == 2 ? 'ucId' : 'sid');
		$param = array(
			'api_type' 	=> 'bindweixin',
			'ucId' 		=> $ucId,
			'type'		=> $type,
			'checkId' 	=> $checkId,
		);
		$json 	= $this->request($param);
		$result = json_decode($json, true);
		return $result;
		/**
		* $result 返回数据结构
		* ========================================================================
		* 未扫描状态，返回二维码信息
		* array(
		* 		error => 0,
		* 		info => array(
		* 			'ticket' => 'http://*****',//二维码地址
		* 			'timestamp' => 12123213123,//时间戳，在此基础上1800秒后二维码过期
		* 		)
		* );
		* ========================================================================
		* 已扫描，返回微信用户信息
		* array(
		* 		error => 1,
		* 		info => array(
		* 			'nickname' => 'xxxxx',//微信昵称
		* 			'headimgurl' => 'http://********',//微信头像地址
		* 		)
		* );
		* ========================================================================
		* 
		* ========================================================================
		* 二维码验证成功返回信息
		* array(
		* 		error => 1,
		* 		errmsg => '二维码验证成功'
		* );
		*  二维码验证失败返回信息
		* array(
		* 		error => 2,
		* 		errmsg => '超凡网账号已绑定其它微信'
		* 		nickname => '微信昵称'
		* );
		* ========================================================================
		*/

		/**
		* 业务处理，前端建议间隔3-5秒调用一次此接口，判断是否扫描；
		*/
		if($result['error'] == 0){//二维码未扫描，返回二维码信息
			$ticket 	= $result['info']['ticket'];//二维码图片地址
			$timestamp 	= $result['info']['timestamp'];//生成时间，在此基础上1800秒后过期
		}elseif($result['error'] == 1){//二维码已扫描，返回微信用户信息
			$headimgurl = $result['info']['headimgurl'];//微信头像
			$nickname 	= $result['info']['nickname'];//微信昵称
		}else{
			//Request Error
		}
	}
	/**
	* 解除绑定
	* @param 	number $userId		用户id
	* @param 	number $userType 	类型（0:CRM，1:超凡网，2:知友）
	* @return 	array
	*/
	public function unBindweixin($userId, $userType = 2)
	{
		$fieldName 	= $userType == 1 ? 'userId' : ($userType == 2 ? 'ucId' : 'sid');
		$param 		= array(
			'api_type' => 'unBindweixin',
			$fieldName => $userId
		);
		$json 	= $this->request($param);
		$result = json_decode($json, true);
		return $result;
	}
	/**
	* 获取微信用户信息
	* @param number $openId
	* @return array
	*/
	public function getUserInfo($openId)
	{
		$param = array(
			'api_type' 	=> 'getWxInfo',
			'openId' 	=> $openId
		);
		$json 	= $this->request($param);
		$result = json_decode($json, true);
		return $result;
	}

	/**
	* 推送微信消息接口
	* 
	* @author	Inna
	* @since	2015-09-16
	* 
	* @param	int		$sid		用户ID
	* @param	text	$content	消息内容
	* @param	string	$url		跳转地址
	* @param	int		$tpId		模板编号
	* @param	int		$userType	用户类型:0-CRM，1-超凡网，2-知友用户ID
	* 
	* 
	* 返回json格式的三种情况
	* 
	*	array(
	* 		errcode => -2,
	* 		errmsg => '微信ID或用户ID或微信内容不能为空！'
	*  );
	*  
	*  array(
	* 		errcode => -1,
	* 		errmsg => '该用户还未绑定微信！'
	*  );
	*  
	*  array(
	* 		errcode => 0,
	* 		errmsg => 'success'
	*  );
	* 	
	*/
	public function sendMsg($sid, $content, $url = '', $tpId = 1, $userType = 1)
	{
		$param = array(
			'api_type'	=> 'sendMsg',
			'sid'		=> $sid,
			'content'	=> $content,
			'tpId'		=> $tpId,
			'url'		=> $url,
			'userType'	=> $userType,
		);
		$json 	= $this->request($param);
		$result = json_decode($json, true);
		return $result;
	}
	/**
	* HTTP请求
	* @param array $param
	* @return boolean
	*/
	public function request($param)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, CRM_URL.'Api/weixin.php');
		$param['timestamp'] = time();
		curl_setopt(
			$ch, CURLOPT_POSTFIELDS,
			array_merge(
				array(
					'api_user' 	=> WEIXIN_API_USER,
					'api_key' 	=> md5(WEIXIN_API_KEY.$param['timestamp'])
				),
				$param
			)
		);
		$result = curl_exec($ch);
		if($result === false) {
			$result = curl_error($ch);
		}
		curl_close($ch);
		return $result;
	}
}