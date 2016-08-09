<?
/**
 * 调用config数据
 *
 * 
 *
 * @package	Util
 * @author	Xuni
 * @since	2015-06-16
 */
class LoadConfig
{
	private static $config;

	private static function load()
	{
		return include(ConfigDir.'/public.config.php');
	}

	/**
	 * 获取认证数据
	 * @author	void
	 * @since	2014-12-09
	 * 
	 * @access	public
	 * @param	string	$key	键
	 * @return	mixed(string|array)
	 */
	public static function get($key)
	{
		static $config = array();

		if ($key == 'all' && !empty($config)){
			return $config;
		}
				
		if ( !empty($config) && isset($config[$key]) ) {
			return $config[$key];
		}
		
		$config = self::load();
		//$config = empty($config) ? array() : $config;

		if ($key == 'all' && !empty($config)){
			return $config;
		}
				
		if ( !empty($config) && isset($config[$key]) ) {
			return $config[$key];
		}
		return array();
	}

}
?>