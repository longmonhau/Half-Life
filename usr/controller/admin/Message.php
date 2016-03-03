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
		$skip = 0;
		$pageNum = 10;

		if( !$page = intval($this->get("page")) )
		{
			$page = 1;
		}
		$skip = ($page-1)*$pageNum;
		$msgList = $this->msgModel->where("resp",0)->orderby("created_at","DESC")->skip($skip)->limit( $pageNum )->get();

		$return_msg = [];
		foreach ($msgList as $msg) 
		{
			$msg->ireply = $this->msgModel->where("resp",$msg->id)->get();
			$return_msg[] = $msg;
		}

		if( $this->AjaxRequest )
    	{
			return $this->renderJson(["code"=>200,"msgList"=>$return_msg]);
		} else {
			$this->assign("user", tSession::getLoginedUserInfo());
			$this->assign("messageList", $return_msg);
			return $this->display("adminMessages.html");
		}

	}

	public function updateStatus()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
			$msgList = $this->post("msgList");
			$msgList_array = explode("|", $msgList);
			$this->msgModel->updateStatus( $msgList_array );
		}
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
		
		$this->assign("user", tSession::getLoginedUserInfo());
		return $this->display("messageView.html");
	}


	public function reply()
	{
		$mid = intVal($this->post("mid"));
		$resbody = nl2br(strip_tags( $this->post("content")));
		if( !$msg = $this->msgModel->getMessageById( $mid ) )
		{
			return $this->renderJson(401,"消息不存在！");
		}
		$user = tSession::getLoginedUserInfo();
		$Resp = [];
		$Resp['resp'] = $mid;
		$Resp['name'] = $user->name;
		$Resp['email'] = $user->email;
		$Resp['gravatar'] = $user->avatar;
		$Resp['msgbody'] = $resbody;
		$Resp['created_at'] = date("Y-m-d H:i:s");
		$this->msgModel->insert( $Resp );

		$mailer = Mailer::newInstance();
		$mailer->addAddress( $msg->email, $msg->name);
		$mailer->addReplyTo( $user->email,$user->name);
		$mailer->isHTML(TRUE);
		$mailer->Subject = "来自 ".$this->siteInfo['site_name']." 的私信回复";
		$mailer->Body = "<img src='".$user->avatar."' style='width:50px;height:50px;border-radius:100%;float:left; margin-right:1em;'/> "
					.$user->name."&nbsp;&nbsp;".date("Y-m-d H:i:s")."<br/>".$resbody
					."<div style='width:100%;float:left;border-top:1px dashed #CCC;padding-top:1em;margin-top:1em;'>您在"
					.$msg->created_at."发来的私信内容：<br/>".$msg->msgbody."</div>";
		if( !$mailer->send() )
		{
			Log::error($mailer->ErrorInfo);
		}

		return $this->renderJson(200,"ok");
	}

	public function del()
	{
		if( $this->AjaxRequest )
		{
			$mid = $this->post("msgid");
			$mid = explode("|", $mid);
			if( $msg = $this->msgModel->destroy($mid) )
			{
				$this->msgModel->whereIn("resp", $mid)->delete();
				return $this->renderJson(['code'=>200,'errmsg'=>"ok",'go_url'=>'/Admin/Message/List']);
			} else
			{
				return $this->renderJson(401,"消息{$mid}不存在！");
			}
		} else{
			return $this->renderString("Access denied!");
		}
	}
}
