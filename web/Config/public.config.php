<?
/**
 * 定义应用所需常量
 */
$checkhost 	= include(ConfigDir.'/checkhost.config.php');
$define = array(
	'COOKIE_USER' => 'UsErAdMiNyZc',

	'searchapi'	=> array(
		'key'	=> '89eb637c610f94b9d281c458bca42421',
		'url'	=> 'http://searchapi.chofn.net/trademark/search/',
	),
	'proposerApi'	=> array(
		'key'	=> '89eb637c610f94b9d281c458bca42421',
		'url'	=> 'http://tmsearch.chofn.api/proposer/search/',
	),
	'noticeApi'	=> array(
		'key'	=> '89eb637c610f94b9d281c458bca42421',
		'url'	=> 'http://tmsearch.chofn.api/notice/search/',
	),
	
	'MSG_TEMPLATE' => array(
		'valid'     	=> "验证码：%s，有效期为10分钟，请尽快使用。退订回N",
		'register'  	=> "%s（登录密码），系统已为您开通手机账户，登陆可查看求购进展，工作人员不会向你索要，请勿向任何人泄露。退订回N",
		'newValid'  	=> "%s（动态登录密码），仅本次有效，请在10分钟内使用。工作人员不会向你索要，请勿向任何人泄露。退订回N",
		'validBind' 	=> "%s（手机绑定校验码），仅本次有效请在10分钟内使用。工作人员不会向你索要，请勿向任何人泄露。如非本人操作，请忽略。退订回N",
		'weixin'     	=> "%s（微信解绑校验码），有效期为5分钟，请尽快使用。退订回N",
	),
	'MSG_TEMPLATEID' => array(
		'valid'     	=> "848",
		'register'  	=> "849",
		'newValid'  	=> "850",
		'validBind' 	=> "851",
		'weixin'     	=> "852",
	),
	'FOLLOW'		=> array(1=>'商标' , 2=>'专利', 3=>'版权',4=>'其他'),
	//来源渠道
    'SOURCE' => array(
        '1'  => '管家',
        '2'  => '顾问',
		'4'  => '展示页',
		'5'  => '引导页',
		'6'  => '400',
		'7'  => '乐语',
		'8'  => '百度商桥',
		'3'  => '其他',
		'9'  => '同行',
		'10'  => '一只蝉',
        '11'  => '用户中心',
        '12'  => '出售者平台',
        '13'  => '一只蝉手机端',
    ),
	
);
if(is_array($checkhost)){
	$define = array_merge($define,$checkhost);	
}

return $define;

?>