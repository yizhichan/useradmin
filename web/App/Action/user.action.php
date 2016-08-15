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

		$res 	= $this->load('member')->getList($params, $page, $this->rowNum);

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
		$this->display();
	}

}
?>