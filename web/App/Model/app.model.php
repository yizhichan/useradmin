<?
/**
* 应用基础模型基类
*
* 存放基础模型公共方法
* 
* @package	Model
* @author	void
* @since	2015-11-20
*/
abstract class AppModel extends Model
{
    /**
    * sql调试开关
    */
    public $debug     = true;

    /**
    * 查询字段
    */
    protected $fields = array(
        '1' => 'id',
    );

    /**
    * 初始化(构造模型时执行)
    *
    * @access	public
    * @return	void
    */
    public function init()
    {
    }

    /**
    * 通过主键id获取信息(1条数据)
    * @author	void
    * @since	2015-11-20
    *
    * @access	public
    * @param	int		$id		主键id
    * @param	string	$field	字段名(只支持一个)
    * @return	mixed   array|string
    */
    public function get($id, $field = '')
    {
        return $this->findOne($id, $field);
    }

    /**
    * 通过字段名获取信息(1条数据)
    * @author	void
    * @since	2015-11-20
    *
    * @access	public
    * @param	int		$key	字段key
    * @param	string	$value	字段值
    * @return	array
    */
    public function getByField($key, $value)
    {
        $field   = $this->fields[$key];
        $r['eq'] = array($field => $value);

        return $this->find($r);
    }
    /**
    * 获取业务对象(系统对接时使用)
    * @author   void
    * @since    2016-01-28
    *
    * @access   public
    * @param    string  $name    业务代理类名
    * @return   object  返回业务对象
    */
    public function importBi($name)
    {
        static $config = array();
        if ( empty($config) ) {
            require(ConfigDir.'/Extension/service.config.php');
        }
        
        static $objList = array();
        if ( isset($objList[$name]) && $objList[$name] ) {
            return $objList[$name];
        }

        $file = BiDir.'/'.strtolower($name).'.bi.php';
        require($file);
        $className      = $name.'Bi';
        $bi             = new $className();
        $bi->url        = $config[$bi->apiId]['url'];
        $objList[$name] = $bi;
        
        return $bi;
    }
}
?>