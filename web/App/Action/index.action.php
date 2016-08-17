<?
/**
 * 项目首页
 *
 * 首页内容展示
 *
 * @package	Action
 * @author	Xuni
 * @since	2016-08-10
 */
class IndexAction extends AppAction
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
		$todaySale 	= $this->load('sale')->countTodaySale();
		$todayBuy 	= $this->load('buy')->countTodayBuy();
		$todayReg 	= $this->load('member')->countTodayReg();

		$this->set('todaySale', $todaySale);
		$this->set('todayBuy', $todayBuy);
		$this->set('todayReg', $todayReg);
		$this->display();
	}
}
?>