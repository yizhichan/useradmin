<?
/**
 * 用户中心接口
 * 
 * @access	public
 * @package bi
 * @author	Xuni
 * @since	2015-11-05
 */
class PassportBi extends Bi
{
	/**
	 * 对外接口域名编号(默认为超凡网、其他待定)
	 */
	public $apiId = 2;


	/**
	 * 通过userID获取账户信息（可多个）
	 * 
	 * @access	public
	 * @param	array	$userIds	用户ID数组
	 *
	 * @return	array   返回账户所有信息(空为账户不存在)
	 */
	public function getListByIds($userIds)
	{
		
		$param = array(
			'ids' => $userIds,
			);
		return $this->request("passport/getListByIds/", $param);
	}

	/**
	 * 获取账户信息
	 * 
	 * @access	public
	 * @param	string	$account	登录账户
	 * @param	int		$cateId		账户标识(1邮件、2手机、3用户名、4用户id)
	 * @return	array   返回账户所有信息(空为账户不存在)
	 */
	public function get($account, $cateId=1)
	{
		$param = array(
			'account' => $account,
			'cateId'  => $cateId,
			);
		return $this->request("passport/get/", $param);
	}

	/**
	 * 检查账户是否存在
	 * 
	 * @access	public
	 * @param	string	$account	登录账户
	 * @param	int		$cateId		账户标识(1邮件、2手机、3用户名)
	 * @return	int     返回userId(0不存在、大于0存在)
	 */
	public function exist($account, $cateId=1)
	{
		$param = array(
			'account' => $account,
			'cateId'  => $cateId,
			);
		
		return $this->request("passport/exist/", $param);
	}

	/**
	 * 账户注册
	 *
	 * @access	public
	 * @param	string	$account	登录账户
	 * @param	int		$cateId		账户标识(1邮件、2手机、3用户名)
	 * @param	string	$password	登录密码
	 * @param	string	$ip			用户ip
	 * @return	int     返回userId(-1账户已存在、0失败或异常、大于0成功)
	 */
	public function register($account, $password, $cateId=1, $ip)
	{
		$param = array(
			'source'   => 2,//来源
			'ip'       => $ip,
			'account'  => $account,
			'cateId'   => $cateId,
			'password' => $password,
			);
		
		return $this->request("passport/register/", $param);
	}

	/**
	 * 账户登录
	 * 
	 * @access	public
	 * @param	string	$account	登录账户
	 * @param	int		$cateId		账户标识(1邮件、2手机、3用户名)
	 * @param	string	$password	登录密码
	 * @param	string	$ip			登录ip
	 * @return	array   返回账户信息(为空账户或密码错误、异常)
	 */
	public function login($account, $cateId, $password, $ip)
	{
		$param = array(
			'source'   => 2,//来源
			'ip'       => $ip,
			'account'  => $account,
			'cateId'   => $cateId,
			'password' => $password,
			);
		return $this->request("passport/login/", $param);
	}
	
	/**
	 * 修改密码
	 * 
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	string	$password	登陆密码
	 * @param	string	$newPwd		新密码
	 * @return	int     (-1账户不存在或密码错误、0失败或异常、1成功)
	 */
	public function changePwd($userId, $password, $newPwd)
	{
		$param = array(
			'userId'   => $userId,
			'password' => $password,
			'newPwd'   => $newPwd,
			);
		return $this->request("passport/changePwd/", $param);
	}

	/**
	 * 修改登陆手机
	 *
	 * @author	void
	 * @since	2014-11-17
	 *
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	string	$mobile		新手机号
	 *
	 * @return	array
	 */
	public function changeMobile($userId, $mobile)
	{
		$param = array(
			'userId'  => $userId,
			'mobile'  => $mobile,
		);
	
		return $this->request("passport/changeMobile/", $param);
	}

	/**
	 * 添加登陆手机
	 *
	 * @author	Xuni
	 * @since	2015-11-05
	 *
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	string	$mobile		新手机号
	 *
	 * @return	array
	 */
	public function addMobile($userId, $mobile)
	{
		$param = array(
			'userId'  => $userId,
			'mobile'  => $mobile,
		);
		return $this->request("passport/addMobile/", $param);
	}

	/**
	 * 修改登陆账户
	 * 
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	string	$account	新登录账户
	 * @param	int		$cateId		账户标识(1邮件、2手机)
	 * @return	int     -1账户只能为邮件或手机、0失败或异常、1成功
	 */
	public function changeEmail($userId, $account)
	{
		$param = array(
			'userId'  => $userId,
			'email' => $account,
			);

		return $this->request("passport/changeEmail/", $param);
	}

	/**
	 * 激活邮箱、手机
	 * 
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	int		$cateId		1邮件、2手机
	 * @return	int     (-1账户不存在、0失败或异常、1成功)
	 */
	public function active($userId, $cateId=1)
	{
		$param = array(
			'userId'  => $userId,
			);
		if ($cateId == 2){
			$url = "passport/activeMobile";
		}elseif($cateId == 1){
			$url = "passport/activeEmail";
		}
		return $this->request($url, $param);
	}
	
	
	
	/**
	 * 重置密码
	 * 
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	string	$newPwd		新密码
	 * @return	int     -1账户不存在、0失败或异常、1成功
	 */
	public function resetPwd($userId, $newPwd)
	{
		$param = array(
			'userId' => $userId,
			'newPwd' => $newPwd,
			);
		return $this->request("passport/resetPwd/", $param);
	}

	/**
	 * 注销账户
	 * 
	 * @access	public
	 * @param	int		$userId		用户id
	 * @return	int     0失败、1成功
	 */
	public function remove($userId)
	{
		$param = array(
			'userId'  => $userId,
			);
		return $this->request("passport/cancel/", $param);
	}


	/**
	 * 修改用户昵称
	 * 
	 * @access	public
	 * @param	int		$userId		用户id
	 * @param	string	$nickname	用户昵称
	 * @return	int     -1账户只能为邮件或手机、0失败或异常、1成功
	 */
	public function changeNickname($userId, $nickname)
	{
		$param = array(
			'userId'  => $userId,
			'nickname' => $nickname,
			);

		return $this->request("passport/changeNickname/", $param);
	}

}
?>