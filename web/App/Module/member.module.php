<?
/**
 * 会员管理
 *
 * 会员列表页处理
 * 
 * @package	Model
 * @author	void
 * @since	2016-08-11
 */
class MemberModule extends AppModule
{
	
	public $models = array(
        'member'   		=> 'user',
	);
	
	/**
	* 获取会员数据
	* @author	hyand
	* @since	2016-03-02
	* @param 	string 		$trademark	商标数
	* @param 	string 		$class		类别
	* @param 	int 		$uid		用户id
	* @return	int			$count
	*/
	public function getMemberList()
	{
		$r = array();
		$r['order'] = array('id'=>'desc');
		$r['limit'] = 1000;

		return $this->import('member')->find($r);

	}

	public function getList($params, $page, $num)
	{
		$r = array();

		if ( $params['uid'] > 0 ){
			$r['eq']['id'] = $params['uid']; 
		}
		if ( !empty($params['mobile']) ){
			$r['eq']['mobile'] = $params['mobile']; 
		}

		$r['order'] = array('id'=>'desc');
		$r['page']	= $page;
		$r['limit']	= $num;

		return $this->import('member')->findAll($r);
	}

	public function getListOther($data)
	{
		if ( empty($data) || !is_array($data) ) return array();
		foreach ($data as $k => $v) {
			$data[$k]['isUserBuy'] 		= $this->load('buy')->isUserBuy($v['id']) > 0 ? true : false;
			$data[$k]['isMobileBuy'] 	= $this->load('buy')->isMobileBuy($v['mobile']) > 0 ? true : false;
			$data[$k]['isUserSale'] 	= $this->load('sale')->isUserSale($v['id']) > 0 ? true : false;
			$data[$k]['isMobileSale'] 	= $this->load('sale')->isMobileSale($v['mobile']) > 0 ? true : false;
		}
		return $data;
	}

	public function getInfoById($uid)
	{
		$r 			= array();
		$r['eq']	= array('id'=>$uid);
		$r['limit']	= 1;

		return $this->import('member')->find($r);
	}
	
}
?>