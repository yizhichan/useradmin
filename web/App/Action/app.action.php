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
	public $rowNum      = 15;

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
			'index'			=> '*',
			'login'			=> '*',
			'register'		=> '*',
			'buybrand'		=> '*',
			'lostpassword'	=> '*',
			'authcode'		=> '*',
			'test'	=> '*',

		);
		if(strpos($_SERVER['HTTP_REFERER'],SELLER_URL)!==false){ //如果是出售者平台过来的记录来源
		    Session::set("source",1);
		}
		$allow  = false;
		$mod	= $this->mod;
		if ( isset($mods[$mod]) ) {
			if ( is_array($mods[$mod]) ) {
				$allow = in_array($action, $mods[$mod]) ? true : false;

			} else {
				$allow = $mods[$mod] == '*' ? true : false;
			}
		}
		$isLogin = $this->setLoginUser();
		if ( !$allow && !$isLogin) {
			$this->redirect('', '/login/index');
			exit;
		}
		//$ucmenu = require ConfigDir.'/menu.config.php';
		//$this->set('ucmenu',$ucmenu);
		$this->set('current_url', '/'.$this->mod .'/' . $this->action.'/');
		$this->set('title',$this->pageTitle);
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
	/**
	* 设置用户信息数据
	*
	* @author  haydn
	* @since   2016-01-20
	* @access  public
	* @return  void
	*/
	protected final function setLoginUser()
	{
		$uKey 			= C('PUBLIC_USER');
		$this->token 	= LoginAuth::get($uKey);
		if(!empty($this->token)) {
			$session = $this->import('sessions', 2)->getByToken($this->token);
			if( isset($session['userId']) && $session['cookieId'] == $this->token ) {
				$userInfo   = $this->import('user', 2)->get($session['userId']);
				$mbinfo     = array(
					'id'        => $session['userId'],
					'mobile'    => $userInfo['mobile'],
					'email'    	=> $userInfo['email'],
					'cfwId'     => $userInfo['cfwId'],
					'cateId'	=> $session['type']
				);
				$this->userInfo = $mbinfo;
				$this->isLogin  = true;
				$this->setUserView();
				return true;
			}
		}
		return false;
	}

	/**
	* 设置用户信息数据到页面
	* @author  haydn
	* @since   2016-01-20
	* @access  public
	* @return  void
	*/
	protected final function setUserView()
	{
		$this->userinfo					= $this->load('user')->getInfoById( $this->userInfo['id'] );
		$message						= $this->load('message')->getPageList($this->userinfo['id'],1,30,'',false);//我的消息
		$this->userInfo['specname'] 	= $this->userinfo['specname'];
		$this->userInfo['mobile_hide'] 	= $this->userinfo['mobile_hide'];
		$this->userInfo['email_hide'] 	= $this->userinfo['email_hide'];
		$this->set('nickname', $this->userinfo['firstname']);//名称
		$this->set('userMobile', $this->userInfo['mobile_hide']);//手机
		$this->set('userEmail', $this->userInfo['email_hide']);//邮箱
		$this->set('userInfo', $this->userinfo);//用户数组
		$this->set('cfwId', $this->userInfo['cfwId']);//超凡网id
		$this->set('isLogin', $this->isLogin);//是否登录
		$this->set('messagecount', $message['total']);//信息数量
		$this->myStaff();
	}

	/**
	* 删除用户信息数据
	* @author  haydn
	* @since   2016-01-20
	* @access  public
	* @return  void
	*/
	protected final function removeUser()
	{
		$this->nickname     = '';
		$this->userMobile   = '';
		$this->cfwId        = '';
		$this->userInfo     = '';
		$this->isLogin      = false;
		$this->setUserView();
	}
	/**
	* 我的顾问
	* @since    2016-01-21
	* @author   haydn
	* @return   void
	*/
	protected final function myStaff()
	{
		$staffInfo  = array();
		$userId 	= $this->userInfo['id'];
		$aidArr 	= $this->load('relation')->getRelationList( $userId );
		if( !empty($aidArr) ){
			$arr = $this->load('relation')->getStaffInfo( array('aid' => $aidArr) );
			if( $arr['code'] == 1 ){
				$staffInfo = $arr['data'];
			}
		}
		$this->set('staffInfo', $staffInfo);
	}
	/**
	* 验证js跨域
	* @author   haydn
	* @since    2016-01-27
	* @return   bool		验证来源的合法性（true:合法 false:非法）
	*/
	protected final function checkSource()
	{
		$this->recordLook();
		$is			= false;
		$timestamp	= $_GET['timestamp'];
		if( time() - $timestamp < 1000 ){
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
	* 浏览
	* @author   haydn
	* @since    2016-04-11
	* @return   bool
	*/
	protected final function recordLook($url = '')
	{
		$userId = !empty($this->userInfo['id']) ? $this->userInfo['id'] : 0;
		//$url	= 'http://shansoo.net/trademark/detail/?id=10365769';
		//$url	= 'http://t.chofn.net/d-12163945-25.html';
		$url	= !empty($url) ? $url : $_SERVER["HTTP_REFERER"];
		$array	= parse_url($url);
		if( !empty($array['query']) ){
			$host	= $array['host'];
			$query	= $array['query'];
			parse_str($query);
			$tid	= !empty($id) && $id > 0 ? $id : 0;
		}else{
			$pathArr= explode('-',$array['path']);
			$tid	= !empty($pathArr[1]) ? $pathArr[1] : 0;
		}
		if( $tid > 0 ){
			$this->load('browse')->addTidBrowse($tid,$url,$userId);
		}
	}
}
?>