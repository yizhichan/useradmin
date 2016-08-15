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
class AdminUserModule extends AppModule
{
	
	public $models = array(
        'user'   		=> 'adminuser',
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
	public function getInfo($uname)
	{
		$r = array();
		$r['eq'] 	= array('username'=>$uname);
		$r['limit'] = 1;

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

	public function addUser($uname, $upass)
	{
		if ( empty($uname) || empty($upass) ) return false;

		$data = array(
			'username' 	=> $uname,
			'password' 	=> getPasswordMd5($upass),
			'nickname' 	=> $uname,
			'isUse' 	=> 2,
			);
		return $this->import('user')->create($data);
	}
	
}
?>