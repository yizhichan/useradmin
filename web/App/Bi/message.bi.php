<?
/**
 * 消息Api代理接口
 *
 * 发送邮件、短信
 *
 * @package	Bi
 * @author	Xuni
 * @since	2015-11-05
 */
class MessageBi extends Bi
{
	/**
	 * 接口标识
	 */
	public $apiId = 2;

	/**
	 * 发送邮件
	 * 
	 * @author	Xuni
	 * @since	2015-11-05
	 *
	 * @access	public
	 * @param	string	$email		收件人邮箱
	 * @param	string	$title		邮件标题
	 * @param	string	$content	邮件内容
	 * @param	string	$name		收件人名称[没有则为空]
	 * @param	string	$from		签名
	 * 
	 * @return	array
	 */
	public function sendMail($email, $title, $content, $name = '', $from='超凡网')
	{
		$param = array(
			'email'   => $email,
			'title'   => $title,
			'content' => $content,
			'name'	  => $name,
			'from'	  => $from,
		);
		
		return $this->request("message/sendMail/", $param);
	}

	/**
	 * 发送短信
	 * 
	 * @author	Xuni
	 * @since	2015-11-05
	 *
	 * @access	public
	 * @param	string	$mobile		收件人手机号
	 * @param	string	$content	邮件内容
	 * @param	int 	$tplId		模板Id
	 * 
	 * @return	array
	 */
	public function sendMsg($mobile, $content, $tplId=0)
	{
		$param = array(
			'mobile'   	=> $mobile,
			'content' 	=> $content,
			'tplId'	  	=> $tplId,
		);
		return $this->request("message/sendMsg/", $param);
	}

}
?>