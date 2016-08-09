<?
/**
 * 一只蝉后台接口
 * 
 * @access	public
 * @package bi
 * @author	Xuni
 * @since	2016-07-04
 */
class TradeBi extends Bi
{
	/**
	 * 对外接口域名编号
	 */
	public $apiId = 9;

	/**
	 * 获取热门的商标
	 * 
	 * @access	public
	 * @param	int		$number		商标数量
	 *
	 * @return	array   
	 */
	public function getHotTm($class, $number=8)
	{
        //随机热门商标
        $random = array(3,9,25,33);
        $_class = $random[array_rand($random)];

		$param = array(
			'class'  	=> (intval($class) <= 0 || intval($class) > 45) ? $_class : intval($class),
			'limit' 	=> intval($number),
			);

		$params = array(
			'user'		=>	'api1010',
			'method'	=>	'getBanner',
			'data'		=>	$param,
		);
		$params['sign'] = $this->sign($params);
		$data = $this->request("openapi/request/", $params);

        if ( $data['code'] == '101' ){
            return $data['data'];
        }
        return array();
	}

    /**
     * 获取商标的包装信息
     * 
     * @access  public
     * @param   string     $number     商标号（144,88888）;多个用英文逗号分隔
     *
     * @return  array   
     */
    public function getSaleImg($number)
    {
        $param = array(
            'number' => $number,
        );
        $params = array(
            'user'      =>  'api1010',
            'method'    =>  'getSaleImg',
            'data'      =>  $param,
        );
        $params['sign'] = $this->sign($params);
        $data = $this->request("openapi/request/", $params);

        if ( $data['code'] == '101' ){
            return $data['data'];
        }
        return array();
    }

	/**
     * sign签名
     *
     * @todo        对数据进行签名，保证数据完整性
     * @return      string
     * @author      Xuni
     * @copyright   CHOFN
     * @since       2016-07-04
     */
    protected function sign($data)
    {
        unset($data['sign']);
        ksort($data, SORT_STRING);
        $apiKey = TRADE_MY_KEY;
        $sign   = md5( md5(serialize($data)).$apiKey );
        return $sign;
    }

}
?>