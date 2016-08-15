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

		$res 	= $this->load('adminuser')->getList($params, $page, $this->rowNum);

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

		$info = $this->load('adminuser')->getInfoById($this->userId);
		if ( getPasswordMd5($oldpass) != $info['password'] ){
			$this->redirect('原密码不正确', '');
		}

		$res = $this->load('adminuser')->changePass($this->userId, $newpass);
		if ( $res ) $this->redirect('修改成功', '');

		$this->redirect('修改失败', '');
	}

}
?>