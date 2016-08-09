<?
/**
 * 用户中心接口
 * 
 * @access	public
 * @package bi
 * @author	Xuni
 * @since	2015-11-05
 */
class TradminBi extends Bi
{
	public $apiId = 9;

	//出售商标
	public function insertSale($data)
	{
		$actionName = 'systemapi';
		$funName     = 'addSale';
		/*
		$data = array(
			'uid'           => '580',
			'number'        => '144',
			'phone'         => '13551112451',
			'contact'       => '标大帅',
			'price'         => 123456,
			'type'          => 1,
			'source'        => 1,
		);*/
		$params = array(
			'user' => 'api1010',
			'sign' => $this->sign($data),
			'data' => $data,
			);
		return $this->request("systemapi/addSale/", $params);
	}
	//删除出售商标
	public function deleteSale($data)
	{
		$actionName = 'systemapi';
		$funName     = 'cancelContact';
		/*
		$data = array(
			'uid'           => '580',
			'number'        => '144',
		);*/
		$params = array(
			'user' => 'api1010',
			'sign' => $this->sign($data),
			'data' => $data,
			);

        $t = $this->request("systemapi/cancelContact/", $params);
        return $t;

	}

	//联系人修改价格
	public function updatePrice($data)
	{
		$actionName = 'systemapi';
		$funName     = 'updateContactPrice';
		/*
		$data = array(
			'cid'           => '580',
			'price'        => '144',
		);*/
		$params = array(
			'user' => 'api1010',
			'sign' => $this->sign($data),
			'data' => $data,
			);

        $t = $this->request("systemapi/updateContactPrice/", $params);
        return $t;

	}
    public function sign($data)
    {
        ksort($data, SORT_STRING);
        $apiKey = 'JyZyZcXmChOfN2016ZxWlQkF';
        $sign   = md5( md5(serialize($data)).$apiKey );
        return $sign;
    }
}
?>