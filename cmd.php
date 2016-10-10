<?
if (php_sapi_name() != "cli") {
    exit("only run in command line mode \n");
}
/**
 * 项目入口
 注意！！！
 使用时，请将此文件放到与项目目录平级并设置下面的项目目录！否则将影响cron定时任务与队列处理！
 */
set_time_limit(0);                                          //设置超时时间
$web    = 'web';               //设置项目目录名称
$cmdDir = dirname(__FILE__);                                //获取当前路径
$webDir = realpath($cmdDir . '/' . $web);
$libDir = realpath($webDir . '/../Spring');
define('Uri', isset($argv[1]) ? $argv[1] : 'index/index/');	//保存命令行参数		
define('CmdDir', $cmdDir);                                  //定义项目路径
define('WebDir', $webDir);									//定义项目路径
define('ActionDir', $webDir.'/App/Console');				//定义控制器存放路径
require($libDir.'/Spring.php');								//载入框架入口文件
require(ConfigDir.'/app.config.php');						//载入应用全局配置
define('PHPPath', 'php');
Spring::run(2);												//启动框架

?>