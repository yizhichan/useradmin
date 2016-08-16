<?
/**
 * 注册
 *
 * 注册
 *
 * @package	Action
 * @author	Xuni
 * @since	2016-08-10
 */
class RegisterAction extends AppAction
{

	/**
	 * 控制器默认方法
	 * @author	Xuni
	 * @since	2016-08-10
	 *
	 * @access	public
	 * @return	void
	 */
	public function index()
	{
		$uname 	= $this->input('uname', 'string', '');
		$upass 	= $this->input('upass', 'string', '');
		$upass2 = $this->input('upass2', 'string', '');

		if ( empty($uname) || empty($upass) || empty($upass2) ) {
			$this->redirect('请输入用户名或密码', '/login/?reg=1');
		}

		$info = $this->load('user')->getInfo($uname);
		if ( !empty($info) ) $this->redirect('用户名已存在', '/login/?reg=1');

		$uid = $this->load('user')->addUser($uname, $upass);
		if ( $uid ){
			$this->redirect('注册成功，请登录', '/login');
		}
		$this->redirect('注册失败', '/login/?reg=1');
	}

}
?>