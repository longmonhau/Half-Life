<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\core\component\Mailer;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\component\Log;

class Message extends Control
{
	use SiteInfo;
	private $msgModel = NULL;
	private $siteInfo = NULL;

	public function __construct()
	{
		parent::__Construct();
		$this->msgModel = Model::make("Message");
		$this->siteInfo = $this->getSiteInfo();
	}
	public function index()
	{
		return $this->display("adminMessge.html");
	}

	public function view($mid)
	{

		if( $msg = $this->msgModel->getMessageById($mid) )
		{
			$msg_res = $this->msgModel->where("resp",$mid)->get();
			$this->assign("msg", $msg);
			$this->assign("msgRes", $msg_res );
		} else{
			$this->assign("no_msg_err", true);
		}

		return $this->display("messageView.html");
	}


	public function reply()
	{
		$request = Factory::make("request");
		$mid = intVal($request->get("mid"));
		$resbody = nl2br(strip_tags( $request->get("content")));
		if( !$msg = $this->msgModel->getMessageById( $mid ) )
		{
			return $this->renderJson(401,"消息不存在！");
		}
		$user = tSession::getLoginedUserInfo();
		$Resp = [];
		$Resp['resp'] = $mid;
		$Resp['name'] = $user->name;
		$Resp['email'] = $user->email;
		$Resp['gravatar'] = md5($user->email);
		$Resp['msgbody'] = $resbody;
		$Resp['created_at'] = date("Y-m-d H:i:s");
		$this->msgModel->insert( $Resp );

		$mailer = Mailer::newInstance();
		$mailer->addAddress( $msg->email, $msg->name);
		$mailer->addReplyTo( $user->email,$user->name);
		$mailer->isHTML(TRUE);
		$mailer->Subject = "来自 ".$this->siteInfo['site_name']." 的私信回复";
		$mailer->Body = $resbody;
		if( !$mailer->send() )
		{
			Log::error($mailer->ErrorInfo);
		}

		return $this->renderJson(200,"ok");
	}

	public function del()
	{
		$request = Factory::make("request");
		$mid = intval($request->get("msgid"));
		if( $msg = $this->msgModel->getMessageById($mid) )
		{
			$msg->delete();
			$this->msgModel->where("resp", $mid)->delete();
			return $this->renderJson(['code'=>200,'errmsg'=>"ok",'go_url'=>'Admin/Feeds']);
		} else
		{
			return $this->renderJson(401,"消息{$mid}不存在！");
		}
	}
}
