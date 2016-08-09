<?php
/**
 * 外部引用默认类
 *
 * @package Action
 * @author  Xuni
 * @since   2016-05-20
 */
class includeAction extends Action
{

    public function index(){}

    /**
     * 继承父类，变为公用方法
     */
    public function load($name)
    {
        return parent::load($name);
    }

    /**
     * 继承父类，变为公用方法
     */
    public function com($cacheId)
    {
        return parent::com($cacheId);
    }

    /**
     * 获取输入参数
     *
     * @access    protected
     * @param    string    $name    参数名
     * @param    string    $type    参数类型
     * @param    int        $length    参数长度(0不切取)
     * @return    mixed(int|float|string|array)
     */
    protected function getParam($name, $type = 'string', $length = 0)
    {
        $types = array('int', 'float', 'array', 'string');
        if ( !in_array($type, $types) )
        {
            return '';
        }

        $value = isset($this->input[$name]) ? $this->input[$name] : '';
        
        if ( empty($value) )
        {
            if ( $type == 'string' )
            {
                return '';
            }

            if ( $type == 'array' )
            {
                return array();
            }
        }

        if ( $type == 'int' )
        {
            return intval($value);
        }

        if ( $type == 'float' )
        {
            return $length == 0 
                ? sprintf("%.2f", floatval($value)) 
                : sprintf("%.{$length}f", floatval($value));
        }

        if ( $type == 'array' )
        {
            return $value;
        }

        $length = $length ? intval($length) : 0;
        return $length ? substr($value, 0, $length) : $value;             
    }

}
