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
        'user'   		=> 'user',
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

		return $this->import('user')->find($r);

	}

	public function getList($param, $page, $num)
	{
		$r = array();
		$r['order'] = array('id'=>'desc');
		$r['page']	= $page;
		$r['limit']	= $num;

		return $this->import('user')->findAll($r);
	}

	
}
?>