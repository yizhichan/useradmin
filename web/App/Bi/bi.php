<?
/**
 * 业务集成基类
 *
 * 发送请求
 *
 * @package	Bi
 * @author	void
 * @since	2015-11-20
 */
abstract class Bi extends RpcClient
{
	/**
	 * 发送请求
	 * @author	void
	 * @since	2015-11-20
	 *
	 * @access	public
	 * @param	string	$name	请求的接口
	 * @param	string  $param	提交的参数
	 * @return	array
	 */
	public function request($name, $param)
	{
		$response = parent::request($name, $param);
		if ( empty($response) ) {
			return array('code' => '404', 'msg' => '系统异常', 'data' => '');
		}
		
		return $response;
	}
}
?>