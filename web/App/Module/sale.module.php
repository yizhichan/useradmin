<?
/**
 * 国内商标
 *
 * 国内商标
 * 
 * @package	Model
 * @author	void
 * @since	2016-08-11
 */
class SaleModule extends AppModule
{
	
	public $models = array(
        'sale'   		=> 'sale',
        'contact'   	=> 'saleContact',
        'tminfo'   		=> 'saleTminfo',
	);
	
	public function isUserSale($uid)
	{
		$r = array();
		$r['eq'] 	= array('uid'=>$uid);

		return $this->import('contact')->count($r);
	}


    public function isMobileSale($mobile)
    {
        $r = array();
        $r['eq']    = array('phone'=>$mobile);

        return $this->import('contact')->count($r);
    }
	
    public function countUidMobile($uid, $mobile)
    {
        $r = array();
        $r['raw']    = " `uid` = '$uid' OR `phone` = '$mobile' ";

        return $this->import('contact')->count($r);
    }

    public function getListByUidMoblie($uid, $mobile)
    {
        if ( $uid <= 0 ) return array();
        
        $r = array();
        if ( empty($mobile) ){
            $r['eq']    = array('uid'=>$uid);
        }else{
            $r['raw']   = " `uid` = '$uid' OR `phone` = '$mobile' ";
        }        
        $r['order'] = array('id'=>'desc');
        $r['limit'] = 10000;

        return $this->import('contact')->find($r);
    }

    public function countTodaySale()
    {
        $r = array();
        $r['raw']   = " `date` > ".strtotime(date("Y-m-d"));

        return $this->import('contact')->count($r);
    }
}
?>