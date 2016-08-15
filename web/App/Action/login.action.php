<?
/**
 * 登录
 *
 * 登录
 *
 * @package	Action
 * @author	Xuni
 * @since	2016-08-10
 */
class LoginAction extends AppAction
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
		$reg = $this->input('reg', 'string', '0');
		$this->set('reg', $reg);

		if ( $this->isPost() ){			
			$this->login();
		}
		$this->display();
	}

	protected function login()
	{
		$uname = $this->input('uname', 'string', '');
		$upass = $this->input('upass', 'string', '');

		if ( empty($uname) || empty($upass) ) {
			$this->redirect('请输入用户名或密码', '/login');
		}

		$info = $this->load('adminuser')->getInfo($uname);
		if ( empty($info) ) $this->redirect('用户名或密码错误', '/login');

		if ( getPasswordMd5($upass) == $info['password'] )
		{
			$this->redirect('登录成功，请稍等', '/index');
		}

		$this->redirect('用户名或密码错误', '/login');
	}
}
?>