<?
/**
 * 应用表单组件基类
 *
 * 表单数据收集
 *
 * @package	Form
 * @author	void
 * @since	2015-11-20
 */
abstract class AppForm extends Form
{
	/**
	 * 错误输出格式[0消息框提示、1json格式提示]
	 */
	protected $format = 0;

	/**
	 * 验证规则
	 */
	protected $rules = array(
		'require'  =>  '/.+/',
		'email'    =>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
		'currency' =>  '/^\d+(\.\d+)?$/',
		'number'   =>  '/^\d+$/',
		'zip'      =>  '/^\d{6}$/',
		'int'	   =>  '/^[-\+]?\d+$/',
		'float'    =>  '/^[-\+]?\d+(\.\d+)$/',
		'english'  =>  '/^[A-Za-z]+$/',
		);

	/**
	 * 中断并输出提示信息
	 *
	 * @access	public
	 * @param   mixed	$error 提示信息
	 * @return	void
	 */
	public function stop($error = 'error')
	{
		if ( $this->format )
		{
			exit(json_encode($error));
		}
		else
		{
			MessageBox::halt($error);
		}
	}
}
?>