<?

$web    = 'web';                                  //设置项目目录名称
$cmdDir = dirname(__FILE__);                        //获取当前路径
$webDir = realpath($cmdDir . '/' . $web);
$libDir = realpath($webDir . '/../Spring');
define('WebDir', $webDir);                          //设置项目目录
require_once($libDir.'/Spring.php');                     //载入框架入口文件
require_once(LibDir.'/Util/Tool/MakeCode.php');          //载入代码生成工具

//指定数据库名、表前缀
$configs = array(
    array(
        'name'      => 'useradmin',
        'db'        => 'useradmin',
        'prefix'    => 'ua_',
        'contain'   => '*',
        ),
    array(
        'name'      => 'trade',
        'db'        => 'trade_new',
        'prefix'    => 't_',
        'contain'   => '*',
        ),
    array(
        'name'      => 'my',
        'db'        => 'trade_new',
        'prefix'    => 'my_',
        'contain'   => '*',
        ),
    array(
        'name'      => 'seller',
        'db'        => 'trade_new',
        'prefix'    => 's_',
        'contain'   => '*',
        ),
    array(
        'name'      => 'trademark',
        'db'        => 'trademarkOnline',
        'prefix'    => 'tm_',
        'contain'   => array(
                'tm_imgurl',
                'tm_proposer',
                'tm_proposer_new',
                'tm_trademark',
                'tm_second_status',
                'tm_status_new',
                'tm_tmclass_group',
                'tm_tmclass_item',
                'tm_agent',
            ),
        ),
    );
Spring::init();
//指定数据库配置文件存放路径
MakeCode::$configFileDir = WebDir.'/Config/Db';
MakeCode::create($configs);
?>