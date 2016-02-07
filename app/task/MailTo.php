<?php namespace lOngmon\Hau\usr\task;

include_once __DIR__.'/../../vendor/autoload.php';
use lOngmon\Hau\core\component\Mailer;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\component\Log;
use lOngmon\Hau\core\Kernel;
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
		$this->mail->Subject = "Your received a comment!";
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
}

$mailer = new MailTo;
$opt = getopt("f:d:");
call_user_func( [$mailer, $opt['f']], $opt['d']);