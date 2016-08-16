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
class MemberAction extends AppAction
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
		$params['uid'] 		= $this->input('uid', 'int', '');
		$params['mobile'] 	= $this->input('mobile', 'string', '');

		$res 	= $this->load('member')->getList($params, $page, $this->rowNum);

		$total 	= empty($res['total']) ? 0 : $res['total'];
		$list 	= empty($res['rows']) ? array() : $res['rows'];

		$pager 	= $this->pager($total, $this->rowNum, 10, 'active');
		$pBar 	= empty($list) ? '' : getPageBar($pager, true);

		$list 	= $this->load('member')->getListOther($list);

		$this->set('search', $params);
		$this->set("pageBar", $pBar);
		$this->set('list', $list);//debug($list);
		$this->display();
	}

	public function view()
	{
		$uid 	= $this->input('uid', 'int', '0');
		$info 	= $this->load('member')->getInfoById($uid);
		if ( !empty($info) ){
			$info['buyCount'] 	= $this->load('buy')->countUidMobile($uid, $info['mobile']);
			$info['saleCount'] 	= $this->load('sale')->countUidMobile($uid, $info['mobile']);

			$info['buyList'] 	= $this->load('buy')->getListByUidMoblie($uid, $info['mobile']);
			$info['saleList'] 	= $this->load('sale')->getListByUidMoblie($uid, $info['mobile']);
		}

		$this->set('info', $info);
		$this->set('SOURCE', C('SOURCE'));
		$this->display();
	}

	// public function ajaxList()
	// {
	// 	$list = $this->load('member')->getMemberList();
	// 	$flag = array('data'=>$list);
	// 	$this->returnAjax($flag);
	// }

}
?>