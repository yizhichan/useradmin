<?php
/**
 * 项目入口
 */
set_time_limit(0);                                          //设置超时时间
$webDir = dirname(__FILE__);                                //获取当前路径
$libDir = realpath($webDir . '/../Spring');
      
define('WebDir', $webDir);                                  //定义项目路径
define('ActionDir', $webDir.'/App/Console');                //定义控制器存放路径
require($libDir.'/Spring.php');                             //载入框架入口文件
require(ConfigDir.'/app.config.php');                       //载入应用全局配置

return Spring::out();

?>
