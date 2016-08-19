<?
/**
 * 会员管理
 *
 * 会员列表页处理
 * 
 * @package Model
 * @author  Xuni
 * @since   2016-08-11
 */
class MemberModule extends AppModule
{
    
    public $models = array(
        'member'        => 'user',
        'collect'       => 'userCollect',
        'dou'           => 'total',
        'douLog'        => 'totalLog',
        'addition'      => 'userAddition',
    );
    
    /**
    * 获取会员数据
    * @author   Xuni
    */
    public function getList($params, $page, $num)
    {
        $r = array();

        if ( $params['uid'] > 0 ){
            $r['eq']['id'] = $params['uid']; 
        }
        if ( !empty($params['mobile']) ){
            $r['eq']['mobile'] = $params['mobile']; 
        }

        $r['order'] = array('id'=>'desc');
        $r['page']  = $page;
        $r['limit'] = $num;

        return $this->import('member')->findAll($r);
    }

    //获取用户其他信息
    public function getListOther($data)
    {
        if ( empty($data) || !is_array($data) ) return array();
        foreach ($data as $k => $v) {
            $data[$k]['isUserBuy']      = $this->load('buy')->isUserBuy($v['id']) > 0 ? true : false;
            $data[$k]['isMobileBuy']    = $this->load('buy')->isMobileBuy($v['mobile']) > 0 ? true : false;
            $data[$k]['isUserSale']     = $this->load('sale')->isUserSale($v['id']) > 0 ? true : false;
            $data[$k]['isMobileSale']   = $this->load('sale')->isMobileSale($v['mobile']) > 0 ? true : false;
        }
        return $data;
    }

    //通过用户ID获取用户信息
    public function getInfoById($uid)
    {
        $r          = array();
        $r['eq']    = array('id'=>$uid);
        $r['limit'] = 1;

        return $this->import('member')->find($r);
    }

    //统计今天注册的会员
    public function countTodayReg()
    {
        $r          = array();
        $r['raw']   = " `regDate` > ".strtotime(date("Y-m-d"));

        return $this->import('member')->count($r);
    }

    //获取用户收藏商品列表
    public function getMemberCollect($uid)
    {
        if ( $uid <= 0 ) return array();

        $r = array();
        $r['eq']    = array('userId'=>$uid,'source'=>'1');
        $r['order'] = array('id'=>'desc');
        $r['limit'] = 5000;

        return $this->import('collect')->find($r);
    }

    //获取用户蝉豆日志
    public function getMemberDouLog($uid)
    {
        if ( $uid <= 0 ) return array();

        $r = array();
        $r['eq']    = array('uid'=>$uid);
        $r['order'] = array('id'=>'desc');
        $r['limit'] = 5000;

        return $this->import('douLog')->find($r);
    }
    
    //获取用户蝉豆数量
    public function getMemberDoudou($uid)
    {
        if ( $uid <= 0 ) return array();

        $r = array();
        $r['eq']    = array('uid'=>$uid);
        $r['limit'] = 1;

        return $this->import('dou')->find($r);
    }

    //更新用户蝉豆数量
    public function updateDoudou($uid, $num)
    {
        if ( $uid <= 0 ) return false;
        if ( $num < 0 ) return false;

        $r['eq']    = array('uid'=>$uid);
        $doudou     = $this->import('dou')->find($r);

        if (  empty($doudou) ){            
            $data = array(
                'uid'       => $uid,
                'amount'    => $num,
                );

            $flag   = $this->import('dou')->create($data);
            $change = $num;
            $type   = 1;
            $desc   = '后台增加';
        }else{
            if ( $doudou['amount'] == $num ) return true;

            $change = abs($num - $doudou['amount']);
            $data   = array('amount'=>intval($num));
            $flag   = $this->import('dou')->modify($data, $r);
            $change = abs($num - $doudou['amount']);
            if ( ($num - $doudou['amount']) < 0 ){
                $type   = 2;
                $desc   = '后台扣减';
            }else{
                $type   = 1;
                $desc   = '后台增加';
            }
            
        }

        if ( $flag ){            
            $this->addDoudouLog($uid, $change, $type, $desc);
            return true;
        }
        return false;
    }

    //添加蝉豆日志
    public function addDoudouLog($uid, $change, $type=1, $desc='后台增加')
    {
        if ( $uid <= 0 ) return false;
        if ( !is_int($change) ) return false;

        $data = array(
            'uid'       => $uid,
            'amount'    => $change,
            'types'     => $type,
            'note'      => $desc
            );
        return $this->import('douLog')->create($data);
    }

    //更新用户昵称
    public function updateNickname($uid, $name)
    {
        if ( $uid <= 0 ) return false;

        $r['eq']    = array('id'=>$uid);
        $data       = array('nickname'=>$name);
        return $this->import('member')->modify($data, $r);
    }

    //更新用户新商品审核状态
    public function updateVerify($uid, $verify)
    {
        if ( $uid <= 0 ) return false;

        $verify     = $verify ? '1' : '2';
        $r['eq']    = array('id'=>$uid);
        $data       = array('isVerify'=>$verify);
        return $this->import('member')->modify($data, $r);
    }

    //更新用户密码
    public function updatePassword($uid, $password)
    {
        if ( $uid <= 0 ) return false;
        if ( empty($password) ) return false;

        $info       = $this->load('member')->getInfoById($uid);
        $password   = getPasswordMd5($password, $info['20f2e7']);
        $r['eq']    = array('id'=>$uid);
        $data       = array('password'=>$password);
        return $this->import('member')->modify($data, $r);
    }

    public function updateIsUse($uid, $isUse)
    {
        if ( $uid <= 0 ) return false;

        $isUse      = $isUse ? '1' : '2';
        $r['eq']    = array('id'=>$uid);
        $data       = array('isUse'=>$isUse);
        return $this->import('member')->modify($data, $r);
    }

    //添加用户扩展
    public function addAddition($uid, $type, $desc)
    {        
        if ( $uid <= 0 ) return false;
        if ( $type <= 0 || $type > 254 ) return false;
        if ( empty($desc) ) return false;

        $data = array(
            'uid'   => $uid,
            'type'  => $type,
            'desc'  => $desc,
            'opId'  => $this->userId,
            );
        return $this->import('addition')->create($data);
    }

    public function existAddition($uid, $type, $desc)
    {
        if ( $uid <= 0 ) return false;
        if ( $type <= 0 || $type > 254 ) return false;
        if ( empty($desc) ) return false;

        $r['eq'] = array(
            'uid'   => $uid,
            'type'  => $type,
            'desc'  => $desc
            );
        return $this->import('addition')->count($r);
    }

    //删除相关用户扩展
    public function deleteAddition($aid)
    {
        if ( $aid <=0 ) return false;

        $r['eq'] = array('id'=>$aid);
        return $this->import('addition')->remove($r);
    }

    //更新用户联系方式
    public function updateContactInfo($uid, $info)
    {
        if ( $uid <= 0 ) return false;
        if ( empty($info) ) return false;

        $r['eq'] = array(
            'uid'   => $uid,
            'type'  => 3,
            );
        $_info = $this->import('addition')->find($r);
        if ( empty($_info) ){
            $data = array(
                'uid'   => $uid,
                'type'  => 3,
                'desc'  => $info,
                'opId'  => $this->userId,
            );
            return $this->import('addition')->create($data);
        }
        $r['eq'] = array('id'=>$_info['id']);
        $data = array(
            'desc'  => $info,
            'opId'  => $this->userId,
            );
        return $this->import('addition')->modify($data, $r);
    }

    //通过类型获取用户附加信息
    public function getAdditionByType($uid, $type)
    {
        if ( $uid <= 0 ) return array();
        if ( $type <= 0 || $type > 254 ) return array();

        $r['eq']    = array('uid'=>$uid,'type'=>$type);
        $r['limit'] = 1000;

        $list   = $this->import('addition')->find($r);
        //$res    = arrayColumn($list, 'desc', 'id');
        return $list;
    }
}
?>