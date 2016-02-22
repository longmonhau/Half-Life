<?php namespace lOngmon\Hau\usr\task;

include_once __DIR__.'/../../vendor/autoload.php';
use lOngmon\Hau\core\component\Mailer;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\component\Log;
use lOngmon\Hau\core\Kernel;
use lOngmon\Hau\core\Model;
Kernel::boot();

class MailTo
{
	use SiteInfo;

	private $mail = NULL;
	private $siteinfo = NULL;

	public function __construct()
	{
		$this->mail = Mailer::newInstance();
		$this->siteinfo = $this->getSiteInfo();
	}

	public function commentEmail( $data )
	{
		$data = json_decode( base64_decode( $data ) );
		$this->mail->addAddress( $data->receiver,"root");
		$this->mail->addReplyTo($data->email,$data->name);
		$this->mail->isHTML(TRUE);
		$this->mail->Subject = "You received a comment!";
		$this->mail->Body = '<div style="width:1000px;margin:0 auto;font-size:17px; border-bottom:1px dashed #999;margin-bottom:15px;">'
							.'Title:<a style="text-decoration:none;" href="http://'.$this->siteinfo['site_domain'].'/Blog/'.$data->post->url.'.html">'.$data->post->title.'</a></div>'
							.'<div style="width:1000px; margin:0 auto;"><div style="width:60px; float:left;">'
							.'<img style="width:50px;height:50px;border-radius:100%;" src="http://www.gravatar.com/avatar/'.$data->gravatar.'"/></div>'
							.'<div style="width:930px;float:right;"><div style="width:930px; font-weight:bold;">'.$data->name.'('.$data.email.')</div>'
							.'<div style="width:930px;margin-top:10px;">'.$data->content.'</div></div></div>';
		if( !$this->mail->send() )
		{
			Log::error($this->mail->ErrorInfo);
		}
	}

	public function messageEmail( $msgId )
	{
		$messageModel = Model::make("Message");
		if( !$msg = $messageModel->getMessageById( $msgId ) )
		{
			return;
		}
		$this->mail->addAddress("1307995200@qq.com","root");
		$this->mail->addReplyTo( $msg->email, $msg->name);
		$this->mail->isHTML(TRUE);
		$this->mail->Subject = "您收到一条私信！";
		$this->mail->Body = '<div style="width:1000px;">'
							.'<div style="width:100px; float:left;">'
							.'<img src="" style="width:60px;height:60px;borderr-radius:100%;"/>'
							.'</div><div style="width:900px;float:left;">'.$msg->name."&nbsp;&nbsp;".date("Y-m-d H:i:s")."<br/>".$msg->msgbody.'</div></div>';
		if( !$this->mail->send() )
		{
			Log::error($this->mail->ErrorInfo);
		}
	}
}

$mailer = new MailTo;
$opt = getopt("f:d:");
call_user_func( [$mailer, $opt['f']], $opt['d']);
