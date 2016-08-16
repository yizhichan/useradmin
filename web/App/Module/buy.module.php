<?
/**
 * 求购信息
 *
 * 求购信息
 * 
 * @package	Model
 * @author	void
 * @since	2016-08-11
 */
class BuyModule extends AppModule
{
	
	public $models = array(
        'buy'  	=> 'buyTrademark',
	);
	
	
	public function isUserBuy($uid)
	{
		$r = array();
		$r['eq'] 	= array('userId'=>$uid);

		return $this->import('buy')->count($r);
	}

	public function isMobileBuy($mobile)
	{
		$r = array();
		$r['eq'] 	= array('mobile'=>$mobile);

		return $this->import('buy')->count($r);
	}

	public function countUidMobile($uid, $mobile)
    {
        $r = array();
        $r['raw']    = " `userId` = '$uid' OR `mobile` = '$mobile' ";

        return $this->import('buy')->count($r);
    }

    public function getListByUidMoblie($uid, $mobile)
    {
    	$r = array();
        $r['raw']  	= " `userId` = '$uid' OR `mobile` = '$mobile' ";
        $r['limit']	= 10000;

        return $this->import('buy')->find($r);
    }
}
?>