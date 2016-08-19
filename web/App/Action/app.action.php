<?
/**
* 应用控制器基类
*
* 存放控制器公共方法
*
* @package	Action
* @author	void
* @since	2015-11-20
*/
abstract class AppAction extends Action
{
	/**
	* 每页显示多少行
	*/
	public $rowNum      = 10;

	public $userInfo    = array();

	public $isLogin     = false;

	public $token;
	
	public $pageTitle   = "用户管理";
	/**
	* 前置操作(框架自动调用)
	* @author	void
	* @since	2015-11-20
	*
	* @access	public
	* @return	void
	*/
	public function before()
	{
		$mods = array(
			'login'			=> '*',
			'register'		=> '*',
			'test'			=> '*',

		);

		$allow  = false;
		$mod	= $this->mod;
		if ( isset($mods[$mod]) ) {
			if ( is_array($mods[$mod]) ) {
				$allow = in_array($action, $mods[$mod]) ? true : false;
			} else {
				$allow = $mods[$mod] == '*' ? true : false;
			}
		}
		$isLogin = $this->getLoginUser();

		//有用户账号就必须判断账号是否有效
		if ( !empty($this->username) ){
			$userinfo = $this->load('user')->getInfo($this->username);
			if(empty($userinfo) || $userinfo['isUse'] != 1) {
				$this->removeUser();
				$this->redirect('未登录或权限不够', '/login');
			}			
		}
		if ( !$allow && !$isLogin) {
			$this->redirect('请登录后操作', '/login');
			exit;
		}

		$this->set('_title_', $this->pageTitle);
		$this->set('_mod_', $this->mod);
		$this->set('_act_', $this->action);
	}

	/**
	* 后置操作(框架自动调用)
	* @author	void
	* @since	2015-11-20
	*
	* @access	public
	* @return	void
	*/
	public function after()
	{
		//自定义业务逻辑
	}


	/**
	* 输出json数据
	*
	* @author	Xuni
	* @since	2015-11-06
	*
	* @access	public
	* @return	void
	*/
	protected function returnAjax($data=array())
	{
		$jsonStr = json_encode($data);
		exit($jsonStr);
	}

	protected final function getLoginUser()
	{
		$userInfo = unserialize( Session::get(C('COOKIE_USER')) );
		return $this->setLoginUser($userInfo, $userInfo['remember']);
	}

	/**
	* 设置用户信息数据
	*
	* @author  Xuni
	* @since   2016-01-20
	* @access  public
	* @return  void
	*/
	protected final function setLoginUser($info, $remember='')
	{
		$this->isLogin 	= false;

		if ( empty($info) || empty($info['id']) ){
			$this->removeUser();
			return false;
		}
		$info['remember'] 	= $remember;
		
		$this->isLogin 		= true;
		$this->userId 		= $info['id'];
		$this->nickname 	= $info['nickname'];
		$this->username 	= $info['username'];
		$this->isUse 		= $info['isUse'];

		if ( $remember ){
			Session::set(C('COOKIE_USER'), serialize($info), 0);
		}else{
			Session::set(C('COOKIE_USER'), serialize($info));
		}
		
		$this->setUserView();
		return true;
	}

	/**
	* 设置用户信息数据到页面
	* @author  Xuni
	* @since   2016-01-20
	* @access  public
	* @return  void
	*/
	protected final function setUserView()
	{
		$this->set('isLogin', $this->isLogin);
		$this->set('userId', $this->userId);
		$this->set('username', $this->username);
		$this->set('nickname', $this->nickname);
		$this->set('isUse', $this->isUse);
	}

	/**
	* 删除用户信息数据
	* @author  Xuni
	* @since   2016-01-20
	* @access  public
	* @return  void
	*/
	protected final function removeUser()
	{
		$this->userId     	= 0;
		$this->nickname     = '';
		$this->userMobile   = '';
		$this->isLogin      = false;
		$this->setUserView();
		Session::remove(C('COOKIE_USER'));
	}
	
	//获取来源页面地址并保存
	protected function getReferrUrl($action)
	{
		//配置项
		$referrArr 	= array(
			'member_view' => '/member/index',
			); 
		if ( empty($referrArr[$action]) ) return '/index/';

		$_referr 	= Session::get($action);

		if ( empty($_referr) ){
			if ( strpos($_SERVER['HTTP_REFERER'], $referrArr[$action]) !== false ){
				Session::set($action, $_SERVER['HTTP_REFERER']);
			}else{
				Session::set($action, $referrArr[$action]);
			}
		}else{
			if ( strpos($_SERVER['HTTP_REFERER'], $referrArr[$action]) !== false ){
				Session::set($action, $_SERVER['HTTP_REFERER']);
			}
		}
		return Session::get($action);
	}
}
?>