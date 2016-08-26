<?
/**
 * 会员列表
 *
 * 会员列表显示
 *
 * @package    Action
 * @author    Xuni
 * @since    2016-08-10
 */
class MemberAction extends AppAction
{

    /**
     * 控制器默认方法
     * @author    Xuni
     * @since    2016-08-10
     *
     * @access    public
     * @return    void
     */
    public function index()
    {
        $page   = $this->input('page', 'int', '1');

        $params = array();
        $params['uid']      = $this->input('uid', 'int', '');
        $params['mobile']   = $this->input('mobile', 'string', '');

        $res    = $this->load('member')->getList($params, $page, $this->rowNum);

        $total  = empty($res['total']) ? 0 : $res['total'];
        $list   = empty($res['rows']) ? array() : $res['rows'];

        $pager  = $this->pager($total, $this->rowNum, 10, 'active');
        $pBar   = empty($list) ? '' : getPageBar($pager, true);

        $list   = $this->load('member')->getListOther($list);

        $this->set('search', $params);
        $this->set("pageBar", $pBar);
        $this->set('list', $list);//debug($list);
        $this->display();
    }

    public function view()
    {
        $uid    = $this->input('uid', 'int', '0');
        $info   = $this->load('member')->getInfoById($uid);
        if ( !empty($info) ){
            $info['buyCount']   = $this->load('buy')->countUidMobile($uid, $info['mobile']);
            $info['saleCount']  = $this->load('sale')->countUidMobile($uid, $info['mobile']);

            $doudou             = $this->load('member')->getMemberDoudou($uid);
            $info['doudou']     = intval($doudou['amount']);

            $info['tags']       = $this->load('member')->getAdditionByType($uid, 1);
            $info['memo']       = $this->load('member')->getAdditionByType($uid, 2);
            $contact            = current( $this->load('member')->getAdditionByType($uid, 3) );
            $info['contact']    = unserialize( $contact['desc'] );
        }
        
        $referr = $this->getReferrUrl('member_view');
        $this->set('referr', $referr);
        $this->set('info', $info);
        $this->set('USES', $this->load('user')->getAllUserName());
        $this->display();
    }

    public function buyer()
    {
        $uid    = $this->input('uid', 'int', '0');
        $type   = $this->input('t', 'int', '1');

        $info   = $this->load('member')->getInfoById($uid);
        if ( !empty($info) ){
            $info['buyList']        = $this->load('buy')->getListByUidMoblie($uid, $info['mobile']);
            $info['collectList']    = $this->load('member')->getMemberCollect($uid);
        }
        $referr = $this->getReferrUrl('member_view');
        $this->set('referr', $referr);
        $this->set('info', $info);
        $this->set('SOURCE', C('SOURCE'));
        $this->display();
    }

    public function seller()
    {
        $uid    = $this->input('uid', 'int', '0');
        $info   = $this->load('member')->getInfoById($uid);
        if ( !empty($info) ){
            $info['saleList']   = $this->load('sale')->getListByUidMoblie($uid, $info['mobile']);
            $info['doudouList'] = $this->load('member')->getMemberDouLog($uid);
        }
        $referr = $this->getReferrUrl('member_view');
        $this->set('referr', $referr);
        $this->set('info', $info);
        $this->set('SOURCE', C('SOURCE'));
        $this->display();
    }

    public function update()
    {
        $uid    = $this->input('uid', 'int', 0);
        $name   = $this->input('name', 'string', '');
        $list   = array('nickname','doudou','isVerify','password', 'isUse');//可修改的字段
        if ( $uid <= 0 || !in_array($name, $list) ){
            $this->returnAjax(array('code'=>2,'msg'=>'参数错误'));
        }

        switch ($name) {
            case 'nickname':
                $val    = $this->input('value', 'string', '');
                if ( empty($val) ) $this->returnAjax(array('code'=>2,'msg'=>'昵称不能为空'));
                $flag   = $this->load('member')->updateNickname($uid, $val);
                break;            
            case 'doudou':
                $val    = $this->input('value', 'int', 0);
                if ( !is_int($val) || $val < 0 ) $this->returnAjax(array('code'=>2,'msg'=>'蝉豆应为数字且不能为负数'));
                $flag   = $this->load('member')->updateDoudou($uid, $val);
                break;
            case 'isVerify':
                $val    = $this->input('value', 'int', 0);
                $flag   = $this->load('member')->updateVerify($uid, $val);
                break;
            case 'password':
                $val    = $this->input('value', 'string', '');
                if ( empty($val) ) $this->returnAjax(array('code'=>2,'msg'=>'登录密码不能为空'));
                $flag   = $this->load('member')->updatePassword($uid, $val);
                break;
            case 'isUse':
                $val    = $this->input('value', 'int', 0);
                if ( !is_int($val) || $val < 0 ) $this->returnAjax(array('code'=>2,'msg'=>'登录密码不能为空'));
                $flag   = $this->load('member')->updateIsUse($uid, $val);
                break;
        }
        if ( $flag ) $this->returnAjax(array('code'=>1,'msg'=>'操作成功'));
        $this->returnAjax(array('code'=>2,'msg'=>'操作失败'));
    }

    //添加用户扩展信息
    public function addition()
    {
        $uid    = $this->input('uid', 'int', 0);
        $type   = $this->input('type', 'int', 0);
        $desc   = $this->input('desc', 'string', '');

        if ( $uid <= 0 || $type <=0 || $type > 254 ){
            $this->returnAjax(array('code'=>2,'msg'=>'参数错误'));
        }

        switch ($type) {
            case '1'://tags
                if ( empty($desc) ) $this->returnAjax(array('code'=>2,'msg'=>'标签不能为空'));
                if ( $this->load('member')->existAddition($uid, '1', $desc) > 0 ){
                    $this->returnAjax(array('code'=>2,'msg'=>'标签已存在'));
                }
                $flag = $this->load('member')->addAddition($uid, '1', $desc);
                break;
            case '2'://备注
                if ( empty($desc) ) $this->returnAjax(array('code'=>2,'msg'=>'用户备注不能为空'));
                if ( $this->load('member')->existAddition($uid, '2', $desc) > 0 ){
                    $this->returnAjax(array('code'=>2,'msg'=>'备注已存在'));
                }
                $flag = $this->load('member')->addAddition($uid, '2', $desc);
                break;
            default:
                $this->returnAjax(array('code'=>2,'msg'=>'参数错误'));
                break;
        }
        if ( $flag ) $this->returnAjax(array('code'=>1,'msg'=>'操作成功'));
        $this->returnAjax(array('code'=>2,'msg'=>'操作失败'));
    }

    public function delAddition()
    {
        $aid    = $this->input('aid', 'int', 0);
        if ( $aid <= 0 ) $this->returnAjax(array('code'=>2,'msg'=>'参数错误'));
        $flag = $this->load('member')->deleteAddition($aid);

        if ( $flag ) $this->returnAjax(array('code'=>1,'msg'=>'操作成功'));
        $this->returnAjax(array('code'=>2,'msg'=>'操作失败'));
    }

    //更新用户联系方式
    public function updateContactInfo()
    {
        $uid    = $this->input('uid', 'int', 0);
        $desc   = $this->input('desc', 'string', '');
        $strArr = explode(',', $desc);
        $tmpArr = array_filter($strArr);
        if ( empty($tmpArr) ){
             $this->returnAjax(array('code'=>2,'msg'=>'请填写数据'));
        }
        $data   = array();
        list($data['name'], $data['qq'], $data['phone'], $data['email']) = $strArr;

        if ( $uid <= 0 ) $this->returnAjax(array('code'=>2,'msg'=>'参数错误'));
        $flag = $this->load('member')->updateContactInfo($uid, serialize($data));

        if ( $flag ) $this->returnAjax(array('code'=>1,'msg'=>'操作成功'));
        $this->returnAjax(array('code'=>2,'msg'=>'操作失败'));
    }

}
?>