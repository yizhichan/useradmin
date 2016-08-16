<?
/**
 * 会员列表
 *
 * 会员列表显示
 *
 * @package	Action
 * @author	Xuni
 * @since	2016-08-10
 */
class UserAction extends AppAction
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
		$page 	= $this->input('page', 'int', '1');
		$params = array();
		$res 	= $this->load('user')->getList($params, $page, $this->rowNum);

		$total 	= empty($res['total']) ? 0 : $res['total'];
		$list 	= empty($res['rows']) ? array() : $res['rows'];

		$pager 	= $this->pager($total, $this->rowNum, 10, 'active');
		$pBar 	= empty($list) ? '' : getPageBar($pager, true);

		$result = array();
//debug($pBar);
		$this->set("pageBar", $pBar);
		$this->set('list', $list);//debug($list);
		$this->display();
	}

	public function changePass()
	{
		if ( $this->isPost() ){
			$this->doChangePass();
		}
		$this->display();
	}

	protected function doChangePass()
	{
		$oldpass 	= $this->input('oldpass', 'string', '');
		$newpass 	= $this->input('newpass', 'string', '');
		$newpass2 	= $this->input('newpass2', 'string', '');

		if ( empty($oldpass) )  $this->redirect('请输入原密码', '');
		if ( empty($newpass) )  $this->redirect('请输入新密码', '');
		if ( empty($newpass2) )  $this->redirect('请输入重复新密码', '');
		if ( $newpass2 != $newpass )  $this->redirect('新密码两次输入不同', '');

		$info = $this->load('user')->getInfoById($this->userId);
		if ( getPasswordMd5($oldpass) != $info['password'] ){
			$this->redirect('原密码不正确', '');
		}

		$res = $this->load('user')->changePass($this->userId, $newpass);
		if ( $res ) $this->redirect('修改成功', '');

		$this->redirect('修改失败', '');
	}

	public function unUse()
	{
		if ( !$this->isAjax() ) return false;
		$uid 	= $this->input('uid', 'int', '');

		if ( $uid <= 0 ) $this->returnAjax(array('code'=>2,'msg'=>'参数错误'));

		$flag = $this->load('user')->unUse($uid);
		if ( $flag ) $this->returnAjax(array('code'=>1,'msg'=>'操作完成'));
		$this->returnAjax(array('code'=>2,'msg'=>'操作失败'));
	}

	public function setUse()
	{
		if ( !$this->isAjax() ) return false;
		$uid 	= $this->input('uid', 'int', '');

		if ( $uid <= 0 ) $this->returnAjax(array('code'=>2,'msg'=>'参数错误'));

		$flag = $this->load('user')->setUse($uid);
		if ( $flag ) $this->returnAjax(array('code'=>1,'msg'=>'操作完成'));
		$this->returnAjax(array('code'=>2,'msg'=>'操作失败'));
	}

}
?>