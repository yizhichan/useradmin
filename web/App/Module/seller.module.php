<?
/**
 * 出售者平台 
 *
 * 出售者平台
 * 
 * @package	Model
 * @author	Xuni
 * @since	2016-09-18
 */
class SellerModule extends AppModule
{
	
	public $models = array(
        'quo'  	=> 'quotation',
	);
	
	public function getQuotationList($uid)
    {
        if ( empty($uid) || $uid <= 0 ) return array();

        $r['eq']    = array('uid'=>$uid);
        $r['limit'] = 1000;

        $list = $this->import('quo')->find($r);
        return $list;
    }
	

}
?>