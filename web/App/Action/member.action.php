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
			$doudou				= $this->load('member')->getMemberDoudou($uid);
			$info['doudou']		= intval($doudou['amount']);
		}
		$referr = $this->getReferrUrl('member_view');
		$this->set('referr', $referr);
		$this->set('info', $info);
		$this->set('SOURCE', C('SOURCE'));
		$this->display();
	}

	public function buyer()
	{
		$uid 	= $this->input('uid', 'int', '0');
		$type 	= $this->input('t', 'int', '1');

		$info 	= $this->load('member')->getInfoById($uid);
		if ( !empty($info) ){
			$info['buyList'] 		= $this->load('buy')->getListByUidMoblie($uid, $info['mobile']);
			$info['collectList']	= $this->load('member')->getMemberCollect($uid);
		}
		$referr = $this->getReferrUrl('member_view');
		$this->set('referr', $referr);
		$this->set('info', $info);
		$this->set('SOURCE', C('SOURCE'));
		$this->display();
	}

	public function seller()
	{
		$uid 	= $this->input('uid', 'int', '0');
		$info 	= $this->load('member')->getInfoById($uid);
		if ( !empty($info) ){
			$info['saleList'] 	= $this->load('sale')->getListByUidMoblie($uid, $info['mobile']);
			$info['doudouList'] = $this->load('member')->getMemberDouLog($uid);
		}
		$referr = $this->getReferrUrl('member_view');
		$this->set('referr', $referr);
		$this->set('info', $info);
		$this->set('SOURCE', C('SOURCE'));
		$this->display();
	}

	public function update()
	{
		$uid 	= $this->input('uid', 'int', 0);
		$name 	= $this->input('name', 'string', '');
		if ( $uid <= 0 || !in_array($name, array('nickname','doudou')) ){
			$this->returnAjax(array('code'=>2,'msg'=>'参数错误'));
		}

		switch ($name) {
			case 'nickname':
				$val 	= $this->input('value', 'string', '');
				if ( empty($val) ) $this->returnAjax(array('code'=>2,'msg'=>'昵称不能为空'));
				$flag = $this->load('member')->updateNickname($uid, $val);
				break;			
			case 'doudou':
				$val 	= $this->input('value', 'int', 0);
				if ( !is_int($val) || $val < 0 ) $this->returnAjax(array('code'=>2,'msg'=>'蝉豆应为数字且不能为负数'));
				$flag = $this->load('member')->updateDoudou($uid, $val);
				break;
		}
		if ( $flag ) $this->returnAjax(array('code'=>1,'msg'=>'操作成功'));
		$this->returnAjax(array('code'=>2,'msg'=>'操作失败'));
	}

}
?>