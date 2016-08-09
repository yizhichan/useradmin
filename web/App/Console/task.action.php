<?
/**
 * 后台任务控制器
 *
 * 执行后台任务
 *
 * @package	Action
 * @author	void
 * @since	2015-11-20
 */
class TaskAction extends ConsoleAction
{	
	/**
	 * 执行后台任务
	 * @author	void
	 * @since	2015-11-20
	 *
	 * @access	public
	 * @return	void
	 */
	public function work()
	{
		print time();
	}
}
?>